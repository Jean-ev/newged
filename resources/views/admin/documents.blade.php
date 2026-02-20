@extends('layouts.app')
@section('title', 'Tous les documents')
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Tous les documents</h1>
    <p class="page-subtitle">{{ $documents->total() }} documents sur la plateforme</p>
  </div>
</div>

<div class="table-card">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Nom</th>
        <th>Propriétaire</th>
        <th>Modifié le</th>
        <th>Taille</th>
        <th>Type</th>
        <th>Statut</th>
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
        <td>
          <div style="display:flex; align-items:center; gap:8px;">
            <div style="width:26px; height:26px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:11px; flex-shrink:0;">
              {{ strtoupper(substr($doc->user->name, 0, 1)) }}
            </div>
            <span style="font-size:13px; color:#374151;">{{ $doc->user->name }}</span>
          </div>
        </td>
        <td style="color:#6B7280; font-size:12px;">{{ $doc->updated_at->format('d M Y') }}</td>
        <td style="color:#6B7280; font-size:12px;">{{ $doc->formatted_size }}</td>
        <td style="color:#6B7280; font-size:12px; text-transform:uppercase;">{{ $doc->type }}</td>
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
                <a href="#" onclick="openShareModal({{ $doc->id }}, '{{ $doc->original_name }}'); return false;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Partager
                </a>
                <a href="{{ route('documents.download', $doc) }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Télécharger
              </a>
              <form action="{{ route('documents.toggle-status', $doc) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit">
                  {{ $doc->status === 'prive' ? 'Rendre partagé' : 'Rendre privé' }}
                </button>
              </form>
              <div class="row-menu-divider"></div>
              <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Supprimer ce document ?')">
                @csrf @method('DELETE')
                <button type="submit" class="danger">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                  Supprimer
                </button>
              </form>
            </div>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center; padding:48px; color:#9CA3AF;">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;">
            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/>
          </svg>
          <p style="font-size:14px; font-weight:600; color:#6B7280;">Aucun document sur la plateforme</p>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:16px;">{{ $documents->links() }}</div>

{{-- MODAL Partage --}}
<div class="modal-overlay" id="shareModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title">Partager — <span id="shareDocName"></span></span>
      <button class="modal-close" onclick="document.getElementById('shareModal').classList.remove('open')">✕</button>
    </div>
    <form id="shareForm" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">Utilisateurs *</label>
        <select name="user_ids[]" class="form-control" multiple style="height:120px;">
          @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $u)
            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
          @endforeach
        </select>
        <div style="font-size:11px; color:#6B7280; margin-top:4px;">Maintenez Ctrl pour sélectionner plusieurs</div>
      </div>
      <div class="form-group">
        <label class="form-label">Permission</label>
        <select name="permission" class="form-control">
          <option value="lecture">Lecture seule</option>
          <option value="modification">Modification</option>
        </select>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('shareModal').classList.remove('open')">Annuler</button>
        <button type="submit" class="btn btn-primary">Partager</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function openShareModal(docId, docName) {
  document.getElementById('shareDocName').textContent = docName;
  document.getElementById('shareForm').action = '/admin/documents/' + docId + '/share';
  document.getElementById('shareModal').classList.add('open');
}
</script>
@endpush
@endsection