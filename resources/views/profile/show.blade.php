@extends('layouts.app')
@section('title', 'Mon Profil')
@section('content')

{{-- Cropper.js --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<div class="page-header">
  <div>
    <h1 class="page-title">Mon Profil</h1>
    <p class="page-subtitle">Vos informations personnelles et statistiques</p>
  </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 2fr; gap:20px;">

  {{-- Carte profil gauche --}}
  <div>
    <div class="card" style="text-align:center; padding:28px;">

      {{-- Avatar --}}
      <div style="position:relative; width:80px; margin:0 auto 16px;">
        @if(auth()->user()->avatar)
          <img src="{{ Storage::url(auth()->user()->avatar) }}"
               alt="Avatar"
               style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid #E5E7EB;">
        @else
          <div style="width:80px; height:80px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:32px;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
        @endif

        {{-- Bouton crayon --}}
        <label for="avatarInput"
               style="position:absolute; bottom:0; right:0; width:26px; height:26px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; border:2px solid #fff;"
               title="Changer la photo">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.125 1.125-4.5L16.862 3.487z"/>
          </svg>
        </label>
      </div>

      {{-- Input file caché --}}
      <input type="file" id="avatarInput" accept="image/*" style="display:none;">

      {{-- Formulaire upload (envoi après recadrage) --}}
      <form id="avatarForm" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="avatar_cropped" id="avatarCropped">
      </form>

      @if(session('status') === 'avatar-updated')
        <div style="font-size:12px; color:#10B981; margin-bottom:8px;">✅ Photo mise à jour !</div>
      @endif

      <div style="font-size:18px; font-weight:700; color:#111827;">{{ auth()->user()->name }}</div>
      <div style="font-size:13px; color:#6B7280; margin-top:4px;">{{ auth()->user()->email }}</div>

      <div style="margin-top:14px;">
        @if(auth()->user()->status === 'actif')
          <span class="badge-green">✓ Compte actif</span>
        @elseif(auth()->user()->status === 'en_attente')
          <span class="badge-yellow">⏳ En attente</span>
        @else
          <span class="badge-red">✗ Bloqué</span>
        @endif
      </div>

      <div style="margin-top:10px;">
        @if(auth()->user()->role === 'admin')
          <span class="badge-blue">Administrateur</span>
        @elseif(auth()->user()->role === 'manager')
          <span class="badge-blue">Manager</span>
        @elseif(auth()->user()->role === 'collaborateur')
          <span class="badge-gray">Collaborateur</span>
        @else
          <span class="badge-gray">Lecteur</span>
        @endif
      </div>

      <div style="margin-top:20px; padding-top:20px; border-top:1px solid #E5E7EB;">
        <div style="font-size:12px; color:#6B7280; margin-bottom:8px;">Stockage utilisé</div>
        <div style="font-size:15px; font-weight:700; color:#111827;">
          {{ auth()->user()->storage_used_formatted }}
          <span style="font-size:12px; font-weight:400; color:#6B7280;">/ {{ auth()->user()->storage_quota_formatted }}</span>
        </div>
        <div style="height:6px; background:#E5E7EB; border-radius:99px; margin-top:8px; overflow:hidden;">
          <div style="height:100%; width:{{ auth()->user()->storage_percent }}%; background:{{ auth()->user()->storage_percent >= 90 ? '#EF4444' : '#2563EB' }}; border-radius:99px;"></div>
        </div>
      </div>

      <div style="margin-top:20px; padding-top:20px; border-top:1px solid #E5E7EB;">
        <div style="font-size:12px; color:#6B7280; margin-bottom:4px;">Membre depuis</div>
        <div style="font-size:13px; font-weight:500; color:#374151;">{{ auth()->user()->created_at->format('d M Y') }}</div>
      </div>
    </div>

    {{-- Statistiques --}}
    <div class="card" style="margin-top:16px;">
      <div class="card-title">Statistiques</div>
      <div class="card-sub">Résumé de votre activité</div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div style="text-align:center; padding:14px; background:#F9FAFB; border-radius:8px;">
          <div style="font-size:22px; font-weight:700; color:#2563EB;">{{ auth()->user()->documents()->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">Documents</div>
        </div>
        <div style="text-align:center; padding:14px; background:#F9FAFB; border-radius:8px;">
          <div style="font-size:22px; font-weight:700; color:#10B981;">{{ auth()->user()->folders()->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">Dossiers</div>
        </div>
        <div style="text-align:center; padding:14px; background:#F9FAFB; border-radius:8px;">
          <div style="font-size:22px; font-weight:700; color:#F59E0B;">{{ auth()->user()->documents()->where('is_favorite', true)->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">Favoris</div>
        </div>
        <div style="text-align:center; padding:14px; background:#F9FAFB; border-radius:8px;">
          <div style="font-size:22px; font-weight:700; color:#8B5CF6;">{{ auth()->user()->sharedDocuments()->count() }}</div>
          <div style="font-size:11px; color:#6B7280; margin-top:3px;">Partagés</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Formulaires droite --}}
  <div>
    {{-- Modifier infos --}}
    <div class="card" style="margin-bottom:16px;">
      <div class="card-title">Informations personnelles</div>
      <div class="card-sub">Modifier votre nom et email</div>

      @if(session('status') === 'profile-updated')
        <div class="alert alert-success">✅ Profil mis à jour avec succès !</div>
      @endif

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PATCH')
        <div class="form-group">
          <label class="form-label">Nom complet</label>
          <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
          @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Adresse email</label>
          <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
          @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex; justify-content:flex-end; margin-top:16px;">
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>

    {{-- Modifier mot de passe --}}
    <div class="card" style="margin-bottom:16px;">
      <div class="card-title">Changer le mot de passe</div>
      <div class="card-sub">Utilisez un mot de passe fort d'au moins 8 caractères</div>

      @if(session('status') === 'password-updated')
        <div class="alert alert-success">✅ Mot de passe mis à jour !</div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf @method('PUT')
        <div class="form-group">
          <label class="form-label">Mot de passe actuel</label>
          <input type="password" name="current_password" class="form-control" required>
          @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Nouveau mot de passe</label>
          <input type="password" name="password" class="form-control" required>
          @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Confirmer le mot de passe</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <div style="display:flex; justify-content:flex-end; margin-top:16px;">
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>

    {{-- Supprimer compte --}}
    <div class="card" style="border-color:#FECACA;">
      <div class="card-title" style="color:#991B1B;">Zone dangereuse</div>
      <div class="card-sub">La suppression de votre compte est irréversible</div>
      <button onclick="document.getElementById('deleteModal').classList.add('open')" class="btn btn-danger btn-sm">
        Supprimer mon compte
      </button>
    </div>
  </div>

</div>

{{-- MODAL Recadrage avatar --}}
<div class="modal-overlay" id="cropModal">
  <div class="modal" style="max-width:500px;">
    <div class="modal-header">
      <span class="modal-title">✂️ Recadrer la photo</span>
      <button class="modal-close" onclick="closeCropModal()">✕</button>
    </div>
    <div style="max-height:400px; overflow:hidden; border-radius:8px; background:#000;">
      <img id="cropImage" src="" alt="Recadrage" style="max-width:100%; display:block;">
    </div>
    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
      <button type="button" class="btn btn-secondary" onclick="closeCropModal()">Annuler</button>
      <button type="button" class="btn btn-primary" onclick="confirmCrop()">✅ Confirmer</button>
    </div>
  </div>
</div>

{{-- MODAL Suppression compte --}}
<div class="modal-overlay" id="deleteModal">
  <div class="modal" style="max-width:400px;">
    <div class="modal-header">
      <span class="modal-title" style="color:#991B1B;">⚠️ Supprimer le compte</span>
      <button class="modal-close" onclick="document.getElementById('deleteModal').classList.remove('open')">✕</button>
    </div>
    <p style="font-size:13px; color:#6B7280; margin-bottom:16px;">Cette action est irréversible. Tous vos documents et dossiers seront supprimés.</p>
    <form method="POST" action="{{ route('profile.destroy') }}">
      @csrf @method('DELETE')
      <div class="form-group">
        <label class="form-label">Confirmez votre mot de passe</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('deleteModal').classList.remove('open')">Annuler</button>
        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
  let cropper = null;

  document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(ev) {
      const cropImage = document.getElementById('cropImage');
      cropImage.src = ev.target.result;

      document.getElementById('cropModal').classList.add('open');

      if (cropper) { cropper.destroy(); cropper = null; }

      cropper = new Cropper(cropImage, {
        aspectRatio: 1,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 0.8,
        restore: false,
        guides: true,
        center: true,
        highlight: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
      });
    };
    reader.readAsDataURL(file);
  });

  function closeCropModal() {
    document.getElementById('cropModal').classList.remove('open');
    if (cropper) { cropper.destroy(); cropper = null; }
    document.getElementById('avatarInput').value = '';
  }

  function confirmCrop() {
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    const base64 = canvas.toDataURL('image/jpeg', 0.9);
    document.getElementById('avatarCropped').value = base64;
    document.getElementById('avatarForm').submit();
  }
</script>
@endpush

@endsection