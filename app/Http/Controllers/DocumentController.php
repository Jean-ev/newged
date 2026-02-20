<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $folders  = Folder::where('user_id', $user->id)
                          ->withCount('documents')
                          ->get();
        $documents = Document::where('user_id', $user->id)
                             ->latest()
                             ->take(20)
                             ->get();

        $storageUsed    = Document::where('user_id', $user->id)->sum('size');
        $storageLimit   = 10 * 1024 * 1024 * 1024;
        $storagePercent = $storageLimit > 0 ? round(($storageUsed / $storageLimit) * 100, 1) : 0;

        return view('documents.index', compact(
            'folders', 'documents', 'storageUsed', 'storageLimit', 'storagePercent'
        ));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|max:204800',
            'folder_id' => 'nullable|exists:folders,id',
            'status'    => 'in:prive,partage',
        ]);

        // Vérifier le quota
        $user = auth()->user();
        if (!$user->hasStorageSpace($request->file('file')->getSize())) {
            return redirect()->back()->withErrors([
                'file' => 'Quota de stockage dépassé. Vous avez utilisé ' . $user->storage_used_formatted . ' sur ' . $user->storage_quota_formatted . '.'
            ]);
        }

        $file     = $request->file('file');
        $mimeType = $file->getMimeType();
        $type     = Document::detectType($mimeType);
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('documents/' . auth()->id(), $filename);

        $document = Document::create([
            'name'          => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $mimeType,
            'size'          => $file->getSize(),
            'type'          => $type,
            'status'        => $request->status ?? 'prive',
            'user_id'       => auth()->id(),
            'folder_id'     => $request->folder_id,
        ]);

        ActivityLog::log('upload', $document, "Upload du fichier {$document->original_name}");

        return redirect()->back()->with('success', 'Fichier importé avec succès !');
    }

    public function sharedWithMe()
    {
        $documents = auth()->user()->sharedDocuments()->latest()->get();
        return view('documents.shared', compact('documents'));
    }

    public function recent()
    {
        $documents = Document::where('user_id', auth()->id())
                             ->latest()
                             ->paginate(20);
        return view('documents.recent', compact('documents'));
    }

    public function favorites()
{
    $documents = Document::where('user_id', auth()->id())
                         ->where('is_favorite', true)
                         ->latest()
                         ->paginate(20);
    return view('documents.favorites', compact('documents'));
}

    public function download(Document $document)
    {
        $user = auth()->user();

        if ($document->user_id !== $user->id && !$user->isAdmin()) {
            $hasAccess = $user->sharedDocuments()->where('document_id', $document->id)->exists();
            if (!$hasAccess) abort(403, 'Accès non autorisé.');
        }

        ActivityLog::log('téléchargement', $document, "Téléchargement de {$document->original_name}");

        return Storage::download($document->path, $document->original_name);
    }

    public function destroy(Document $document)
    {
        $user = auth()->user();
        if ($document->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        Storage::delete($document->path);
        ActivityLog::log('suppression', $document, "Suppression de {$document->original_name}");
        $document->delete();

        return redirect()->back()->with('success', 'Document supprimé.');
    }

    public function toggleStatus(Document $document)
    {
        $user = auth()->user();
        if ($document->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $document->status = $document->status === 'prive' ? 'partage' : 'prive';
        $document->save();

        ActivityLog::log('modification', $document, "Statut changé à {$document->status}");

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $documents = Document::where('user_id', auth()->id())
            ->where(function($q) use ($query) {
                $q->where('original_name', 'LIKE', "%{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->take(20)
            ->get();

        $sharedDocuments = auth()->user()->sharedDocuments()
            ->where(function($q) use ($query) {
                $q->where('original_name', 'LIKE', "%{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->get();

        $documents = $documents->merge($sharedDocuments);

        return view('documents.search', compact('documents', 'query'));
    }
    public function preview(Document $document)
    {
        $user = auth()->user();

        if ($document->user_id !== $user->id && !$user->isAdmin()) {
            $hasAccess = $user->sharedDocuments()->where('document_id', $document->id)->exists();
            if (!$hasAccess) abort(403, 'Accès non autorisé.');
        }

        if ($document->type !== 'pdf') {
            return redirect()->back()->with('error', 'Prévisualisation disponible uniquement pour les PDFs.');
        }

        return response()->file(storage_path('app/private/' . $document->path), [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
            ]);
        }
        public function toggleFavorite(Document $document)
    {
        $user = auth()->user();
        if ($document->user_id !== $user->id) {
            abort(403);
        }

        $document->is_favorite = !$document->is_favorite;
        $document->save();

        return redirect()->back()->with('success', $document->is_favorite ? 'Ajouté aux favoris !' : 'Retiré des favoris.');
    }
    public function trash()
    {
        $documents = Document::onlyTrashed()
                            ->where('user_id', auth()->id())
                            ->latest('deleted_at')
                            ->paginate(20);
        return view('documents.trash', compact('documents'));
    }

    public function restore($id)
    {
        $document = Document::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);
        $document->restore();
        ActivityLog::log('restauration', $document, "Restauration de {$document->original_name}");
        return redirect()->back()->with('success', 'Document restauré !');
    }

    public function forceDelete($id)
    {
        $document = Document::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);
        Storage::delete($document->path);
        $document->forceDelete();
        return redirect()->back()->with('success', 'Document supprimé définitivement.');
    }

    public function emptyTrash()
    {
        $documents = Document::onlyTrashed()->where('user_id', auth()->id())->get();
        foreach ($documents as $doc) {
            Storage::delete($doc->path);
            $doc->forceDelete();
        }
        return redirect()->back()->with('success', 'Corbeille vidée !');
    }
}