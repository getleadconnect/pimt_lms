@extends('website.layout')

@section('title', 'Student Dashboard - AnimeStudio Learning Platform')

@push('styles')
<style>
    :root {
        --bg-deep: #0a0e17;
        --bg-panel: #11161f;
        --bg-card: #161c27;
        --bg-card-hover: #1c2330;
        --line: #232b39;
        --line-2: #2a3343;
        --txt-primary: #e6e9ef;
        --txt-secondary: #9aa3b2;
        --txt-muted: #5c6677;
        --accent: #f7931e;
        --accent-2: #ffbb55;
        --green: #22c55e;
        --green-soft: rgba(34, 197, 94, 0.15);
        --blue: #3b82f6;
        --blue-soft: rgba(59, 130, 246, 0.15);
        --pink: #ec4899;
        --pink-soft: rgba(236, 72, 153, 0.15);
        --amber: #f59e0b;
        --amber-soft: rgba(245, 158, 11, 0.15);
        --rose: #f43f5e;
        --rose-soft: rgba(244, 63, 94, 0.15);
        --violet: #8b5cf6;
        --violet-soft: rgba(139, 92, 246, 0.15);
    }

    body { background: var(--bg-deep); }

    /* ===== NAVBAR (override shared layout to match dark theme) ===== */
    .navbar.fixed-top {
        background: rgba(17, 22, 31, 0.92) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #1c2330;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .navbar .navbar-brand,
    .navbar .navbar-brand:hover { color: #f7931e !important; }
    .navbar .navbar-brand img { background: transparent !important; }
    .navbar-nav .nav-link,
    .navbar-nav .nav-link:focus { color: #c7ccd6 !important; }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active { color: #f7931e !important; }

    .navbar .btn-primary-custom {
        background: #f7931e !important;
        border-color: #f7931e !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(247, 147, 30, 0.3);
    }
    .navbar .btn-primary-custom:hover {
        background: #ffbb55 !important;
        border-color: #ffbb55 !important;
    }

    .navbar .dropdown-menu {
        background: #161c27;
        border: 1px solid #232b39;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
    }
    .navbar .dropdown-item { color: #c7ccd6; }
    .navbar .dropdown-item:hover,
    .navbar .dropdown-item:focus { background: #1c2330; color: #f7931e; }
    .navbar .dropdown-item.text-danger { color: #fb7185 !important; }
    .navbar .dropdown-item.text-danger:hover { background: rgba(244, 63, 94, 0.1); color: #fda4af !important; }
    .navbar .dropdown-divider { border-top-color: #232b39; }

    .navbar-toggler { border-color: #232b39; padding: 4px 8px; }
    .navbar-toggler:focus { box-shadow: 0 0 0 2px rgba(247, 147, 30, 0.4); }
    .navbar-toggler-icon { filter: invert(0.9); }

    /* ===== FOOTER (override shared layout to match dark theme) ===== */
    .footer {
        background: #0a0e17 !important;
        color: #c7ccd6 !important;
        border-top: 1px solid #1c2330;
    }
    .footer h5 { color: #ffffff; font-weight: 700; }
    .footer p, .footer li, .footer span { color: #9aa3b2; }
    .footer a { color: #9aa3b2; text-decoration: none; transition: color 0.2s ease; }
    .footer a:hover { color: #f7931e; }
    .footer .contact-info li i { color: #f7931e !important; }
    .footer hr { background-color: #1c2330 !important; opacity: 1; }
    .footer .social-links a {
        background: rgba(247, 147, 30, 0.1);
        color: #f7931e;
        border: 1px solid rgba(247, 147, 30, 0.2);
    }
    .footer .social-links a:hover {
        background: #f7931e;
        color: #fff;
        border-color: #f7931e;
    }

    /* override layout footer & main padding inside dashboard scope */
    .dash-shell { color: var(--txt-primary); }

    /* ===== LAYOUT SHELL: sidebar + content ===== */
    .dash-wrapper {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 24px;
        padding: 24px 24px 40px;
        min-height: calc(100vh - 80px);
        margin-top:20px;
    }
    @media (max-width: 991px) {
        .dash-wrapper { grid-template-columns: 1fr; padding: 16px; gap: 16px; }
    }

    /* ===== SIDEBAR ===== */
    .dash-sidebar {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 22px 16px;
        align-self: flex-start;
        position: sticky;
        top: 96px;
    }
    @media (max-width: 991px) { .dash-sidebar { position: static; } }

    .sidebar-section {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--txt-muted);
        padding: 10px 12px 8px;
    }
    .sidebar-link {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 14px;
        border-radius: 12px;
        color: var(--txt-secondary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.92rem;
        margin-bottom: 4px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .sidebar-link i { font-size: 1rem; width: 18px; text-align: center; }
    .sidebar-link:hover { background: var(--bg-card); color: var(--txt-primary); }
    .sidebar-link.active {
        background: linear-gradient(135deg, rgba(247,147,30,0.18) 0%, rgba(247,147,30,0.08) 100%);
        color: var(--accent-2);
        border-color: rgba(247, 147, 30, 0.25);
    }
    .sidebar-link.active i { color: var(--accent); }
    .sidebar-link.danger:hover { color: #fca5a5; background: rgba(244, 63, 94, 0.08); }

    .sidebar-form { margin: 0; padding: 0; }
    .sidebar-form button {
        width: 100%; text-align: left;
        background: transparent; border: 1px solid transparent;
    }

    /* ===== TOP-RIGHT PROFILE PILL (floats over hero) ===== */
    .profile-pill {
        position: absolute; top: 0; right: 0;
        display: inline-flex; align-items: center; gap: 12px;
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 999px;
        padding: 6px 14px 6px 6px;
    }
    .profile-pill .avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--green) 0%, #16a34a 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 0.95rem;
    }
    .profile-pill .pill-info { line-height: 1.15; }
    .profile-pill .pill-name { color: var(--txt-primary); font-weight: 600; font-size: 0.92rem; }
    .profile-pill .pill-role { color: var(--txt-muted); font-size: 0.72rem; }

    /* ===== HERO HEADER ===== */
    .dash-hero { position: relative; margin-bottom: 28px; padding-right: 200px; }
    @media (max-width: 575px) { .dash-hero { padding-right: 0; } .profile-pill { position: static; margin-bottom: 16px; } }

    .hero-title {
        font-size: 2rem; font-weight: 700;
        color: var(--txt-primary); margin: 0 0 6px;
        letter-spacing: -0.5px;
    }
    .hero-title .accent { color: var(--accent); }
    .hero-sub { color: var(--txt-secondary); margin: 0; font-size: 0.95rem; }

    /* ===== STAT CARDS ===== */
    .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
    @media (max-width: 991px) { .stat-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 420px) { .stat-row { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 14px 18px;
        transition: all 0.25s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 64px;
    }
    .stat-card:hover {
        background: var(--bg-card-hover);
        border-color: var(--line-2);
        transform: translateY(-2px);
        color: inherit;
    }
    .stat-icon {
        order: 1;
        flex: 0 0 auto;
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem;
        margin-bottom: 0;
    }
    .stat-icon.green { background: var(--green-soft); color: var(--green); }
    .stat-icon.blue  { background: var(--blue-soft);  color: var(--blue); }
    .stat-icon.amber { background: var(--amber-soft); color: var(--amber); }
    .stat-icon.violet{ background: var(--violet-soft);color: var(--violet); }

    .stat-label {
        order: 2;
        flex: 1 1 auto;
        font-size: 0.85rem;
        color: var(--txt-secondary);
        margin: 0;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stat-value {
        order: 3;
        flex: 0 0 auto;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--txt-primary);
        line-height: 1;
        margin: 0;
    }

    /* ===== CONTENT ROW (Continue Learning + Upcoming) ===== */
    .content-grid { display: grid; grid-template-columns: 1fr 360px; gap: 20px; margin-bottom: 24px; }
    @media (max-width: 991px) { .content-grid { grid-template-columns: 1fr; } }

    .panel {
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 22px;
    }
    .panel-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 18px;
    }
    .panel-title {
        font-size: 1.05rem; font-weight: 700;
        color: var(--txt-primary); margin: 0;
    }
    .panel-link {
        color: var(--accent); font-size: 0.82rem; font-weight: 600;
        text-decoration: none;
    }
    .panel-link:hover { color: var(--accent-2); }

    /* Question papers table */
    .qpaper-panel { margin-top: 18px; }
    .qpaper-table-wrap {
        overflow-x: auto;
        border: 1px solid var(--line);
        border-radius: 12px;
    }
    .qpaper-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--txt-primary);
        font-size: 0.88rem;
    }
    .qpaper-table thead th {
        text-align: left;
        font-weight: 600;
        font-size: 0.78rem;
        color: var(--txt-secondary);
        background: var(--bg-panel);
        padding: 12px 14px;
        border-bottom: 1px solid var(--line);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .qpaper-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
    }
    .qpaper-table tbody tr:last-child td { border-bottom: none; }
    .qpaper-table tbody tr:hover { background: var(--bg-card-hover); }
    .qpaper-table .text-end { text-align: right; }
    .btn-add {
        display: inline-flex; align-items: center;
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%);
        color: #fff;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-add:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(247, 147, 30, 0.35);
    }
    .badge-attended {
        display: inline-flex; align-items: center;
        background: var(--green-soft);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.35);
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .badge-attended i { color: #4ade80; }


.badge-not-attended
 {
    display: inline-flex;
    align-items: center;
    background: rgb(223 160 148 / 15%);
    color: #f45748;
    border: 1px solid rgb(231 127 104 / 35%);
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
}
.badge-not-attended i { color:  #f45748; }



    /* Continue Learning */
    .cl-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 520px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .cl-list::-webkit-scrollbar { width: 6px; }
    .cl-list::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 999px; }
    .cl-list::-webkit-scrollbar-track { background: transparent; }

    .cl-card {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 16px;
        display: flex; gap: 16px;
        text-decoration: none;
        color: inherit;
        transition: all 0.25s ease;
        margin:5px;
    }
    .cl-card:hover { background: #141a25; border-color: var(--line-2); color: inherit; cursor: pointer; }
    .cl-card:focus { outline: 2px solid var(--accent); outline-offset: 2px; }

    /* ===== COURSE CHOICE MODAL ===== */
    .course-choice-modal {
        position: fixed; inset: 0;
        z-index: 1080;
        display: none;
        align-items: center; justify-content: center;
        padding: 20px;
    }
    .course-choice-modal.open { display: flex; }
    .course-choice-backdrop {
        position: absolute; inset: 0;
        background: rgba(5, 8, 14, 0.72);
        /*backdrop-filter: blur(6px);*/
        -webkit-backdrop-filter: blur(6px);
        animation: ccmFade 0.2s ease both;
    }
    .course-choice-dialog {
        position: relative;
        width: 100%;
        max-width: 480px;
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 28px 26px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6);
        animation: ccmRise 0.25s ease both;
    }
    @keyframes ccmFade { from { opacity: 0; } to { opacity: 1; } }
    @keyframes ccmRise { from { opacity: 0; transform: translateY(14px) scale(0.96); } to { opacity: 1; transform: translateY(0) scale(1); } }

    .ccm-close {
        position: absolute; top: 14px; right: 14px;
        background: var(--bg-panel);
        border: 1px solid var(--line);
        color: var(--txt-secondary);
        width: 32px; height: 32px;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .ccm-close:hover { color: #fff; background: var(--rose); border-color: var(--rose); }

    .ccm-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--txt-primary);
        margin: 0 30px 4px 0;
        letter-spacing: -0.2px;
    }
    .ccm-sub {
        font-size: 0.85rem;
        color: var(--accent);
        margin: 0 0 22px;
        font-weight: 600;
    }

    .ccm-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    @media (max-width: 480px) { .ccm-options { grid-template-columns: 1fr; } }

    .ccm-option {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 22px 14px;
        text-align: center;
        text-decoration: none;
        color: var(--txt-primary);
        transition: all 0.25s ease;
        display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .ccm-option:hover {
        background: var(--bg-card-hover);
        border-color: rgba(247, 147, 30, 0.5);
        transform: translateY(-3px);
        color: var(--txt-primary);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.35);
    }
    .ccm-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.7rem;
        color: #fff;
    }
    .ccm-icon-classes { background: linear-gradient(135deg, #4facfe 0%, #2563eb 100%); box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35); }
    .ccm-icon-tests   { background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%); box-shadow: 0 8px 20px rgba(247, 147, 30, 0.35); }
    .ccm-label { font-size: 1rem; font-weight: 700; }
    .ccm-hint { font-size: 0.78rem; color: var(--txt-muted); }
    .cl-thumb {
        width: 100px; height: 100px; flex-shrink: 0;
        border-radius: 12px; overflow: hidden;
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 1.3rem;
    }
    .cl-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .cl-body { flex: 1; min-width: 0; }
    .cl-title {
        font-size: 1rem; font-weight: 700;
        color: var(--txt-primary); margin: 0 0 4px;
    }
    .cl-meta { color: var(--txt-muted); font-size: 0.82rem; margin-bottom: 12px; display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
    .cl-meta i { color: var(--accent); margin-right: 4px; }
    .cl-meta .cl-status { margin-left: auto; }
    .cl-meta .cl-status.active i  { color: #4ade80; }
    .cl-meta .cl-status.expired i { color: #fb7185; }
    .cl-progress {
        height: 6px; background: var(--line);
        border-radius: 999px; overflow: hidden;
        margin-bottom: 8px;
    }
    .cl-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-2) 100%);
        border-radius: 999px;
        transition: width 0.6s ease;
    }
    .cl-progress-meta {
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.78rem; color: var(--txt-secondary);
    }
    .cl-progress-meta .pct { color: var(--txt-primary); font-weight: 600; }

    /* Upcoming Lessons */
    .lesson-item {
        display: flex; gap: 12px; align-items: flex-start;
        padding: 14px;
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }
    .lesson-item:hover { border-color: var(--line-2); background: #141a25; }
    .lesson-num {
        width: 36px; flex-shrink: 0;
        text-align: center;
    }
    .lesson-num .num {
        font-size: 1.4rem; font-weight: 800;
        color: var(--txt-primary); line-height: 1;
    }
    .lesson-num .lbl {
        font-size: 0.62rem; color: var(--txt-muted);
        letter-spacing: 1px; text-transform: uppercase;
        margin-top: 4px;
    }
    .lesson-body { flex: 1; min-width: 0; }
    .lesson-title {
        font-size: 0.88rem; font-weight: 600;
        color: var(--txt-primary); margin: 0 0 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .lesson-desc {
        font-size: 0.76rem; color: var(--txt-secondary);
        margin: 0; line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .lesson-play {
        width: 32px; height: 32px;
        flex-shrink: 0;
        border-radius: 8px;
        background: var(--accent);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .lesson-play:hover { background: var(--accent-2); color: #fff; transform: scale(1.06); }

    /* ===== ACHIEVEMENTS ===== */
    .ach-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 575px) { .ach-grid { grid-template-columns: 1fr; } }

    .ach-card {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 22px 16px;
        text-align: center;
        transition: all 0.25s ease;
    }
    .ach-card:hover { transform: translateY(-3px); border-color: var(--line-2); }
    .ach-icon {
        width: 56px; height: 56px;
        margin: 0 auto 12px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .ach-icon.amber { background: var(--amber-soft); color: var(--amber); }
    .ach-icon.rose  { background: var(--rose-soft);  color: var(--rose); }
    .ach-icon.green { background: var(--green-soft); color: var(--green); }
    .ach-title {
        font-size: 0.95rem; font-weight: 700;
        color: var(--txt-primary); margin: 0 0 4px;
    }
    .ach-desc { font-size: 0.78rem; color: var(--txt-secondary); margin: 0; }

    /* ===== ENROLLED COURSES (My Courses block) ===== */
    .courses-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    @media (max-width: 767px) { .courses-grid { grid-template-columns: 1fr; } }

    .course-row {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 14px;
        display: flex; gap: 14px; align-items: center;
        transition: all 0.25s ease;
    }
    .course-row:hover { background: #141a25; border-color: var(--line-2); }
    .course-row-thumb {
        width: 70px; height: 70px; flex-shrink: 0;
        border-radius: 10px; overflow: hidden;
        background: linear-gradient(135deg, var(--violet) 0%, #6d28d9 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem;
    }
    .course-row-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .course-row-body { flex: 1; min-width: 0; }
    .course-row-title {
        font-size: 0.95rem; font-weight: 600;
        color: var(--txt-primary); margin: 0 0 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .course-row-meta {
        font-size: 0.75rem; color: var(--txt-muted);
        display: flex; gap: 10px; flex-wrap: wrap;
    }
    .course-row-meta i { color: var(--accent); margin-right: 3px; }

    .badge-pill {
        font-size: 0.7rem; font-weight: 600;
        padding: 4px 10px; border-radius: 999px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-pill.active  { background: var(--green-soft); color: #4ade80; }
    .badge-pill.expired { background: var(--rose-soft);  color: #fb7185; }

    .btn-go {
        width: 36px; height: 36px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
        flex-shrink: 0;
    }
    .btn-go:hover { background: var(--accent-2); color: #fff; transform: translateX(2px); }

    /* ===== CLASSES ===== */
    .class-list { display: flex; flex-direction: column; gap: 10px; max-height: 440px; overflow-y: auto; padding-right: 4px; }
    .class-list::-webkit-scrollbar { width: 6px; }
    .class-list::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 999px; }
    .class-list::-webkit-scrollbar-track { background: transparent; }

    .class-row {
        display: flex; align-items: center; gap: 12px;
        padding: 12px;
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .class-row:hover { border-color: var(--line-2); background: #141a25; }
    .class-thumb {
        width: 48px; height: 48px; flex-shrink: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--blue) 0%, #2563eb 100%);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .class-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .class-thumb.rec { background: linear-gradient(135deg, var(--pink) 0%, #be185d 100%); }
    .class-info { flex: 1; min-width: 0; }
    .class-name {
        font-size: 0.88rem; font-weight: 600;
        color: var(--txt-primary); margin: 0 0 3px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .class-meta { font-size: 0.72rem; color: var(--txt-muted); display: flex; gap: 10px; flex-wrap: wrap; }
    .class-meta i { color: var(--accent); margin-right: 3px; }

    .btn-join, .btn-watch {
        background: var(--accent); color: #fff;
        padding: 6px 12px; border-radius: 999px;
        font-size: 0.72rem; font-weight: 600;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .btn-join:hover, .btn-watch:hover { background: var(--accent-2); color: #fff; }
    .btn-watch { background: var(--pink); }
    .btn-watch:hover { background: #f472b6; }

    /* ===== EMPTY ===== */
    .empty {
        text-align: center; padding: 32px 16px;
        color: var(--txt-muted);
    }
    .empty-icon {
        width: 60px; height: 60px; margin: 0 auto 12px;
        border-radius: 50%;
        background: var(--bg-panel);
        border: 1px solid var(--line);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .empty p { margin: 0; font-size: 0.88rem; }
    .empty .btn-cta {
        margin-top: 14px;
        background: var(--accent); color: #fff;
        padding: 8px 20px; border-radius: 999px;
        font-weight: 600; font-size: 0.85rem;
        text-decoration: none;
        display: inline-block;
    }
    .empty .btn-cta:hover { background: var(--accent-2); color: #fff; }

    /* fade-in */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fadeUp 0.45s ease both; }
    .fade-in.d-1 { animation-delay: 0.05s; }
    .fade-in.d-2 { animation-delay: 0.1s; }
    .fade-in.d-3 { animation-delay: 0.15s; }
    .fade-in.d-4 { animation-delay: 0.2s; }
</style>
@endpush

@section('content')
    @php
        $studentName = $student->student_name ?? 'Student';
        $initial = strtoupper(substr($studentName, 0, 1));
    @endphp

    <div class="dash-shell">
        <div class="dash-wrapper">

            {{-- ===== SIDEBAR ===== --}}
            <aside class="dash-sidebar">
                <div class="sidebar-section">Menu</div>
                <a href="{{ route('student.dashboard') }}" class="sidebar-link active">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

               {{-- <a href="{{ url('/courses') }}" class="sidebar-link">
                    <i class="fas fa-graduation-cap"></i> My Courses
                </a>
                <a href="{{ route('student.course-mock-test') }}" class="sidebar-link">
                    <i class="fas fa-file-alt"></i> Mock Tests
                </a>
                <a href="{{ route('student.easy-tips') }}" class="sidebar-link">
                    <i class="fas fa-lightbulb"></i> Easy Tips
                </a>
                <a href="{{ route('student.success-stories') }}" class="sidebar-link">
                    <i class="fas fa-trophy"></i> Success Stories
                </a> --}}

                <div class="sidebar-section" style="margin-top: 16px;">Account</div>
                <a href="{{ route('student.profile') }}" class="sidebar-link">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form action="{{ route('student.logout') }}" method="POST" class="sidebar-form">
                    @csrf
                    <button type="submit" class="sidebar-link danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </aside>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="dash-content">

                {{-- HERO --}}
                <div class="dash-hero fade-in">
                    <div class="profile-pill">
                        <div class="avatar">{{ $initial }}</div>
                        <div class="pill-info">
                            <div class="pill-name">{{ $studentName }}</div>
                            {{--<div class="pill-role">Pro Member</div>--}}
                        </div>
                    </div>
                    <h1 class="hero-title">Welcome back, <span class="accent">{{ $studentName }}</span>!</h1>
                    <p class="hero-sub">Continue where you left off and keep learning.</p>
                </div>

                @if(Session::has('message'))
                    @php
                        $message = explode('#', Session::get('message'));
                        $type = $message[0] ?? 'info';
                        $text = $message[1] ?? '';
                    @endphp
                    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert" style="border-radius:14px; border:0;">
                        {{ $text }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                {{-- STAT CARDS --}}
                <div class="stat-row">
                    <a href="{{ url('/courses') }}" class="stat-card fade-in d-1">
                        <div class="stat-icon green"><i class="fas fa-book-open"></i></div>
                        <div class="stat-value">{{ $enrolledCourseCount ?? 0 }}</div>
                        <div class="stat-label">Enrolled Course{{ ($enrolledCourseCount ?? 0) == 1 ? '' : 's' }}</div>
                    </a>
                    <div class="stat-card fade-in d-2">
                        <div class="stat-icon green"><i class="fas fa-check-square"></i></div>
                        <div class="stat-value">{{ $lessonsCompleted ?? 0 }}</div>
                        <div class="stat-label">Lessons Completed</div>
                    </div>
                    <div class="stat-card fade-in d-3">
                        <div class="stat-icon blue"><i class="fas fa-photo-video"></i></div>
                        <div class="stat-value">{{ $totalVideos ?? 0 }}</div>
                        <div class="stat-label">Total Videos</div>
                    </div>
                    <div class="stat-card fade-in d-4">
                        <div class="stat-icon amber"><i class="fas fa-clock"></i></div>
                        <div class="stat-value">{{ $learningHours ?? 0 }}h</div>
                        <div class="stat-label">Learning Time</div>
                    </div>
                </div> 

                {{-- CONTINUE LEARNING + UPCOMING LESSONS --}}
                <div class="content-grid">
                    <div class="panel fade-in">
                        <div class="panel-header">
                            <h3 class="panel-title">Continue Learning</h3>
                            <a href="{{ url('/courses') }}" class="panel-link">View All</a>
                        </div>
                        @if(!empty($continueLearning) && count($continueLearning) > 0)
                            <div class="cl-list">
                                @foreach($continueLearning as $cl)
                                    @php
                                        $encodedCourseId = base64_encode((string) str_pad($cl->course_id,10,'0',STR_PAD_LEFT));
                                    @endphp
                                    <a href="{{ route('student.course-mock-test', $encodedCourseId) }}" style="text-decoration: none;">
                                    <div class="cl-card"
                                         role="button"
                                         tabindex="0"
                                         {{-- <!--onclick="openCourseChoice({{ $cl->course_id }}, '{{ addslashes($cl->course_name) }}', '{{ route('student.course-content', $cl->course_id) }}', '{{ route('student.course-mock-test', $encodedCourseId) }}')"--> --}}
                                         
                                         >
                                        <div class="cl-thumb">
                                            @if($cl->course_icon)
                                                {{--<img src="{{ config('constants.course_icon').$cl->course_icon }}" alt="{{ $cl->course_name }}">--}}
                                                <img src="{{ asset('assets/course_icon.png') }}" alt="{{ $cl->course_name }}">
                                            @else
                                                <i class="fas fa-book"></i>
                                            @endif
                                        </div>
                                        <div class="cl-body">
                                            <h4 class="cl-title">{{ $cl->course_name }}</h4>
                                            <div class="cl-meta">
                                                <span><i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($cl->start_date)->format('d M Y') }}</span>
                                                <span class="mx-2">|</span>
                                                <span><i class="fas fa-hourglass-half"></i> {{ $cl->valid_till }}</span>
                                                <span class="badge-pill cl-status {{ $cl->is_expired ? 'expired' : 'active' }}">
                                                    @if($cl->is_expired)
                                                        <i class="fas fa-times-circle"></i> {{ $cl->subscription_status }}
                                                    @else
                                                        <i class="fas fa-clock"></i> {{ $cl->subscription_status }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="cl-progress">
                                                <div class="cl-progress-bar" style="width: {{ $cl->percent }}%;"></div>
                                            </div>
                                            <div class="cl-progress-meta">
                                                <span>{{ $cl->videos_done }}/{{ $cl->videos_total }} videos completed</span>
                                                <span class="pct">{{ $cl->percent }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        @else
                            <div class="empty">
                                <div class="empty-icon"><i class="fas fa-play-circle"></i></div>
                                <p>Enroll in a course to start learning.</p>
                                <a href="{{ url('/courses') }}" class="btn-cta">Browse Courses</a>
                            </div>
                        @endif
                    </div>

                    <div class="panel fade-in">
                        <div class="panel-header">
                            <h3 class="panel-title">Upcoming Lessons</h3>
                        </div>
                        @if(isset($upcomingLessons) && count($upcomingLessons) > 0)
                            @foreach($upcomingLessons as $idx => $lesson)
                                <div class="lesson-item">
                                    <div class="lesson-num">
                                        <div class="num">{{ $idx + 1 }}</div>
                                        <div class="lbl">Next</div>
                                    </div>
                                    <div class="lesson-body">
                                        <h5 class="lesson-title">{{ $lesson->title ?: $lesson->chapter_name }}</h5>
                                        <p class="lesson-desc">{{ $lesson->chapter_name ? $lesson->chapter_name.' · ' : '' }}{{ $lesson->description ?: 'Continue this chapter to make progress.' }}</p>
                                    </div>
                                    <a href="{{ route('student.course-content', $lesson->course_id) }}" class="lesson-play" title="Play">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="empty">
                                <div class="empty-icon"><i class="fas fa-list-ul"></i></div>
                                <p>No upcoming lessons. You're all caught up!</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- QUESTION PAPERS TABLE --}}
                <div class="panel fade-in qpaper-panel mb-3">
                    <div class="panel-header">
                        <h3 class="panel-title"><i class="fas fa-file-alt me-2" style="color: var(--violet);"></i>Examinations</h3>
                        <span class="panel-link" style="cursor:default;">
                            @isset($questionPapersTable){{ count($questionPapersTable) }}@else 0 @endisset
                        </span>
                    </div>
                    @if(!empty($questionPapersTable) && count($questionPapersTable) > 0)
                        <div class="qpaper-table-wrap">
                            <table class="qpaper-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question Paper</th>
                                        <th>Course</th>
                                        <th>Start Date</th>
                                        <th>Time</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questionPapersTable as $i => $qp)
                                        @php
                                            $paperDate = $qp->start_date
                                                ? \Carbon\Carbon::parse($qp->start_date)->format('Y-m-d')
                                                : null;
                                            $isToday = ($paperDate === $todayIst);
                                            $encodedCourseId = base64_encode((string) str_pad($qp->course_id, 10, '0', STR_PAD_LEFT));
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $qp->question_paper_name }}</td>
                                            <td>{{ $qp->course_name }}</td>
                                            <td>{{ $qp->start_date ? \Carbon\Carbon::parse($qp->start_date)->format('d M Y') : '-' }}</td>
                                            <td>
                                                {{ $qp->start_time ? \Carbon\Carbon::parse($qp->start_time)->format('h:i A') : '-' }}
                                                &mdash;
                                                {{ $qp->end_time ? \Carbon\Carbon::parse($qp->end_time)->format('h:i A') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                @if(!empty($qp->attended_id))
                                                    <span class="badge-attended">
                                                        <i class="fas fa-check-circle me-1"></i>Attended
                                                    </span>
                                                @elseif(empty($qp->attended_id) and $qp->start_date<date('Y-m-d'))
                                                    <span class="badge-not-attended">
                                                        <i class="fas fa-times-circle me-1"></i>Unattended
                                                    </span>
                                                @elseif($isToday)
                                                    <a href="{{ route('student.course-mock-test', $encodedCourseId) }}"
                                                       class="btn-add">
                                                        <i class="fas fa-plus me-1"></i>Attend Test
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">
                            <div class="empty-icon"><i class="fas fa-file-alt"></i></div>
                            <p>No mock tests available for your enrolled courses yet.</p>
                        </div>
                    @endif
                </div>


                {{-- LIVE & RECORDED CLASSES --}}
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="panel fade-in" style="margin: 0;">
                            <div class="panel-header">
                                <h3 class="panel-title"><i class="fas fa-video me-2" style="color: var(--blue);"></i>Online Classes</h3>
                                @if(isset($liveClasses) && count($liveClasses) > 0)
                                    <span class="panel-link" style="cursor:default;">{{ count($liveClasses) }}</span>
                                @endif
                            </div>
                            @if(isset($liveClasses) && count($liveClasses) > 0)
                                <div class="class-list">
                                    @foreach($liveClasses as $liveClass)
                                        <div class="class-row">
                                            <div class="class-thumb">
                                                @if($liveClass->class_icon)
                                                    <img src="{{ config('constants.live_class_icon').$liveClass->class_icon }}" alt="{{ $liveClass->title }}">
                                                @else
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                @endif
                                            </div>
                                            <div class="class-info">
                                                <h5 class="class-name">{{ $liveClass->title }}</h5>
                                                <div class="class-meta">
                                                    <span><i class="fas fa-user"></i>{{ $liveClass->conducted_by }}</span>
                                                    <span><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($liveClass->start_date)->format('d M Y') }}</span>
                                                    <span><i class="fas fa-clock"></i>{{ \Carbon\Carbon::parse($liveClass->start_time)->format('h:i A') }}</span>
                                                </div>
                                            </div>
                                            @if($liveClass->class_link)
                                                <a href="{{ $liveClass->class_link }}" target="_blank" class="btn-join">
                                                    <i class="fas fa-external-link-alt"></i> Join
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty">
                                    <div class="empty-icon"><i class="fas fa-video"></i></div>
                                    <p>No online classes scheduled.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="panel fade-in" style="margin: 0;">
                            <div class="panel-header">
                                <h3 class="panel-title"><i class="fas fa-play-circle me-2" style="color: var(--pink);"></i>Recorded Classes</h3>
                                @if(isset($recordedClasses) && count($recordedClasses) > 0)
                                    <span class="panel-link" style="cursor:default;">{{ count($recordedClasses) }}</span>
                                @endif
                            </div>
                            @if(isset($recordedClasses) && count($recordedClasses) > 0)
                                <div class="class-list">
                                    @foreach($recordedClasses as $recordedClass)
                                        <div class="class-row">
                                            <div class="class-thumb rec">
                                                @if($recordedClass->class_icon)
                                                    <img src="{{ config('constants.recorded_class_icon').$recordedClass->class_icon }}" alt="{{ $recordedClass->title }}">
                                                @else
                                                    <i class="fas fa-play-circle"></i>
                                                @endif
                                            </div>
                                            <div class="class-info">
                                                <h5 class="class-name">{{ $recordedClass->title }}</h5>
                                                <div class="class-meta">
                                                    <span><i class="fas fa-user"></i>{{ $recordedClass->class_by }}</span>
                                                    @if($recordedClass->duration)
                                                        <span><i class="fas fa-hourglass-half"></i>{{ $recordedClass->duration }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('student.recorded-classes', $recordedClass->course_id) }}" class="btn-watch">
                                                <i class="fas fa-play"></i> Watch
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty">
                                    <div class="empty-icon"><i class="fas fa-play-circle"></i></div>
                                    <p>No recorded classes yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>{{-- /dash-content --}}
        </div>{{-- /dash-wrapper --}}
    </div>{{-- /dash-shell --}}


    {{-- ===== COURSE CHOICE MODAL ===== --}}
    <div class="course-choice-modal" id="courseChoiceModal" aria-hidden="true">
        <div class="course-choice-backdrop" onclick="closeCourseChoice()"></div>
        <div class="course-choice-dialog" role="dialog" aria-modal="true" aria-labelledby="ccmTitle">
            <button type="button" class="ccm-close" onclick="closeCourseChoice()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="ccm-title" id="ccmTitle">What would you like to open?</h3>
            <p class="ccm-sub" id="ccmCourseName">&nbsp;</p>
            <div class="ccm-options">
                <a href="#" class="ccm-option" id="ccmClasses">
                    <div class="ccm-icon ccm-icon-classes"><i class="fas fa-play-circle"></i></div>
                    <div class="ccm-label">Classes</div>
                    <div class="ccm-hint">Videos &amp; lessons</div>
                </a>
                <a href="#" class="ccm-option" id="ccmMockTest">
                    <div class="ccm-icon ccm-icon-tests"><i class="fas fa-file-alt"></i></div>
                    <div class="ccm-label">Mock Test</div>
                    <div class="ccm-hint">Practice exams</div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function checkCourseStatus(status, url) {
        if (status === 'Expired') {
            Swal.fire({
                title: 'Course Expired!',
                text: 'Sorry! The course is expired. Thank You!',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        } else {
            window.location.href = url;
        }
    }

    /*// ===== Course Choice Modal =====
    function openCourseChoice(courseId, courseName, classesUrl, mockTestUrl) {
        const modal = document.getElementById('courseChoiceModal');
        document.getElementById('ccmCourseName').textContent = courseName;
        document.getElementById('ccmClasses').setAttribute('href', classesUrl);
        document.getElementById('ccmMockTest').setAttribute('href', mockTestUrl);
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    */

    function closeCourseChoice() {
        const modal = document.getElementById('courseChoiceModal');
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('courseChoiceModal');
            if (modal && modal.classList.contains('open')) closeCourseChoice();
        }
    });

    // Keyboard activation on continue-learning cards
    document.addEventListener('keydown', function(e) {
        if ((e.key === 'Enter' || e.key === ' ') && e.target.classList && e.target.classList.contains('cl-card')) {
            e.preventDefault();
            e.target.click();
        }
    });
</script>
@endpush
