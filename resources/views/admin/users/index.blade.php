@extends('layouts.app')
@section('title', 'Gestion des utilisateurs')
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Gestion des utilisateurs</h1>
    <p class="page-subtitle">{{ $users->total() }} utilisateurs enregistrés</p>
  </div>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Créer un utilisateur
  </a>
</div>

<div class="table-card">
  <table class="file-table">
    <thead>
      <tr>
        <th style="padding-left:22px;">Utilisateur</th>
        <th>Rôle</th>
        <th>Statut</th>
        <th>Quota</th>
        <th>Inscrit le</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <td style="padding-left:22px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:34px; height:34px; background:#2563EB; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:13px; flex-shrink:0;">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
              <div style="font-weight:500; color:#111827; font-size:13px;">{{ $user->name }}</div>
              <div style="font-size:11.5px; color:#6B7280;">{{ $user->email }}</div>
            </div>
          </div>
        </td>
        <td>
          <form action="{{ route('admin.users.role', $user) }}" method="POST">
            @csrf @method('PATCH')
            <select name="role" class="form-control" style="width:auto; padding:4px 8px; font-size:12px;" onchange="this.form.submit()">
              @foreach(['admin','manager','collaborateur','lecteur'] as $role)
                <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>
          @if($user->status === 'actif')
            <span class="badge-green">Actif</span>
          @elseif($user->status === 'en_attente')
            <span class="badge-yellow">En attente</span>
          @else
            <span class="badge-red">Bloqué</span>
          @endif
        </td>
        <td>
          <div style="font-size:12px; color:#6B7280;">
            {{ $user->storage_used_formatted }} / {{ $user->storage_quota_formatted }}
          </div>
          <div style="height:4px; background:#E5E7EB; border-radius:99px; margin-top:4px; width:80px; overflow:hidden;">
            <div style="height:100%; width:{{ $user->storage_percent }}%; background:{{ $user->storage_percent >= 90 ? '#EF4444' : ($user->storage_percent >= 70 ? '#F59E0B' : '#2563EB') }}; border-radius:99px;"></div>
          </div>
        </td>
        <td style="color:#6B7280; font-size:12px;">{{ $user->created_at->format('d M Y') }}</td>
        <td>
          <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <button onclick="openQuotaModal({{ $user->id }}, '{{ $user->name }}', {{ $user->storage_quota }})" class="btn btn-secondary btn-sm">
              💾 Quota
            </button>
            @if($user->status === 'en_attente')
            <form action="{{ route('admin.users.activate', $user) }}" method="POST">
              @csrf @method('PATCH')
              <button class="btn btn-secondary btn-sm">✓ Activer</button>
            </form>
            @endif
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
              @csrf @method('PATCH')
              <button class="btn btn-sm {{ $user->status === 'bloque' ? 'btn-secondary' : 'btn-danger' }}">
                {{ $user->status === 'bloque' ? 'Débloquer' : 'Bloquer' }}
              </button>
            </form>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm">Supprimer</button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div style="margin-top:16px;">{{ $users->links() }}</div>

{{-- MODAL Quota --}}
<div class="modal-overlay" id="quotaModal">
  <div class="modal" style="max-width:380px;">
    <div class="modal-header">
      <span class="modal-title">Modifier le quota — <span id="quotaUserName"></span></span>
      <button class="modal-close" onclick="document.getElementById('quotaModal').classList.remove('open')">✕</button>
    </div>
    <form id="quotaForm" method="POST">
      @csrf @method('PATCH')
      <div class="form-group">
        <label class="form-label">Quota de stockage</label>
        <select name="storage_quota" class="form-control">
          <option value="524288000">500 MB</option>
          <option value="1073741824">1 GB</option>
          <option value="2147483648">2 GB</option>
          <option value="5368709120">5 GB</option>
          <option value="10737418240">10 GB</option>
        </select>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('quotaModal').classList.remove('open')">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function openQuotaModal(userId, userName, currentQuota) {
  document.getElementById('quotaUserName').textContent = userName;
  document.getElementById('quotaForm').action = '/admin/users/' + userId + '/quota';
  const select = document.querySelector('[name="storage_quota"]');
  select.value = currentQuota;
  document.getElementById('quotaModal').classList.add('open');
}
</script>
@endpush

@endsection