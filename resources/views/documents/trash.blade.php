@extends('layouts.app')
@section('title', 'Corbeille')
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Corbeille</h1>
    <p class="page-subtitle">{{ $documents->total() }} document(s) supprimé(s)</p>
  </div>
  @if($documents->count())
  <form action="{{ route('documents.empty-trash') }}" method="POST" onsubmit="return confirm('Vider toute la corbeille ? Cette action est irréversible.')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-danger">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
      Vider la corbeille
    </button>
  </form>
  @endif
</div>

@if($documents->count())
<div class="table-card">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Nom</th>
        <th>Supprimé le</th>
        <th>Taille</th>
        <th>Actions</th>
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
            <span class="fname-label" style="color:#9CA3AF; text-decoration:line-through;">{{ $doc->original_name }}</span>
          </div>
        </td>
        <td style="color:#6B7280; font-size:12px;">{{ $doc->deleted_at->format('d M Y, H:i') }}</td>
        <td style="color:#6B7280; font-size:12px;">{{ $doc->formatted_size }}</td>
        <td>
          <div style="display:flex; gap:8px;">
            <form action="{{ route('documents.restore', $doc->id) }}" method="POST">
              @csrf @method('PATCH')
              <button class="btn btn-secondary btn-sm">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                Restaurer
              </button>
            </form>
            <form action="{{ route('documents.force-delete', $doc->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm">Supprimer</button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div style="margin-top:16px;">{{ $documents->links() }}</div>
@else
<div class="table-card" style="text-align:center; padding:56px;">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 16px;">
    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
  </svg>
  <p style="font-size:15px; font-weight:600; color:#6B7280; margin-bottom:6px;">Corbeille vide</p>
  <p style="font-size:13px; color:#9CA3AF;">Les documents supprimés apparaîtront ici.</p>
</div>
@endif

@endsection