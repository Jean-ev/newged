<!DOCTYPE html>
<html lang="fr">
    @php use Illuminate\Support\Facades\Storage; @endphp
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'GED') — DocuFlow</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

:root {
  --blue:      #2563EB;
  --blue-dark: #1D4ED8;
  --blue-bg:   #EFF6FF;
  --sidebar-w: 240px;
  --topbar-h:  56px;
  --gray-50:   #F9FAFB;
  --gray-100:  #F3F4F6;
  --gray-200:  #E5E7EB;
  --gray-300:  #D1D5DB;
  --gray-400:  #9CA3AF;
  --gray-500:  #6B7280;
  --gray-600:  #4B5563;
  --gray-700:  #374151;
  --gray-900:  #111827;
  --green:     #10B981;
  --red:       #EF4444;
  --yellow:    #F59E0B;
  --page-bg:   #F1F5F9;
}

body {
  font-family: 'Inter', sans-serif;
  background: var(--page-bg);
  color: var(--gray-900);
  display: flex;
  min-height: 100vh;
  font-size: 14px;
}

/* SIDEBAR */
.sidebar {
  width: var(--sidebar-w);
  background: #fff;
  border-right: 1px solid var(--gray-200);
  position: fixed;
  top: 0; left: 0; bottom: 0;
  display: flex;
  flex-direction: column;
  z-index: 100;
  overflow-y: auto;
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--gray-100);
}

.logo-icon {
  width: 34px; height: 34px;
  background: var(--blue);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
}

.logo-text { font-size: 16px; font-weight: 700; color: var(--gray-900); }

.nav-label {
  font-size: 10px; font-weight: 600;
  letter-spacing: .09em; text-transform: uppercase;
  color: var(--gray-400); padding: 16px 20px 5px;
  display: block;
}

.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 12px; margin: 1px 8px; border-radius: 7px;
  color: var(--gray-600); font-size: 13px; font-weight: 500;
  cursor: pointer; text-decoration: none;
  transition: background .12s, color .12s;
}

.nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
.nav-item:hover { background: var(--gray-100); color: var(--blue); }
.nav-item.active { background: var(--blue); color: #fff; font-weight: 600; }

.nav-divider { height: 1px; background: var(--gray-200); margin: 6px 16px; }

.sidebar-storage {
  margin-top: auto; padding: 16px 20px;
  border-top: 1px solid var(--gray-100);
}

.storage-row {
  display: flex; justify-content: space-between;
  font-size: 12.5px; font-weight: 500; color: var(--gray-700); margin-bottom: 7px;
}

.storage-track { height: 5px; background: var(--gray-200); border-radius: 99px; overflow: hidden; }
.storage-fill { height: 100%; background: var(--blue); border-radius: 99px; transition: width .3s; }
.storage-sub { margin-top: 5px; font-size: 11px; color: var(--gray-500); }

/* TOPBAR */
.topbar {
  position: fixed;
  top: 0;
  left: var(--sidebar-w);
  right: 0;
  height: var(--topbar-h);
  background: #fff;
  border-bottom: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  padding: 0 24px;
  gap: 14px;
  z-index: 90;
}

.s-icon {
  position: absolute; left: 10px; top: 50%;
  transform: translateY(-50%); color: var(--gray-400);
  display: flex; pointer-events: none;
}

.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

.tb-btn {
  width: 34px; height: 34px; border: none; background: none;
  border-radius: 7px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: var(--gray-500); transition: background .12s;
}

.tb-btn:hover { background: var(--gray-100); }

.user-menu { position: relative; }

.tb-avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: var(--blue); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 13px; cursor: pointer;
  border: 2px solid var(--gray-200);
}

.dropdown {
  position: absolute; right: 0; top: 42px;
  background: #fff; border: 1px solid var(--gray-200);
  border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08);
  min-width: 180px; display: none; z-index: 200;
}

.dropdown.open { display: block; }

.dropdown-header { padding: 12px 16px; border-bottom: 1px solid var(--gray-100); }
.dropdown-name { font-weight: 600; font-size: 13px; }
.dropdown-email { font-size: 11px; color: var(--gray-500); margin-top: 2px; }

.dropdown a, .dropdown button {
  display: flex; align-items: center; gap: 8px;
  width: 100%; padding: 9px 16px; font-size: 13px;
  color: var(--gray-700); text-decoration: none;
  background: none; border: none; cursor: pointer;
  font-family: inherit; transition: background .1s;
}

.dropdown a:hover, .dropdown button:hover { background: var(--gray-100); }
.dropdown-divider { height: 1px; background: var(--gray-100); margin: 4px 0; }

/* MAIN */
.main {
  margin-left: var(--sidebar-w);
  margin-top: var(--topbar-h);
  flex: 1;
  padding: 28px 32px;
  width: calc(100% - var(--sidebar-w));
  min-height: calc(100vh - var(--topbar-h));
}

/* ALERTS */
.alert {
  padding: 11px 16px; border-radius: 8px;
  font-size: 13px; margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px;
}

.alert-success { background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; }
.alert-error   { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }

/* PAGE HEADER */
.page-header {
  display: flex; justify-content: space-between;
  align-items: flex-start; margin-bottom: 26px;
}

.page-title { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; }
.page-subtitle { font-size: 13px; color: var(--gray-500); margin-top: 3px; }

/* BUTTONS */
.btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 8px; font-family: inherit;
  font-size: 13px; font-weight: 600; cursor: pointer;
  border: none; text-decoration: none; transition: all .12s;
}

.btn-primary { background: var(--blue); color: #fff; }
.btn-primary:hover { background: var(--blue-dark); color: #fff; }
.btn-secondary { background: #fff; color: var(--gray-700); border: 1px solid var(--gray-300); }
.btn-secondary:hover { background: var(--gray-50); }
.btn-danger { background: var(--red); color: #fff; }
.btn-danger:hover { background: #DC2626; }
.btn-sm { padding: 5px 12px; font-size: 12px; }

/* SECTION TITLE */
.section-title { font-size: 15px; font-weight: 700; color: var(--gray-900); margin-bottom: 14px; }

/* FOLDER GRID */
.folder-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 28px; }

.folder-card {
  background: #fff; border: 1px solid var(--gray-200);
  border-radius: 11px; padding: 14px 16px 16px;
  cursor: pointer; transition: border-color .15s, box-shadow .15s;
  text-decoration: none; color: inherit; display: block;
}

.folder-card:hover { border-color: #93C5FD; box-shadow: 0 2px 12px rgba(37,99,235,.06); }

.folder-card-top {
  display: flex; justify-content: space-between;
  align-items: flex-start; margin-bottom: 14px;
}

.folder-menu-btn {
  background: none; border: none; cursor: pointer;
  color: var(--gray-400); padding: 2px 4px; border-radius: 4px;
  display: flex; align-items: center; transition: background .1s;
}

.folder-menu-btn:hover { background: var(--gray-100); }
.folder-name { font-size: 13px; font-weight: 600; color: var(--gray-900); margin-bottom: 3px; }
.folder-meta { font-size: 11.5px; color: var(--gray-500); }

/* FILE TABLE */
.table-card { background: #fff; border: 1px solid var(--gray-200); border-radius: 11px; overflow: hidden; }

.file-table { width: 100%; border-collapse: collapse; }

.file-table thead tr { background: #F8FAFC; border-bottom: 1px solid var(--gray-200); }

.file-table th {
  padding: 10px 18px; text-align: left; font-size: 11px;
  font-weight: 600; color: var(--gray-400);
  text-transform: uppercase; letter-spacing: .07em; white-space: nowrap;
}

.file-table tbody tr { border-bottom: 1px solid var(--gray-100); transition: background .1s; }
.file-table tbody tr:last-child { border-bottom: none; }
.file-table tbody tr:hover { background: #FAFBFC; }
.file-table td { padding: 12px 18px; font-size: 13px; color: var(--gray-700); }

.fname-cell { display: flex; align-items: center; gap: 10px; }

.ftype-icon {
  width: 28px; height: 28px; border-radius: 6px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

.ftype-icon svg { width: 14px; height: 14px; }
.ftype-icon.pdf   { background: #FEE2E2; }
.ftype-icon.image { background: #F3E8FF; }
.ftype-icon.excel { background: #D1FAE5; }
.ftype-icon.word  { background: #DBEAFE; }
.ftype-icon.video { background: #FEF3C7; }
.ftype-icon.autre { background: var(--gray-100); }

.fname-label { font-size: 13px; font-weight: 400; color: var(--gray-900); }

/* Badges */
.badge-shared  { display: inline-flex; padding: 3px 10px; background: var(--blue); color: #fff; border-radius: 999px; font-size: 11.5px; font-weight: 500; }
.badge-private { display: inline-flex; padding: 3px 10px; background: transparent; color: var(--gray-500); border-radius: 999px; font-size: 11.5px; border: 1px solid var(--gray-300); }
.badge-green   { display: inline-flex; padding: 2px 9px; background: #DCFCE7; color: #166534; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-yellow  { display: inline-flex; padding: 2px 9px; background: #FEF9C3; color: #854D0E; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-red     { display: inline-flex; padding: 2px 9px; background: #FEE2E2; color: #991B1B; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-blue    { display: inline-flex; padding: 2px 9px; background: var(--blue-bg); color: var(--blue-dark); border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-gray    { display: inline-flex; padding: 2px 9px; background: var(--gray-100); color: var(--gray-600); border-radius: 999px; font-size: 11px; font-weight: 600; }

/* Row dots */
.row-dots {
  background: none; border: none; cursor: pointer;
  color: var(--gray-400); padding: 4px 5px; border-radius: 5px;
  display: flex; align-items: center; transition: background .1s;
}

.row-dots:hover { background: var(--gray-100); }

/* Row menu */
.row-actions { position: relative; }

.row-menu {
  position: absolute; right: 0; top: 100%;
  background: #fff; border: 1px solid var(--gray-200);
  border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.08);
  min-width: 160px; display: none; z-index: 300;
}

.row-menu.open { display: block; }

.row-menu a, .row-menu button {
  display: flex; align-items: center; gap: 8px;
  width: 100%; padding: 8px 14px; font-size: 12.5px;
  color: var(--gray-700); text-decoration: none;
  background: none; border: none; cursor: pointer;
  font-family: inherit; transition: background .1s;
}

.row-menu a:hover, .row-menu button:hover { background: var(--gray-100); }
.row-menu .danger { color: var(--red); }
.row-menu-divider { height: 1px; background: var(--gray-100); margin: 3px 0; }

/* Modal */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.4);
  z-index: 500; display: none; align-items: center; justify-content: center;
}

.modal-overlay.open { display: flex; }

.modal {
  background: #fff; border-radius: 14px; padding: 26px;
  width: 100%; max-width: 460px;
  box-shadow: 0 20px 60px rgba(0,0,0,.12);
}

.modal-header {
  display: flex; justify-content: space-between;
  align-items: center; margin-bottom: 20px;
}

.modal-title { font-size: 16px; font-weight: 700; }

.modal-close {
  background: none; border: none; cursor: pointer;
  color: var(--gray-400); font-size: 20px; line-height: 1;
  padding: 2px 6px; border-radius: 4px;
}

.modal-close:hover { background: var(--gray-100); color: var(--gray-700); }

/* Forms */
.form-group { margin-bottom: 15px; }
.form-label { display: block; font-size: 12.5px; font-weight: 500; color: var(--gray-700); margin-bottom: 5px; }

.form-control {
  width: 100%; padding: 8px 12px;
  border: 1.5px solid var(--gray-300); border-radius: 7px;
  font-family: inherit; font-size: 13px;
  color: var(--gray-900); outline: none; transition: border .15s;
  background: #fff;
}

.form-control:focus { border-color: var(--blue); }
.form-error { font-size: 11.5px; color: var(--red); margin-top: 4px; }

/* Card */
.card { background: #fff; border: 1px solid var(--gray-200); border-radius: 11px; padding: 18px; }
.card-title { font-size: 13.5px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
.card-sub { font-size: 11.5px; color: var(--gray-400); margin-bottom: 14px; }

/* Admin 3 col */
.three-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

/* Mini table */
.mini-table { width: 100%; border-collapse: collapse; }
.mini-table th { font-size: 10px; font-weight: 600; color: var(--gray-400); text-transform: uppercase; letter-spacing: .07em; padding: 5px 0; border-bottom: 1px solid var(--gray-100); text-align: left; }
.mini-table td { padding: 8px 0; font-size: 12.5px; color: var(--gray-700); border-bottom: 1px solid var(--gray-50); vertical-align: middle; }
.mini-table tr:last-child td { border-bottom: none; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="logo-icon">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
        <polyline points="13 2 13 9 20 9"/>
      </svg>
    </div>
    <span class="logo-text">DocuFlow</span>
  </div>

  <nav>
    @if(auth()->user()->isAdmin())
    <a class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Tableau de bord
    </a>
    @endif

    <a class="nav-item {{ request()->routeIs('documents.index') ? 'active' : '' }}" href="{{ route('documents.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
      Mes Documents
    </a>

    <a class="nav-item {{ request()->routeIs('documents.shared') ? 'active' : '' }}" href="{{ route('documents.shared') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Partagés avec moi
    </a>

    <a class="nav-item {{ request()->routeIs('documents.recent') ? 'active' : '' }}" href="{{ route('documents.recent') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Récents
    </a>

    <a class="nav-item {{ request()->routeIs('documents.favorites') ? 'active' : '' }}" href="{{ route('documents.favorites') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Favoris
    </a>

    @if(auth()->user()->isAdmin())
    <div class="nav-divider"></div>
    <span class="nav-label">Administration</span>

    <a class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Gestion utilisateurs
    </a>

    <a class="nav-item {{ request()->routeIs('admin.documents') ? 'active' : '' }}" href="{{ route('admin.documents') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
      Tous les documents
    </a>

    <a class="nav-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}" href="{{ route('admin.logs') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Journal d'activité
    </a>
    @endif

    <div class="nav-divider"></div>

    <a class="nav-item {{ request()->routeIs('documents.trash') ? 'active' : '' }}" href="{{ route('documents.trash') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
    Corbeille
    </a>

    <a class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
    Paramètres
    </a>
  </nav>

  @php
    $used  = auth()->user()->storage_used;
    $limit = auth()->user()->storage_quota;
    $pct   = auth()->user()->storage_percent;
    $color = $pct >= 90 ? '#EF4444' : ($pct >= 70 ? '#F59E0B' : '#2563EB');
  @endphp

  <div class="sidebar-storage">
    <div class="storage-row">
      <span>Stockage</span>
      <span style="color:{{ $color }};">{{ $pct }}%</span>
    </div>
    <div class="storage-track">
      <div class="storage-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
    </div>
    <div class="storage-sub">
      {{ auth()->user()->storage_used_formatted }} utilisés sur {{ auth()->user()->storage_quota_formatted }}
    </div>
    @if($pct >= 90)
    <div style="margin-top:8px; padding:6px 10px; background:#FEE2E2; border-radius:6px; font-size:11px; color:#991B1B;">
      ⚠️ Quota presque atteint !
    </div>
    @endif
  </div>

</aside>

<!-- TOPBAR -->
<header class="topbar">
  <form action="{{ route('documents.search') }}" method="GET" style="flex:1; max-width:360px; position:relative;">
    <span class="s-icon">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </span>
    <input type="text" name="q" placeholder="Rechercher des fichiers..." value="{{ request('q') }}" style="width:100%; height:34px; padding:0 12px 0 34px; background:#F3F4F6; border:1.5px solid #E5E7EB; border-radius:7px; font-family:inherit; font-size:13px; color:#111827; outline:none;">
  </form>

  <div class="topbar-right">
    <button class="tb-btn">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
    </button>

    <div class="tb-avatar" onclick="toggleDropdown()" style="overflow:hidden; padding:0;">
        @if(auth()->user()->avatar)
            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                alt="avatar"
                style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
        @else
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        @endif
    </div>
      <div class="dropdown" id="userDropdown">
        <div class="dropdown-header">
          <div class="dropdown-name">{{ auth()->user()->name }}</div>
          <div class="dropdown-email">{{ auth()->user()->email }}</div>
        </div>
        <a href="{{ route('profile.show') }}">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Mon profil
        </a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Se déconnecter
          </button>
        </form>
      </div>
    </div>
  </div>
</header>

<!-- MAIN -->
<main class="main">
  @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error">❌ {{ session('error') }}</div>
  @endif

  @yield('content')
</main>

<script>
function toggleDropdown() {
  document.getElementById('userDropdown').classList.toggle('open');
}

document.addEventListener('click', function(e) {
  const menu = document.getElementById('userDropdown');
  if (menu && !e.target.closest('.user-menu')) {
    menu.classList.remove('open');
  }
});

function toggleRowMenu(btn) {
  document.querySelectorAll('.row-menu.open').forEach(m => m.classList.remove('open'));
  
  const menu = btn.nextElementSibling;
  const rect = btn.getBoundingClientRect();
  
  menu.style.position = 'fixed';
  menu.style.top = (rect.bottom + 5) + 'px';
  menu.style.right = (window.innerWidth - rect.right) + 'px';
  menu.style.zIndex = '9999';
  
  menu.classList.toggle('open');
}

document.addEventListener('click', function(e) {
  if (!e.target.closest('.row-actions')) {
    document.querySelectorAll('.row-menu.open').forEach(m => m.classList.remove('open'));
  }
});
</script>

@stack('scripts')
</body>
</html>