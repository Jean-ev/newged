<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Folder::create([
            'name'      => $request->name,
            'user_id'   => auth()->id(),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Dossier créé.');
    }

    public function show(Folder $folder)
    {
        if ($folder->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $documents = $folder->documents()->latest()->paginate(20);
        return view('documents.folder', compact('folder', 'documents'));
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $folder->delete();
        return redirect()->back()->with('success', 'Dossier supprimé.');
    }
}