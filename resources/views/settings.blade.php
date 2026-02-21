@extends('layouts.app')
@section('title', 'Paramètres')
@section('content')

@php
  $prefs = auth()->user()->preferences ?? [];
  $viewMode     = $prefs['view_mode']      ?? 'grid';
  $notifShare   = $prefs['notif_share']    ?? true;
  $notifActivate= $prefs['notif_activate'] ?? true;
  $notifComment = $prefs['notif_comment']  ?? false;
@endphp

<div class="page-header">
  <div>
    <h1 class="page-title">Paramètres</h1>
    <p class="page-subtitle">Configurez vos préférences</p>
  </div>
</div>

@if(session('status') === 'preferences-updated')
  <div class="alert alert-success">✅ Préférences enregistrées avec succès !</div>
@endif

<form method="POST" action="{{ route('settings.update') }}">
  @csrf

  <div style="max-width:700px;">

    {{-- Notifications --}}
    <div class="card" style="margin-bottom:16px;">
      <div class="card-title">🔔 Notifications</div>
      <div class="card-sub">Gérer vos préférences de notifications</div>

      <div style="display:flex; flex-direction:column; gap:14px;">

        {{-- Notif share --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #F3F4F6;">
          <span style="font-size:13px; color:#374151;">Email lors d'un partage de document</span>
          <label style="position:relative; display:inline-block; width:40px; height:22px; cursor:pointer;">
            <input type="checkbox" name="notif_share" value="1" {{ $notifShare ? 'checked' : '' }}
                   style="opacity:0; width:0; height:0;"
                   onchange="updateToggle(this)">
            <span class="toggle-track" style="position:absolute; inset:0; background:{{ $notifShare ? '#2563EB' : '#D1D5DB' }}; border-radius:99px; transition:.2s;">
              <span class="toggle-thumb" style="position:absolute; left:{{ $notifShare ? '20px' : '2px' }}; top:2px; width:18px; height:18px; background:#fff; border-radius:50%; transition:.2s;"></span>
            </span>
          </label>
        </div>

        {{-- Notif activate --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #F3F4F6;">
          <span style="font-size:13px; color:#374151;">Email lors de l'activation de mon compte</span>
          <label style="position:relative; display:inline-block; width:40px; height:22px; cursor:pointer;">
            <input type="checkbox" name="notif_activate" value="1" {{ $notifActivate ? 'checked' : '' }}
                   style="opacity:0; width:0; height:0;"
                   onchange="updateToggle(this)">
            <span class="toggle-track" style="position:absolute; inset:0; background:{{ $notifActivate ? '#2563EB' : '#D1D5DB' }}; border-radius:99px; transition:.2s;">
              <span class="toggle-thumb" style="position:absolute; left:{{ $notifActivate ? '20px' : '2px' }}; top:2px; width:18px; height:18px; background:#fff; border-radius:50%; transition:.2s;"></span>
            </span>
          </label>
        </div>

        {{-- Notif comment --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0;">
          <span style="font-size:13px; color:#374151;">Email lors d'un commentaire</span>
          <label style="position:relative; display:inline-block; width:40px; height:22px; cursor:pointer;">
            <input type="checkbox" name="notif_comment" value="1" {{ $notifComment ? 'checked' : '' }}
                   style="opacity:0; width:0; height:0;"
                   onchange="updateToggle(this)">
            <span class="toggle-track" style="position:absolute; inset:0; background:{{ $notifComment ? '#2563EB' : '#D1D5DB' }}; border-radius:99px; transition:.2s;">
              <span class="toggle-thumb" style="position:absolute; left:{{ $notifComment ? '20px' : '2px' }}; top:2px; width:18px; height:18px; background:#fff; border-radius:50%; transition:.2s;"></span>
            </span>
          </label>
        </div>

      </div>
    </div>

    {{-- Apparence --}}
    <div class="card" style="margin-bottom:16px;">
      <div class="card-title">🎨 Apparence</div>
      <div class="card-sub">Personnalisez l'interface</div>

      <div class="form-group">
        <label class="form-label">Affichage des fichiers</label>
        <input type="hidden" name="view_mode" id="viewModeInput" value="{{ $viewMode }}">
        <div style="display:flex; gap:10px;">
          <button type="button" id="btnGrid" onclick="setViewMode('grid')"
                  class="btn btn-sm {{ $viewMode === 'grid' ? 'btn-primary' : 'btn-secondary' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Grille
          </button>
          <button type="button" id="btnList" onclick="setViewMode('list')"
                  class="btn btn-sm {{ $viewMode === 'list' ? 'btn-primary' : 'btn-secondary' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Liste
          </button>
        </div>
      </div>
    </div>

    {{-- Sécurité --}}
    <div class="card" style="margin-bottom:16px;">
      <div class="card-title">🔒 Sécurité</div>
      <div class="card-sub">Informations de sécurité de votre compte</div>

      <div style="display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F9FAFB; border-radius:8px;">
          <div>
            <div style="font-size:13px; font-weight:500; color:#111827;">Mot de passe</div>
            <div style="font-size:11px; color:#6B7280;">Dernière modification inconnue</div>
          </div>
          <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm">Modifier</a>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F9FAFB; border-radius:8px;">
          <div>
            <div style="font-size:13px; font-weight:500; color:#111827;">Rôle actuel</div>
            <div style="font-size:11px; color:#6B7280;">{{ ucfirst(auth()->user()->role) }}</div>
          </div>
          <span class="badge-blue">{{ ucfirst(auth()->user()->role) }}</span>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F9FAFB; border-radius:8px;">
          <div>
            <div style="font-size:13px; font-weight:500; color:#111827;">Statut du compte</div>
            <div style="font-size:11px; color:#6B7280;">État actuel de votre compte</div>
          </div>
          @if(auth()->user()->status === 'actif')
            <span class="badge-green">Actif</span>
          @elseif(auth()->user()->status === 'en_attente')
            <span class="badge-yellow">En attente</span>
          @else
            <span class="badge-red">Bloqué</span>
          @endif
        </div>
      </div>
    </div>

    {{-- Stockage --}}
    <div class="card" style="margin-bottom:16px;">
      <div class="card-title">💾 Stockage</div>
      <div class="card-sub">Utilisation de votre espace de stockage</div>

      <div style="margin-bottom:12px;">
        <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
          <span style="color:#374151;">Utilisé</span>
          <span style="font-weight:600; color:#111827;">{{ auth()->user()->storage_used_formatted }} / {{ auth()->user()->storage_quota_formatted }}</span>
        </div>
        <div style="height:8px; background:#E5E7EB; border-radius:99px; overflow:hidden;">
          <div style="height:100%; width:{{ auth()->user()->storage_percent }}%; background:{{ auth()->user()->storage_percent >= 90 ? '#EF4444' : '#2563EB' }}; border-radius:99px;"></div>
        </div>
        <div style="font-size:11px; color:#6B7280; margin-top:5px;">{{ auth()->user()->storage_percent }}% utilisé</div>
      </div>

      <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:16px;">
        <div style="text-align:center; padding:12px; background:#FEE2E2; border-radius:8px;">
          <div style="font-size:18px; font-weight:700; color:#DC2626;">{{ auth()->user()->documents()->where('type','pdf')->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">PDF</div>
        </div>
        <div style="text-align:center; padding:12px; background:#F3E8FF; border-radius:8px;">
          <div style="font-size:18px; font-weight:700; color:#7C3AED;">{{ auth()->user()->documents()->where('type','image')->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">Images</div>
        </div>
        <div style="text-align:center; padding:12px; background:#D1FAE5; border-radius:8px;">
          <div style="font-size:18px; font-weight:700; color:#059669;">{{ auth()->user()->documents()->where('type','excel')->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">Excel</div>
        </div>
      </div>
    </div>

    {{-- Bouton Enregistrer --}}
    <div style="display:flex; justify-content:flex-end;">
      <button type="submit" class="btn btn-primary">💾 Enregistrer les préférences</button>
    </div>

  </div>
</form>

@push('scripts')
<script>
  function updateToggle(checkbox) {
    const track = checkbox.nextElementSibling;
    const thumb = track.querySelector('.toggle-thumb');
    if (checkbox.checked) {
      track.style.background = '#2563EB';
      thumb.style.left = '20px';
    } else {
      track.style.background = '#D1D5DB';
      thumb.style.left = '2px';
    }
  }

  function setViewMode(mode) {
    document.getElementById('viewModeInput').value = mode;
    const btnGrid = document.getElementById('btnGrid');
    const btnList = document.getElementById('btnList');
    if (mode === 'grid') {
      btnGrid.className = 'btn btn-primary btn-sm';
      btnList.className = 'btn btn-secondary btn-sm';
    } else {
      btnGrid.className = 'btn btn-secondary btn-sm';
      btnList.className = 'btn btn-primary btn-sm';
    }
  }
</script>
@endpush

@endsection