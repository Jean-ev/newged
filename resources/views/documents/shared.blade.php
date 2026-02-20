@extends('layouts.app')
@section('title', 'Partagés avec moi')
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Partagés avec moi</h1>
    <p class="page-subtitle">Documents que d'autres utilisateurs ont partagés avec vous</p>
  </div>
</div>

@if($documents->count())
<div class="table-card">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Nom</th>
        <th>Partagé par</th>
        <th>Date</th>
        <th>Taille</th>
        <th>Permission</th>
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
        <td style="color:#6B7280;">{{ $doc->user->name }}</td>
        <td style="color:#6B7280;">{{ $doc->pivot->created_at->format('d M Y') }}</td>
        <td style="color:#6B7280;">{{ $doc->formatted_size }}</td>
        <td>
          @if($doc->pivot->permission === 'modification')
            <span class="badge-blue">Modification</span>
          @else
            <span class="badge-gray">Lecture</span>
          @endif
        </td>
        <td>
          <a href="{{ route('documents.download', $doc) }}" class="btn btn-secondary btn-sm">Télécharger</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="table-card" style="text-align:center; padding:56px; color:#9CA3AF;">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 16px;">
    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
  </svg>
  <p style="font-size:15px; font-weight:600; color:#6B7280; margin-bottom:6px;">Aucun document partagé</p>
  <p style="font-size:13px;">Personne n'a encore partagé de document avec vous.</p>
</div>
@endif

@endsection