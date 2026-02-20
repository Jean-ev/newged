@extends('layouts.app')
@section('title', "Journal d'activité")
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Journal d'activité</h1>
    <p class="page-subtitle">Toutes les actions sensibles de la plateforme</p>
  </div>
  <a href="{{ route('admin.logs.export') }}" class="btn btn-secondary">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    Exporter CSV
  </a>
</div>

<div class="table-card">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Date & heure</th>
        <th>Utilisateur</th>
        <th>Action</th>
        <th>Description</th>
        <th>IP</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $log)
      <tr>
        <td style="padding-left:22px; color:#6B7280; font-size:12px; white-space:nowrap;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
        <td style="font-weight:500;">{{ $log->user?->name ?? 'Système' }}</td>
        <td>
          @php
            $color = match(true) {
              str_contains($log->action, 'suppression') || str_contains($log->action, 'blocage') => 'badge-red',
              str_contains($log->action, 'upload') || str_contains($log->action, 'création') => 'badge-green',
              str_contains($log->action, 'modification') => 'badge-yellow',
              str_contains($log->action, 'partage') => 'badge-blue',
              default => 'badge-gray'
            };
          @endphp
          <span class="{{ $color }}">{{ $log->action }}</span>
        </td>
        <td style="color:#6B7280; font-size:12px;">{{ $log->description ?? '—' }}</td>
        <td style="color:#9CA3AF; font-size:12px;">{{ $log->ip_address ?? '—' }}</td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align:center; padding:32px; color:#9CA3AF;">Aucune activité enregistrée.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:16px;">{{ $logs->links() }}</div>

@endsection