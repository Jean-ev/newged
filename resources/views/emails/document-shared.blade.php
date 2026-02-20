<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
body { font-family: 'Inter', Arial, sans-serif; background: #F1F5F9; margin: 0; padding: 20px; }
.container { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
.header { background: #2563EB; padding: 28px 32px; text-align: center; }
.header h1 { color: #fff; font-size: 20px; margin: 0; }
.header p { color: rgba(255,255,255,.8); font-size: 13px; margin: 6px 0 0; }
.body { padding: 32px; }
.doc-card { background: #F8FAFC; border: 1px solid #E5E7EB; border-radius: 10px; padding: 16px; margin: 20px 0; display: flex; align-items: center; gap: 14px; }
.doc-icon { width: 42px; height: 42px; background: #FEE2E2; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.doc-name { font-weight: 600; font-size: 14px; color: #111827; }
.doc-meta { font-size: 12px; color: #6B7280; margin-top: 3px; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
.badge-lecture { background: #F3F4F6; color: #374151; }
.badge-modification { background: #DBEAFE; color: #1D4ED8; }
.btn { display: block; text-align: center; background: #2563EB; color: #fff; padding: 13px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; margin: 24px 0 0; }
.footer { padding: 20px 32px; border-top: 1px solid #E5E7EB; font-size: 12px; color: #9CA3AF; text-align: center; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>📄 DocuFlow</h1>
    <p>Gestion électronique de documents</p>
  </div>

  <div class="body">
    <p style="font-size:15px; color:#111827;">Bonjour,</p>
    <p style="font-size:14px; color:#374151; line-height:1.6;">
      <strong>{{ $sharedBy->name }}</strong> a partagé un document avec vous sur DocuFlow.
    </p>

    <div class="doc-card">
      <div class="doc-icon">📄</div>
      <div>
        <div class="doc-name">{{ $document->original_name }}</div>
        <div class="doc-meta">
          {{ $document->formatted_size }} •
          <span class="badge {{ $permission === 'modification' ? 'badge-modification' : 'badge-lecture' }}">
            {{ ucfirst($permission) }}
          </span>
        </div>
      </div>
    </div>

    <p style="font-size:13px; color:#6B7280; line-height:1.6;">
      @if($permission === 'modification')
        Vous pouvez <strong>consulter et modifier</strong> ce document.
      @else
        Vous pouvez <strong>consulter</strong> ce document.
      @endif
    </p>

    <a href="{{ url('/documents/shared') }}" class="btn">
      Accéder au document →
    </a>
  </div>

  <div class="footer">
    Vous recevez cet email car un document a été partagé avec votre compte DocuFlow.<br>
    © {{ date('Y') }} DocuFlow — Tous droits réservés
  </div>
</div>
</body>
</html>
