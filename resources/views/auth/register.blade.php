<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription — DocuFlow</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #E0E7FF 100%);
  min-height: 100vh;
  display: flex; align-items: center; justify-content: center;
  padding: 20px;
}

.auth-card {
  background: #fff;
  border-radius: 20px;
  padding: 40px;
  width: 100%; max-width: 440px;
  box-shadow: 0 20px 60px rgba(37,99,235,.12);
}

.logo {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 28px;
}

.logo-icon {
  width: 36px; height: 36px;
  background: #2563EB; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
}

.logo-text { font-size: 18px; font-weight: 700; color: #111827; }

.form-title { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.form-sub { font-size: 13px; color: #6B7280; margin-bottom: 24px; }

.info-box {
  padding: 11px 14px;
  background: #EFF6FF; border: 1px solid #BFDBFE;
  border-radius: 8px; font-size: 12.5px;
  color: #1D4ED8; margin-bottom: 20px; line-height: 1.5;
}

.form-group { margin-bottom: 15px; }

.form-label {
  display: block; font-size: 13px;
  font-weight: 500; color: #374151; margin-bottom: 5px;
}

.form-control {
  width: 100%; padding: 9px 12px;
  border: 1.5px solid #D1D5DB; border-radius: 8px;
  font-family: inherit; font-size: 13.5px;
  color: #111827; outline: none; transition: border .15s;
  background: #F9FAFB;
}

.form-control:focus { border-color: #2563EB; background: #fff; }
.form-error { font-size: 12px; color: #EF4444; margin-top: 4px; }

.btn-submit {
  width: 100%; padding: 11px;
  background: #2563EB; color: #fff;
  border: none; border-radius: 8px;
  font-family: inherit; font-size: 14px;
  font-weight: 600; cursor: pointer;
  transition: background .15s; margin-top: 8px;
}

.btn-submit:hover { background: #1D4ED8; }

.auth-link {
  text-align: center; margin-top: 18px;
  font-size: 13.5px; color: #6B7280;
}

.auth-link a { color: #2563EB; font-weight: 500; text-decoration: none; }

.alert-error {
  padding: 10px 14px;
  background: #FEE2E2; color: #991B1B;
  border: 1px solid #FECACA;
  border-radius: 8px; font-size: 13px; margin-bottom: 18px;
}
</style>
</head>
<body>

<div class="auth-card">
  <div class="logo">
    <div class="logo-icon">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
        <polyline points="13 2 13 9 20 9"/>
      </svg>
    </div>
    <span class="logo-text">DocuFlow</span>
  </div>

  <h3 class="form-title">Créer un compte</h3>
  <p class="form-sub">Rejoignez votre espace de gestion documentaire</p>

  <div class="info-box">
    ℹ️ Votre compte sera en attente de validation par un administrateur avant activation.
  </div>

  @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
      <label class="form-label">Nom complet *</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Jean Dupont" required autofocus>
      @error('name')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">Adresse email *</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="vous@entreprise.com" required>
      @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">Mot de passe *</label>
      <input type="password" name="password" class="form-control" placeholder="Minimum 8 caractères" required>
      @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
      <label class="form-label">Confirmer le mot de passe *</label>
      <input type="password" name="password_confirmation" class="form-control" placeholder="Répétez votre mot de passe" required>
    </div>
    <button type="submit" class="btn-submit">Créer mon compte</button>
  </form>

  <div class="auth-link">
    Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
  </div>
</div>

</body>
</html>