@extends('layouts.app')
@section('title', $folder->name)
@section('content')

<div class="page-header">
  <div>
    <div style="font-size:12px; color:#6B7280; margin-bottom:6px;">
      <a href="{{ route('documents.index') }}" style="color:#2563EB; text-decoration:none;">Mes Documents</a>
      <span style="margin:0 6px;">›</span>
      <span>{{ $folder->name }}</span>
    </div>
    <h1 class="page-title">{{ $folder->name }}</h1>
    <p class="page-subtitle">{{ $documents->total() }} fichier{{ $documents->total() > 1 ? 's' : '' }}</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('uploadModal').classList.add('open')">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
      <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
    </svg>
    Importer
  </button>
</div>

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
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
              </svg>
            </button>
            <div class="row-menu">
              <a href="{{ route('documents.download', $doc) }}">Télécharger</a>
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
<div style="margin-top:16px;">{{ $documents->links() }}</div>
@else
<div class="table-card" style="text-align:center; padding:56px; color:#9CA3AF;">
  <p style="font-size:15px; font-weight:600; color:#6B7280; margin-bottom:6px;">Dossier vide</p>
  <p style="font-size:13px;">Importez un fichier dans ce dossier.</p>
</div>
@endif

{{-- MODAL Upload --}}
<div class="modal-overlay" id="uploadModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title">Importer dans "{{ $folder->name }}"</span>
      <button class="modal-close" onclick="document.getElementById('uploadModal').classList.remove('open')">✕</button>
    </div>
    <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="folder_id" value="{{ $folder->id }}">
      <div class="form-group">
        <label class="form-label">Fichier *</label>
        <input type="file" name="file" class="form-control" required>
      </div>
      <div class="form-group">
        <label class="form-label">Visibilité</label>
        <select name="status" class="form-control">
          <option value="prive">🔒 Privé</option>
          <option value="partage">🔓 Partagé</option>
        </select>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('uploadModal').classList.remove('open')">Annuler</button>
        <button type="submit" class="btn btn-primary">Importer</button>
      </div>
    </form>
  </div>
</div>

@endsection