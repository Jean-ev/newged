@extends('layouts.app')

@section('title', 'Mes Documents')

@section('content')

<div class="page-header">
  <h1 class="page-title">Mes Documents</h1>
  <button class="btn btn-primary" onclick="document.getElementById('uploadModal').classList.add('open')">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
      <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
    </svg>
    Importer un fichier
  </button>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
  <h2 class="section-title" style="margin:0;">Dossiers</h2>
  <button class="btn btn-secondary btn-sm" onclick="document.getElementById('folderModal').classList.add('open')">+ Nouveau dossier</button>
</div>

@if($folders->count())
<div class="folder-grid">
  @foreach($folders as $folder)
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
    <div class="folder-meta">{{ $folder->documents_count }} fichier(s)</div>
  </a>
  @endforeach
</div>
@endif

<h2 class="section-title">Fichiers Récents</h2>

@if($documents->count())
<div class="table-card">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Nom</th>
        <th>Modifié le</th>
        <th>Taille</th>
        <th>Statut</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($documents as $doc)
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
        <td style="color:#6B7280;">{{ $doc->updated_at->format('d M Y') }}</td>
        <td style="color:#6B7280;">{{ $doc->formatted_size }}</td>
        <td>
          @if($doc->status === 'partage')
            <span class="badge-shared">Partagé</span>
          @else
            <span class="badge-private">Privé</span>
          @endif
        </td>
        <td>
          <div class="row-actions">
            <button class="row-dots" onclick="toggleRowMenu(this)">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
            </button>
            <div class="row-menu">
              @if($doc->type === 'pdf')
              <a href="{{ route('documents.preview', $doc) }}" target="_blank">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Prévisualiser
              </a>
              @endif
              <form action="{{ route('documents.favorite', $doc) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $doc->is_favorite ? '#F59E0B' : 'none' }}" stroke="{{ $doc->is_favorite ? '#F59E0B' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  {{ $doc->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                </button>
              </form>
              <a href="{{ route('documents.download', $doc) }}">Télécharger</a>
              <form action="{{ route('documents.toggle-status', $doc) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit">{{ $doc->status === 'prive' ? 'Rendre partagé' : 'Rendre privé' }}</button>
              </form>
              <div class="row-menu-divider"></div>
              <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                @csrf @method('DELETE')
                <button type="submit" class="danger">Supprimer</button>
              </form>
            </div>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="table-card" style="text-align:center; padding:56px; color:#9CA3AF;">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 16px;">
    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
  </svg>
  <p style="font-size:15px; font-weight:600; color:#6B7280; margin-bottom:6px;">Aucun fichier pour l'instant</p>
  <p style="font-size:13px;">Importez votre premier fichier avec le bouton ci-dessus.</p>
</div>
@endif

{{-- MODAL Upload --}}
<div class="modal-overlay" id="uploadModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title">Importer un fichier</span>
      <button class="modal-close" onclick="document.getElementById('uploadModal').classList.remove('open')">✕</button>
    </div>
    <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label class="form-label">Fichier *</label>
        <input type="file" name="file" class="form-control" required>
      </div>
      <div class="form-group">
        <label class="form-label">Dossier</label>
        <select name="folder_id" class="form-control">
          <option value="">— Aucun dossier —</option>
          @foreach($folders as $folder)
            <option value="{{ $folder->id }}">{{ $folder->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Visibilité</label>
        <select name="status" class="form-control">
          <option value="prive">Privé</option>
          <option value="partage">Partagé</option>
        </select>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('uploadModal').classList.remove('open')">Annuler</button>
        <button type="submit" class="btn btn-primary">Importer</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL Nouveau Dossier --}}
<div class="modal-overlay" id="folderModal">
  <div class="modal" style="max-width:380px;">
    <div class="modal-header">
      <span class="modal-title">Nouveau dossier</span>
      <button class="modal-close" onclick="document.getElementById('folderModal').classList.remove('open')">✕</button>
    </div>
    <form action="{{ route('folders.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">Nom du dossier *</label>
        <input type="text" name="name" class="form-control" placeholder="Ex: Projets 2024" required>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('folderModal').classList.remove('open')">Annuler</button>
        <button type="submit" class="btn btn-primary">Créer</button>
      </div>
    </form>
  </div>
</div>

@endsection