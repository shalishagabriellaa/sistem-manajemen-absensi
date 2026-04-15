{{--
  LOGIN — "GLASS AURORA" FINAL
  Berdasarkan revisi user + color palette sesuai dashboard (biru #3b4cca sidebar)
--}}
@extends('templates.login')
@section('container')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

:root {
    --dash-blue:    #3b4cca;
    --dash-blue-dk: #2d3db4;
    --dash-blue-lt: #5c6ed4;

    --blue-50:   #eef0fb;
    --blue-100:  #d5d9f5;
    --blue-200:  #adb5eb;
    --blue-400:  #6e80df;
    --blue-500:  #4f63d8;
    --blue-600:  #3b4cca;
    --blue-700:  #2d3db4;
    --blue-800:  #1e2d8a;
    --indigo-500:#5b5fce;
    --indigo-600:#4f46e5;

    --slate-50:  #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;
    --slate-400: #94a3b8;
    --slate-500: #64748b;
    --slate-600: #475569;
    --slate-700: #334155;
    --slate-800: #1e293b;
    --slate-900: #0f172a;

    --red-400:   #f87171;
    --red-500:   #ef4444;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    overflow-x: hidden;
}

/* ══════════════════════════════
   ANIMATED BACKGROUND
══════════════════════════════ */
.aurora-bg {
    position: fixed;
    inset: 0;
    overflow: hidden;
    background:
        radial-gradient(circle at 10% 10%, rgba(59,76,202,0.12), transparent 40%),
        radial-gradient(circle at 90% 90%, rgba(59,76,202,0.08), transparent 40%),
        linear-gradient(180deg, #f6f8ff 0%, #eef2ff 100%);
}
.aurora-bg::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 10%,  rgba(59,76,202,0.60) 0%, transparent 60%),
        radial-gradient(ellipse 60% 70% at 85% 80%,  rgba(79,70,229,0.50) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 55% 40%,  rgba(45,61,180,0.22) 0%, transparent 55%);
}
.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.35;
    pointer-events: none;
    will-change: transform;
}
.orb-a {
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(59,76,202,0.25) 0%, transparent 70%);
    top: -200px; left: -180px;
    animation: orb-move-a 20s ease-in-out infinite alternate;
}
.orb-b {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(99,102,241,0.22) 0%, transparent 70%);
    bottom: -180px; right: -160px;
    animation: orb-move-b 25s ease-in-out infinite alternate;
}
.orb-c {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(45,61,180,0.18) 0%, transparent 70%);
    top: 45%; left: 40%;
    transform: translate(-50%,-50%);
    animation: orb-move-c 30s ease-in-out infinite alternate;
}
@keyframes orb-move-a {
    0%   { transform: translate(0,0) scale(1); }
    100% { transform: translate(100px,80px) scale(1.15); }
}
@keyframes orb-move-b {
    0%   { transform: translate(0,0) scale(1); }
    100% { transform: translate(-80px,-100px) scale(1.1); }
}
@keyframes orb-move-c {
    0%   { transform: translate(-50%,-50%) scale(1); }
    100% { transform: translate(-45%,-55%) scale(1.2); }
}
.arc-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.07);
    pointer-events: none;
    animation: ring-pulse 8s ease-in-out infinite alternate;
}
.arc-1 { width:800px; height:800px; top:-300px;  right:-250px; animation-delay:0s; }
.arc-2 { width:600px; height:600px; top:-200px;  right:-150px; animation-delay:1.5s; }
.arc-3 { width:400px; height:400px; top:-100px;  right:-50px;  animation-delay:3s; }
.arc-4 { width:700px; height:700px; bottom:-280px; left:-220px; animation-delay:2s; }
.arc-5 { width:500px; height:500px; bottom:-180px; left:-120px; animation-delay:4s; }
@keyframes ring-pulse {
    0%   { opacity:.5;  transform:scale(1); }
    100% { opacity:1;   transform:scale(1.04); }
}
@keyframes grain-drift {
    0%   { transform:translate(0,0); }
    25%  { transform:translate(-2px,2px); }
    50%  { transform:translate(2px,-2px); }
    75%  { transform:translate(-1px,-1px); }
    100% { transform:translate(1px,1px); }
}

/* ══════════════════════════════
   PAGE LAYOUT
══════════════════════════════ */
.page-wrap {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    padding: 2rem 1.5rem;
}
.card-outer {
    width: 100%; max-width: 900px;
    display: flex; min-height: 600px;
    border-radius: 24px; overflow: hidden;
    background: white;
    border: 1px solid rgba(226,232,240,0.8);
    box-shadow:
        0 24px 64px rgba(0,0,0,0.10),
        0 4px 16px rgba(0,0,0,0.04);
}

/* ══════════════════════════════
   LEFT INFO PANEL
══════════════════════════════ */
.info-panel {
    flex: 0 0 38%;
    display: flex; flex-direction: column;
    justify-content: space-between;
    padding: 2.5rem 2.25rem;
    position: relative; overflow: hidden;
    background: linear-gradient(145deg, #3b4cca 0%, #4f63d8 60%, #5c6ed4 100%);
}
.info-panel::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 44px 44px;
    pointer-events: none;
}
.info-panel::after {
    content: '';
    position: absolute; top:-120px; right:-120px;
    width:350px; height:350px; border-radius:50%;
    border:1px solid rgba(255,255,255,0.07);
    pointer-events: none;
}
.info-panel * { color: white; }

/* Brand */
.ip-brand {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: center; /* ke tengah horizontal */
    margin-top: 20px;        /* turun sedikit */
}
.brand-stack {
    display: flex;
    flex-direction: column;
    align-items: center; /* isi ikut ke tengah */
    gap: .6rem;
}
.ip-brand-name {
    font-size: .750rem;
    font-weight: 700;
    line-height: 1.4;
    color: rgba(255,255,255,.85);
    letter-spacing: .01em;
}
.brand-logo {
    width: 175px;
    filter: drop-shadow(0 6px 16px rgba(0,0,0,.30));
}

/* Clock */
.ip-center {
    position: relative; z-index: 1;
    flex: 1;
    display: flex; flex-direction: column;
    align-items: flex-start; justify-content: center;
    padding: 1.5rem 0;
}
.clock-wrap { position: relative; width: 100%; }
.clock-accent {
    width: 28px; height: 3px;
    background: linear-gradient(90deg, rgba(255,255,255,.7), rgba(255,255,255,.3));
    border-radius: 999px;
    margin-bottom: 1rem;
}
.clock-time {
    font-size: clamp(2.25rem, 4vw, 3rem);
    font-weight: 800;
    color: white;
    letter-spacing: -.04em;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}
.clock-time .sec {
    font-size: .65em;
    font-weight: 600;
    color: rgba(255,255,255,.5);
    letter-spacing: -.02em;
}
.clock-date {
    margin-top: .625rem;
    font-size: .8125rem;
    font-weight: 500;
    color: rgba(255,255,255,.6);
    line-height: 1.4;
}
.clock-day {
    font-size: .625rem;
    font-weight: 700;
    color: rgba(255,255,255,.35);
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-top: .25rem;
}
.clock-ring-svg {
    position: absolute; right: -20px; top: 50%;
    transform: translateY(-50%);
    opacity: .10; pointer-events: none;
}

/* Features */
.ip-features {
    margin-top: 1.75rem;
    display: flex;
    flex-direction: column;
    gap: .5rem;
}
.feature {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    font-size: .6875rem;
    font-weight: 600;
    padding: .45rem .6rem;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.16);
    border-radius: 8px;
    width: 100%;
}
.feature i { font-size: .625rem; opacity: .85; }

/* Pills */
.ip-pills {
    position: relative; z-index: 1;
    display: flex; flex-wrap: wrap; gap: .4rem;
}
.ip-pill {
    display: flex; align-items: center; gap: .35rem;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 999px;
    padding: .3rem .65rem;
    font-size: .6rem; font-weight: 600;
    color: rgba(255,255,255,.75);
}
.ip-pill-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }

/* ══════════════════════════════
   RIGHT FORM PANEL
══════════════════════════════ */
.form-panel {
    flex: 1;
    background: #ffffff;
    display: flex; align-items: center; justify-content: center;
    padding: 2.75rem 3rem;
    overflow-y: auto;
    position: relative;
}
.form-panel::before {
    content: '';
    position: absolute; top: 0; right: 0;
    width: 200px; height: 200px;
    background: radial-gradient(ellipse at top right, rgba(189,198,245,0.35) 0%, transparent 70%);
    pointer-events: none;
}
.fp-inner {
    width: 100%; max-width: 360px;
    position: relative; z-index: 1;
}

/* Title */
.fp-title {
    font-size: clamp(1.5rem, 2.5vw, 1.75rem);
    font-weight: 800;
    color: var(--slate-900);
    letter-spacing: -.025em;
    line-height: 1.2;
    margin-bottom: .375rem;
}
.fp-sub {
    font-size: .875rem;
    color: var(--slate-400);
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Fields */
.fg { margin-bottom: 1rem; }
.fl {
    display: flex; align-items: center; gap: .35rem;
    font-size: .8125rem; font-weight: 600;
    color: var(--slate-600);
    margin-bottom: .375rem;
    transition: color .18s;
}
.fl i { color: var(--slate-400); font-size: .6875rem; }
.fi {
    width: 100%;
    padding: .8125rem 1rem;
    font-size: .9375rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--slate-900);
    background: var(--slate-50);
    border: 1.5px solid var(--slate-200);
    border-radius: 10px;
    outline: none;
    transition: all .2s;
}
.fi::placeholder { color: var(--slate-400); }
.fi:hover  { border-color: var(--slate-300); background: white; }
.fi:focus  { border-color: var(--blue-600); background: white; box-shadow: 0 0 0 3px rgba(59,76,202,.1); }
.fi.is-invalid { border-color: var(--red-400); background: #fef2f2; }
.fi.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }

.pw-wrap { position: relative; }
.pw-wrap .fi { padding-right: 3rem; }
.pw-eye {
    position: absolute; right: 1rem; top: 50%;
    transform: translateY(-50%);
    color: var(--slate-400); cursor: pointer;
    font-size: .9375rem; transition: color .18s; z-index: 2;
}
.pw-eye:hover { color: var(--blue-600); }

.f-err {
    color: var(--red-500); font-size: .8125rem; font-weight: 500;
    margin-top: .3rem;
    display: flex; align-items: center; gap: .35rem;
}

/* Forgot */
.forgot-row {
    display: flex; justify-content: flex-end;
    margin-top: .125rem;
    margin-bottom: 1.375rem;
}
.forgot-link {
    font-size: .8125rem; font-weight: 600;
    color: var(--blue-600); text-decoration: none;
    transition: color .18s;
}
.forgot-link:hover { color: var(--blue-800); }

/* Submit Button */
.btn-submit {
    width: 100%;
    padding: .9375rem 1.5rem;
    font-size: .9375rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: white !important;
    background: linear-gradient(135deg, var(--blue-600) 0%, var(--indigo-600) 100%);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all .22s;
    box-shadow: 0 4px 16px rgba(59,76,202,.28);
    margin-bottom: 1.5rem;
    letter-spacing: .015em;
    position: relative;
    overflow: hidden;
    z-index: 1;
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(59,76,202,.38);
    background: linear-gradient(135deg, var(--blue-700) 0%, var(--indigo-600) 100%);
    font-size: 1rem;
    color: white !important;
}
.btn-submit span {
    color: white !important;
    text-shadow: 0 1px 2px rgba(0,0,0,.2);
    transition: inherit;
}
.btn-submit i {
    position: relative; z-index: 2;
    color: white !important;
}
.btn-submit:active { transform: none; }
.btn-submit:disabled { opacity: .65; cursor: not-allowed; transform: none; }

/* Divider */
.div-row {
    display: flex; align-items: center; gap: .875rem;
    margin-bottom: 1.125rem;
}
.div-row::before, .div-row::after {
    content: ''; flex: 1; height: 1px; background: var(--slate-200);
}
.div-lbl {
    font-size: .6875rem; font-weight: 700;
    color: var(--slate-400); letter-spacing: .07em;
    text-transform: uppercase; white-space: nowrap;
}

/* Quick Access */
.qa-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
}
.qa-grid > div:first-child {
    grid-column: 1 / -1;
    width: 100%;
}
.qa-col-lbl {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .35rem;
    font-size: .6875rem;
    font-weight: 700;
    color: var(--slate-500);
    margin-bottom: .5rem;
    padding-bottom: .375rem;
    border-bottom: 1.5px solid var(--slate-100);
    text-transform: uppercase;
    letter-spacing: .06em;
}
.qa-btns {
    display: flex; flex-direction: column;
    gap: .4375rem;
    align-items: stretch;
}
.qb {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    padding: .5625rem .75rem;
    font-size: .8125rem; font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    text-decoration: none;
    border-radius: 8px;
    transition: all .18s;
    border: 1.5px solid;
    width: 100%;
    color: inherit;
}
.qb.face {
    background: var(--blue-50);
    color: var(--blue-700);
    border-color: var(--blue-200);
}
.qb.face:hover {
    background: var(--blue-600);
    color: white !important;
    border-color: var(--blue-600);
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59,76,202,.20);
    font-size: .875rem;
}
.qb.qr {
    background: var(--slate-50);
    color: var(--slate-700);
    border-color: var(--slate-200);
}
.qb.qr:hover {
    background: var(--slate-700);
    color: white !important;
    border-color: var(--slate-700);
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
    font-size: .875rem;
}

/* Footer */
.fp-footer {
    margin-top: 1.375rem;
    padding-top: 1.125rem;
    border-top: 1px solid var(--slate-100);
    display: flex; align-items: center; gap: .5rem;
}
.fp-footer-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #3b4cca;
    flex-shrink: 0;
    box-shadow: 0 0 0 3px rgba(59,76,202,.15);
}
.fp-footer-text {
    font-size: .6875rem;
    color: var(--slate-400);
    line-height: 1.4;
}
.fp-footer-text strong {
    color: var(--slate-600);
    font-weight: 600;
}

/* ══════════════════════════════
   RESPONSIVE
══════════════════════════════ */
@media (max-width: 860px) {
    .info-panel { display: none; }
    .card-outer {
        max-width: 440px; min-height: unset;
        border-radius: 20px; background: transparent;
        border: none; box-shadow: none;
    }
    .form-panel { border-radius: 20px; box-shadow: 0 24px 56px rgba(0,0,0,.28); }
}
@media (max-width: 480px) {
    .page-wrap { padding: 1.25rem 1rem; }
    .form-panel { padding: 2rem 1.5rem; }
    .fp-title { font-size: 1.5rem; }
    .qa-grid { grid-template-columns: 1fr; gap: .5rem; }
}
@media (max-width: 360px) {
    .qa-btns { flex-direction: row; }
    .qb { flex: 1; padding: .5rem .375rem; font-size: .75rem; }
}
@media (max-height: 640px) and (orientation: landscape) {
    .page-wrap { padding: 1rem; }
    .form-panel { padding: 1.5rem 2rem; align-items: flex-start; }
    .fp-sub { margin-bottom: 1.25rem; }
    .fg { margin-bottom: .75rem; }
    .btn-submit { margin-bottom: 1.125rem; }
    .fp-footer { display: none; }
}
</style>

{{-- BACKGROUND --}}
<div class="aurora-bg">
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>
    <div class="orb orb-c"></div>
    <div class="arc-ring arc-1"></div>
    <div class="arc-ring arc-2"></div>
    <div class="arc-ring arc-3"></div>
    <div class="arc-ring arc-4"></div>
    <div class="arc-ring arc-5"></div>
    <div class="dot-grid"></div>
    <div class="grain"></div>
</div>

{{-- PAGE --}}
<div class="page-wrap">
    <div class="card-outer">

        {{-- ── LEFT INFO PANEL ── --}}
        <div class="info-panel">

            {{-- Brand --}}
            <div class="ip-brand">
                <div class="brand-stack">
                    <div class="ip-brand-name">PT Multi Engineering Technologies</div>
                    <img src="{{ asset('assets/img/logo-metech.png') }}"
                        class="brand-logo"
                        alt="Metech Logo">
                </div>
            </div>

            {{-- Clock --}}
            <div class="ip-center">
                <div class="clock-wrap">
                    <div class="clock-accent"></div>

                    <div class="clock-time">
                        <span id="clock-hm">00:00</span><span class="sec">:<span id="clock-sec">00</span></span>
                    </div>

                    <div class="clock-date" id="clock-date-full">—</div>
                    <div class="clock-day"  id="clock-day">—</div>

                    <svg class="clock-ring-svg" width="140" height="140" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="70" cy="70" r="68" stroke="white" stroke-width="1"/>
                        <circle cx="70" cy="70" r="52" stroke="white" stroke-width="0.75"/>
                        <circle cx="70" cy="70" r="36" stroke="white" stroke-width="0.5"/>
                        <circle cx="70" cy="2"   r="4" fill="white"/>
                        <circle cx="138" cy="70" r="3" fill="rgba(255,255,255,0.5)"/>
                        <line x1="60" y1="70" x2="80" y2="70" stroke="rgba(255,255,255,0.4)" stroke-width="0.75"/>
                        <line x1="70" y1="60" x2="70" y2="80" stroke="rgba(255,255,255,0.4)" stroke-width="0.75"/>
                    </svg>

                    <div class="ip-features">
                        <div class="feature">
                            <i class="fas fa-user-circle"></i>
                            Face ID
                        </div>
                        <div class="feature">
                            <i class="fas fa-shield-alt"></i>
                            Secure
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT FORM PANEL ── --}}
        <div class="form-panel">
            <div class="fp-inner">

                <h1 class="fp-title">{{ $title }}</h1>
                <p class="fp-sub">Masukkan kredensial Anda untuk melanjutkan ke dasbor sistem.</p>

                <form action="{{ url('/login-proses') }}" method="POST">
                    @csrf

                    <div class="fg">
                        <label class="fl"><i class="fas fa-user"></i>Username</label>
                        <input type="text"
                               placeholder="Masukkan username"
                               class="fi @error('username') is-invalid @enderror"
                               value="{{ old('username') }}"
                               name="username"
                               autocomplete="username">
                        @error('username')
                        <div class="f-err"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fg">
                        <label class="fl"><i class="fas fa-lock"></i>Password</label>
                        <div class="pw-wrap">
                            <input type="password"
                                   placeholder="Masukkan password"
                                   class="fi @error('password') is-invalid @enderror"
                                   name="password"
                                   id="pw-input"
                                   autocomplete="current-password">
                            <i class="fas fa-eye pw-eye" id="pw-toggle"></i>
                        </div>
                        @error('password')
                        <div class="f-err"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="forgot-row">
                        <a href="{{ url('/forgot-password') }}" class="forgot-link">
                            <i class="fas fa-key" style="font-size:.7rem;margin-right:.2rem;"></i>Lupa Password?
                        </a>
                    </div>

                    <button type="submit" class="btn-submit">
                        <span>
                            <i class="fas fa-sign-in-alt" style="margin-right:.4rem;"></i>
                            Masuk ke Sistem
                        </span>
                    </button>
                </form>

                <div class="div-row"><span class="div-lbl">Akses Cepat Absensi</span></div>

                <div class="qa-grid">
                    <div>
                        <div class="qa-col-lbl">
                            <i class="fas fa-user-circle"></i>Face ID
                        </div>
                        <div class="qa-btns">
                            <a href="{{ url('/presensi') }}"        class="qb face"><i class="fas fa-sign-in-alt"></i>Masuk</a>
                            <a href="{{ url('/presensi-pulang') }}"  class="qb face"><i class="fas fa-sign-out-alt"></i>Pulang</a>
                        </div>
                    </div>
                    <div>
                        {{-- QR column (commented out, preserved) --}}
                        {{--
                        <div class="qa-col-lbl">
                            <i class="fas fa-qrcode"></i>QR Code
                        </div>
                        <div class="qa-btns">
                            <a href="{{ url('/qr-masuk') }}"  class="qb qr"><i class="fas fa-sign-in-alt"></i>Masuk</a>
                            <a href="{{ url('/qr-pulang') }}" class="qb qr"><i class="fas fa-sign-out-alt"></i>Pulang</a>
                        </div>
                        --}}
                    </div>
                </div>

                <div class="fp-footer">
                    <div class="fp-footer-dot"></div>
                    <div class="fp-footer-text">
                        <strong>Metech</strong> - Sistem aman &amp; terenkripsi
                        &copy; {{ date('Y') }} Metech
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
/* ── Password toggle ── */
document.getElementById('pw-toggle').addEventListener('click', function () {
    const inp = document.getElementById('pw-input');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});

/* ── Submit spinner ── */
document.querySelector('form').addEventListener('submit', function () {
    const btn = this.querySelector('.btn-submit');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:.4rem"></i>Memproses...';
    btn.disabled = true;
});

/* ── Input focus label color ── */
document.querySelectorAll('.fi').forEach(el => {
    const lb = el.closest('.fg')?.querySelector('.fl');
    if (!lb) return;
    el.addEventListener('focus', () => lb.style.color = '#3b4cca');
    el.addEventListener('blur',  () => lb.style.color = '');
});

/* ── Live clock with seconds ── */
(function tick() {
    const n      = new Date();
    const hm     = n.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const sec    = String(n.getSeconds()).padStart(2, '0');
    const dateStr= n.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    const dayStr = n.toLocaleDateString('id-ID', { weekday: 'long' }).toUpperCase();

    const hme = document.getElementById('clock-hm');
    const sce = document.getElementById('clock-sec');
    const dte = document.getElementById('clock-date-full');
    const dye = document.getElementById('clock-day');

    if (hme) hme.textContent = hm;
    if (sce) sce.textContent = sec;
    if (dte) dte.textContent = dateStr;
    if (dye) dye.textContent = dayStr;

    setTimeout(tick, 1000);
})();
</script>
@endsection