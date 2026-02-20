<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\DocumentShared;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();

        $stats = [
            'total_users'      => User::count(),
            'active_users'     => User::where('status', 'actif')->count(),
            'pending_users'    => User::where('status', 'en_attente')->count(),
            'total_documents'  => Document::count(),
            'shared_documents' => Document::where('status', 'partage')->count(),
        ];

        $users      = User::latest()->take(5)->get();
        $documents  = Document::with('user')->latest()->take(5)->get();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        $folders    = Folder::withCount('documents')->get();

        $storageUsed    = Document::sum('size');
        $storageLimit   = 10 * 1024 * 1024 * 1024;
        $storagePercent = $storageLimit > 0 ? round(($storageUsed / $storageLimit) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'stats', 'users', 'documents', 'recentLogs',
            'folders', 'storagePercent', 'storageUsed', 'storageLimit'
        ));
    }

    public function users()
    {
        $this->checkAdmin();
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $this->checkAdmin();
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,manager,collaborateur,lecteur',
            'status'   => 'required|in:actif,en_attente,bloque',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        ActivityLog::log('création utilisateur', $user, "Création du compte {$user->name}");

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé.');
    }

    public function toggleUserStatus(User $user)
    {
        $this->checkAdmin();

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Impossible de bloquer votre propre compte.');
        }

        $user->status = $user->status === 'bloque' ? 'actif' : 'bloque';
        $user->save();

        ActivityLog::log('blocage compte', $user, "Statut de {$user->name} : {$user->status}");

        return redirect()->back()->with('success', "Statut de {$user->name} mis à jour.");
    }

    public function activateUser(User $user)
    {
        $this->checkAdmin();

        $user->status = 'actif';
        $user->save();

        ActivityLog::log('activation compte', $user, "Activation de {$user->name}");

        return redirect()->back()->with('success', "{$user->name} activé.");
    }

    public function updateUserRole(Request $request, User $user)
    {
        $this->checkAdmin();

        $request->validate(['role' => 'required|in:admin,manager,collaborateur,lecteur']);

        $oldRole    = $user->role;
        $user->role = $request->role;
        $user->save();

        ActivityLog::log('modification rôle', $user, "Rôle de {$user->name} : {$oldRole} → {$user->role}");

        return redirect()->back()->with('success', 'Rôle mis à jour.');
    }

    public function destroyUser(User $user)
    {
        $this->checkAdmin();

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        ActivityLog::log('suppression utilisateur', $user, "Suppression de {$user->name}");
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé.');
    }

    public function documents()
    {
        $this->checkAdmin();
        $documents = Document::with('user')->latest()->paginate(30);
        return view('admin.documents', compact('documents'));
    }

    public function logs()
    {
        $this->checkAdmin();
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.logs', compact('logs'));
    }

    public function exportLogs()
    {
        $this->checkAdmin();

        $logs = ActivityLog::with('user')->latest()->get();
        $csv  = "Date,Utilisateur,Action,Description,IP\n";

        foreach ($logs as $log) {
            $csv .= implode(',', [
                $log->created_at->format('d/m/Y H:i'),
                $log->user?->name ?? 'Inconnu',
                $log->action,
                str_replace(',', ';', $log->description ?? ''),
                $log->ip_address ?? '',
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="journal.csv"');
    }

    public function shareDocument(Request $request, Document $document)
    {
        $this->checkAdmin();

        $request->validate([
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'permission' => 'in:lecture,modification',
        ]);

        foreach ($request->user_ids as $userId) {
            $document->sharedWith()->syncWithoutDetaching([
                $userId => [
                    'permission'        => $request->permission ?? 'lecture',
                    'shared_by_user_id' => auth()->id(),
                ],
            ]);
        }

        $document->status = 'partage';
        $document->save();

        ActivityLog::log('partage', $document, "Document partagé avec " . count($request->user_ids) . " utilisateur(s)");

        foreach ($request->user_ids as $userId) {
            $recipient = \App\Models\User::find($userId);
            if ($recipient) {
                Mail::to($recipient->email)->send(
                    new DocumentShared($document, auth()->user(), $request->permission ?? 'lecture')
                );
                sleep(1);
            }
        }

        return redirect()->back()->with('success', 'Document partagé.');
    }
    public function updateUserQuota(Request $request, User $user)
    {
        $this->checkAdmin();

        $request->validate([
            'storage_quota' => 'required|integer|min:104857600',
        ]);

        $user->storage_quota = $request->storage_quota;
        $user->save();

        ActivityLog::log('modification quota', $user, "Quota de {$user->name} modifié");

        return redirect()->back()->with('success', "Quota de {$user->name} mis à jour.");
    }
}