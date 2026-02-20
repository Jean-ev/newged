@extends('layouts.app')
@section('title', 'Administration')
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Mes Documents</h1>
    <p class="page-subtitle">Vue d'ensemble de vos fichiers et contrôle centralisé des utilisateurs</p>
  </div>
  <a href="{{ route('documents.index') }}" class="btn btn-primary">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nouveau Fichier
  </a>
</div>

{{-- Accès Rapide --}}
<h2 class="section-title">Accès Rapide</h2>
<div class="folder-grid">
  @forelse($folders->take(4) as $folder)
  <a href="{{ route('folders.show', $folder) }}" class="folder-card">
    <div class="folder-card-top">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" fill="#FCD34D" stroke="#F59E0B" stroke-width="1.2"/>
        <path d="M2 10h20" stroke="#F59E0B" stroke-width="1.2"/>
      </svg>
      <button class="folder-menu-btn" onclick="event.preventDefault()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
      </button>
    </div>
    <div class="folder-name">{{ $folder->name }}</div>
    <div class="folder-meta">{{ $folder->documents_count }} fichiers</div>
  </a>
  @empty
  <p style="color:#9CA3AF; font-size:13px;">Aucun dossier.</p>
  @endforelse
</div>

{{-- Documents Récents --}}
<h2 class="section-title">Documents Récents</h2>
<div class="table-card" style="margin-bottom:28px;">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Nom</th>
        <th>Propriétaire</th>
        <th>Date de modification</th>
        <th>Taille</th>
        <th>Type</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($documents as $doc)
      <tr>
        <td style="padding-left:22px;">
          <div class="fname-cell">
            <div class="ftype-icon {{ $doc->type }}">
              @if($doc->type === 'pdf')
                <svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              @elseif($doc->type === 'image')
                <svg viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              @elseif($doc->type === 'excel')
                <svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              @elseif($doc->type === 'word')
                <svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              @elseif($doc->type === 'video')
                <svg viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
              @endif
            </div>
            <span class="fname-label">{{ $doc->original_name }}</span>
          </div>
        </td>
        <td style="color:#6B7280;">{{ $doc->user->name }}</td>
        <td style="color:#6B7280;">{{ $doc->updated_at->format('d M Y, H:i') }}</td>
        <td style="color:#6B7280;">{{ $doc->formatted_size }}</td>
        <td style="color:#6B7280; text-transform:uppercase; font-size:12px;">{{ $doc->type }}</td>
        <td>
          <a href="{{ route('documents.download', $doc) }}" class="btn btn-secondary btn-sm">Télécharger</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center; padding:32px; color:#9CA3AF;">Aucun document.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Administration utilisateurs --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
  <h2 class="section-title" style="margin:0;">Administration des utilisateurs</h2>
  <span style="font-size:12px; color:#9CA3AF;">Vue centralisée pour contrôler tous les comptes de l'entreprise</span>
</div>

<div class="three-col">

  {{-- Colonne 1 : Utilisateurs --}}
  <div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
      <span class="card-title">Utilisateurs</span>
      <span class="badge-blue">{{ $stats['active_users'] }} comptes actifs</span>
    </div>
    <div class="card-sub">Liste des comptes et rôles associés</div>
    <table class="mini-table">
      <thead>
        <tr>
          <th>Nom</th><th>Rôle</th><th>Statut</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td style="font-weight:500; color:#111827;">{{ $user->name }}</td>
          <td style="color:#6B7280; font-size:12px;">{{ ucfirst($user->role) }}</td>
          <td>
            @if($user->status === 'actif')
              <span class="badge-green">Actif</span>
            @elseif($user->status === 'en_attente')
              <span class="badge-yellow">En attente</span>
            @else
              <span class="badge-red">Bloqué</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="display:flex; gap:8px; margin-top:14px;">
      <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm" style="flex:1; justify-content:center;">Voir tous</a>
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm" style="flex:1; justify-content:center;">Créer un utilisateur</a>
    </div>
  </div>

  {{-- Colonne 2 : Rôles --}}
  <div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
      <span class="card-title">Rôles & permissions</span>
      <span class="badge-gray">4 modèles de rôle</span>
    </div>
    <div class="card-sub">Contrôler les droits sur les documents</div>
    <table class="mini-table">
      <thead>
        <tr><th>Rôle</th><th>Accès</th></tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-weight:600; font-size:12px;">Administrateur</td>
          <td style="color:#6B7280; font-size:11.5px;">Tout gérer (utilisateurs, rôles, documents)</td>
        </tr>
        <tr>
          <td style="font-weight:600; font-size:12px;">Manager</td>
          <td style="color:#6B7280; font-size:11.5px;">Valider, partager, supprimer dans son service</td>
        </tr>
        <tr>
          <td style="font-weight:600; font-size:12px;">Collaborateur</td>
          <td style="color:#6B7280; font-size:11.5px;">Créer, modifier, proposer des mises à jour</td>
        </tr>
        <tr>
          <td style="font-weight:600; font-size:12px;">Lecteur</td>
          <td style="color:#6B7280; font-size:11.5px;">Lecture seule sur les dossiers partagés</td>
        </tr>
      </tbody>
    </table>
    <div style="margin-top:14px;">
      <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm" style="width:100%; justify-content:center;">Gérer les rôles</a>
    </div>
  </div>

  {{-- Colonne 3 : Journal --}}
  <div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
      <span class="card-title">Journal d'activité</span>
      <span class="badge-gray">Dernières 24h</span>
    </div>
    <div class="card-sub">Suivi des actions sensibles</div>
    <table class="mini-table">
      <thead>
        <tr><th>Action</th><th>Utilisateur</th><th>Date</th></tr>
      </thead>
      <tbody>
        @forelse($recentLogs as $log)
        <tr>
          <td style="font-weight:500; font-size:12px; color:#111827;">{{ $log->action }}</td>
          <td style="color:#6B7280; font-size:11.5px;">{{ $log->user?->name ?? '—' }}</td>
          <td style="color:#9CA3AF; font-size:11px; white-space:nowrap;">{{ $log->created_at->format('d/m H:i') }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="color:#9CA3AF; padding:12px 0; font-size:12px;">Aucune activité récente</td></tr>
        @endforelse
      </tbody>
    </table>
    <div style="margin-top:14px;">
      <a href="{{ route('admin.logs.export') }}" class="btn btn-secondary btn-sm" style="width:100%; justify-content:center;">Exporter le journal</a>
    </div>
  </div>

</div>

@endsection