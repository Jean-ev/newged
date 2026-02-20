<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — DocuFlow</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #E0E7FF 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.auth-wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  max-width: 880px;
  width: 100%;
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(37,99,235,.12);
}

.auth-brand {
  background: linear-gradient(145deg, #2563EB, #1D4ED8);
  padding: 48px 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  color: #fff;
}

.brand-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 48px;
}

.brand-logo-icon {
  width: 40px; height: 40px;
  background: rgba(255,255,255,.2);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}

.brand-logo-text { font-size: 22px; font-weight: 700; }

.brand-title {
  font-size: 26px;
  font-weight: 700;
  line-height: 1.3;
  margin-bottom: 14px;
}

.brand-desc { font-size: 14px; opacity: .8; line-height: 1.6; }

.brand-features { list-style: none; margin-top: 36px; }

.brand-features li {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13.5px;
  margin-bottom: 12px;
  opacity: .9;
}

.brand-features li span {
  width: 22px; height: 22px;
  background: rgba(255,255,255,.2);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px;
  flex-shrink: 0;
}

.auth-form { padding: 48px 40px; }

.auth-form-title { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.auth-form-sub { font-size: 13px; color: #6B7280; margin-bottom: 28px; }

.form-group { margin-bottom: 16px; }

.form-label {
  display: block;
  font-size: 13px; font-weight: 500;
  color: #374151; margin-bottom: 5px;
}

.form-control {
  width: 100%; padding: 9px 12px;
  border: 1.5px solid #D1D5DB;
  border-radius: 8px;
  font-family: inherit; font-size: 13.5px;
  color: #111827; outline: none;
  transition: border .15s;
  background: #F9FAFB;
}

.form-control:focus { border-color: #2563EB; background: #fff; }
.form-error { font-size: 12px; color: #EF4444; margin-top: 4px; }

.remember-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.remember-row label {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; color: #374151; cursor: pointer;
}

.forgot-link { font-size: 13px; color: #2563EB; text-decoration: none; }
.forgot-link:hover { text-decoration: underline; }

.btn-submit {
  width: 100%; padding: 11px;
  background: #2563EB; color: #fff;
  border: none; border-radius: 8px;
  font-family: inherit; font-size: 14px;
  font-weight: 600; cursor: pointer;
  transition: background .15s;
}

.btn-submit:hover { background: #1D4ED8; }

.auth-link {
  text-align: center;
  margin-top: 20px;
  font-size: 13.5px;
  color: #6B7280;
}

.auth-link a { color: #2563EB; font-weight: 500; text-decoration: none; }

.alert-error {
  padding: 10px 14px;
  background: #FEE2E2; color: #991B1B;
  border: 1px solid #FECACA;
  border-radius: 8px;
  font-size: 13px;
  margin-bottom: 18px;
}

.demo-box {
  margin-top: 24px;
  padding: 14px;
  background: #F9FAFB;
  border-radius: 8px;
  font-size: 12px;
  color: #6B7280;
  line-height: 1.7;
}

@media (max-width: 640px) {
  .auth-wrapper { grid-template-columns: 1fr; }
  .auth-brand { display: none; }
}
</style>
</head>
<body>

<div class="auth-wrapper">

  {{-- Partie gauche --}}
  <div class="auth-brand">
    <div class="brand-logo">
      <div class="brand-logo-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
          <polyline points="13 2 13 9 20 9"/>
        </svg>
      </div>
      <span class="brand-logo-text">DocuFlow</span>
    </div>
    <h2 class="brand-title">Gérez vos documents en toute sécurité</h2>
    <p class="brand-desc">Une plateforme centralisée pour stocker, partager et contrôler l'accès à vos documents d'entreprise.</p>
    <ul class="brand-features">
      <li><span>✓</span> Upload sécurisé de fichiers</li>
      <li><span>✓</span> Partage contrôlé par l'administrateur</li>
      <li><span>✓</span> Journal d'activité complet</li>
      <li><span>✓</span> Gestion des rôles & permissions</li>
    </ul>
  </div>

  {{-- Partie droite --}}
  <div class="auth-form">
    <h3 class="auth-form-title">Bon retour 👋</h3>
    <p class="auth-form-sub">Connectez-vous à votre espace DocuFlow</p>

    @if($errors->any())
      <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    @if(session('status'))
      <div class="alert-error" style="background:#DCFCE7; color:#166534; border-color:#BBF7D0;">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Adresse email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="vous@entreprise.com" required autofocus>
      </div>
      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <div class="remember-row">
        <label>
          <input type="checkbox" name="remember">
          Se souvenir de moi
        </label>
        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
        @endif
      </div>
      <button type="submit" class="btn-submit">Se connecter</button>
    </form>

    <div class="auth-link">
      Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a>
    </div>

    <div class="demo-box">
      <strong>Comptes de démo :</strong><br>
      Admin : admin@ged.com / password<br>
      Manager : sarah@ged.com / password<br>
      Collaborateur : mohamed@ged.com / password
    </div>
  </div>

</div>

</body>
</html>