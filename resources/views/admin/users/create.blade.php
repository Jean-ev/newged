@extends('layouts.app')
@section('title', 'Créer un utilisateur')
@section('content')

<div class="page-header">
  <div>
    <h1 class="page-title">Créer un utilisateur</h1>
    <p class="page-subtitle">Ajouter un nouveau compte à la plateforme</p>
  </div>
  <a href="{{ route('admin.users') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card" style="max-width:480px;">
  @if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
  @endif

  <form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label class="form-label">Nom complet *</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
      @error('name')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">Email *</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
      @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">Mot de passe *</label>
      <input type="password" name="password" class="form-control" placeholder="Minimum 8 caractères" required>
      @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">Rôle *</label>
      <select name="role" class="form-control" required>
        <option value="collaborateur">Collaborateur</option>
        <option value="lecteur">Lecteur</option>
        <option value="manager">Manager</option>
        <option value="admin">Administrateur</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Statut *</label>
      <select name="status" class="form-control" required>
        <option value="actif">Actif</option>
        <option value="en_attente">En attente</option>
        <option value="bloque">Bloqué</option>
      </select>
    </div>
    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
      <a href="{{ route('admin.users') }}" class="btn btn-secondary">Annuler</a>
      <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
    </div>
  </form>
</div>

@endsection