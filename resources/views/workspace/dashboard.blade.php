php artisan migrate:fresh --seed<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JMS Operasional | Manajemen Dokumen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --ink: #20312f;
            --muted: #7b8781;
            --line: #e1e6df;
            --paper: #f2f3ef;
            --white: #fff;
            --forest: #173a36;
            --forest-deep: #102c29;
            --mint: #dce9de;
            --green: #327966;
            --coral: #d57b5b;
            --gold: #d5ad69;
            --font-body: 'Manrope', 'Segoe UI', sans-serif;
            --font-display: 'DM Serif Display', Georgia, serif;
            --font-mono: 'DM Mono', Consolas, monospace;
        }

        * { box-sizing: border-box; }
        html { min-width: 320px; background: var(--paper); }
        body { margin: 0; color: var(--ink); font-family: var(--font-body); font-size: 13px; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        button, summary, select { cursor: pointer; }
        ::selection { color: var(--white); background: var(--green); }

        .eyebrow, .metric-label, .nav-caption, .sidebar-note p, .mono-note, .topbar-meta, .data-table th, .page-footer, .intro-foot, .login-intro .eyebrow { font-family: var(--font-mono); font-size: 9px; font-weight: 500; letter-spacing: 0; }
        .eyebrow { margin: 0 0 11px; color: var(--green); }
        .brand { display: inline-flex; align-items: center; width: fit-content; color: var(--ink); }
        .brand strong, .brand small { display: block; }
        .brand strong { font-size: 13px; font-weight: 800; }
        .brand small { margin-top: 2px; font-family: var(--font-mono); font-size: 8px; }
        .brand-light { color: #f6f4e9; }
        .button { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; gap: 12px; border: 1px solid transparent; padding: 0 16px; font-size: 11px; font-weight: 700; transition: background .18s ease, color .18s ease, transform .18s ease; }
        .button:hover { transform: translateY(-1px); }
        .button-primary { color: #fff; background: var(--forest); }
        .button-primary:hover { background: var(--forest-deep); }
        .button-secondary { min-height: 36px; color: var(--ink); border-color: var(--line); background: var(--white); }
        .button-secondary:hover, .button-outline:hover { border-color: var(--green); }
        .button-outline { color: var(--forest); border-color: #bbc9c1; background: transparent; }
        .button-wide { width: 100%; min-height: 49px; justify-content: space-between; padding: 0 18px; }
        .button-plus { font-size: 18px; font-weight: 400; line-height: 1; }
        .icon-button { display: inline-grid; width: 34px; height: 34px; place-items: center; border: 1px solid transparent; color: inherit; background: transparent; font-size: 19px; }
        .icon-button:hover { border-color: var(--line); background: #fff; }
        .danger-action { color: #b7614f; }
        .danger-action:disabled { cursor: not-allowed; opacity: .32; }
        .danger-action:disabled:hover { border-color: transparent; background: transparent; }
        .text-link { display: inline-flex; align-items: center; gap: 9px; color: var(--green); font-size: 10px; font-weight: 800; }
        .text-link:hover { color: var(--coral); }

        .workspace-page { min-height: 100vh; }
        .sidebar { position: fixed; z-index: 5; inset: 0 auto 0 0; display: flex; width: 245px; flex-direction: column; padding: 28px 20px 17px; color: #eaf0e7; background: var(--forest); }
        .sidebar > .brand { margin: 0 0 61px 5px; }
        .nav-caption { margin: 0 0 13px 9px; color: #88a499; }
        .primary-nav { display: flex; flex-direction: column; gap: 3px; }
        .nav-group { display: flex; flex-direction: column; gap: 4px; }
        .nav-link { display: flex; min-height: 45px; align-items: center; gap: 12px; border-left: 2px solid transparent; padding: 0 10px; color: #c5d1c8; font-size: 10px; font-weight: 600; transition: color .18s ease, background .18s ease; }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.06); }
        .nav-link.is-active { border-color: #e3b775; color: #fff; background: rgba(255,255,255,.09); }
        .nav-number { width: 20px; color: #86a197; font-family: var(--font-mono); font-size: 9px; }
        .nav-link.is-active .nav-number { color: #e3b775; }
        .sub-nav { display: none; margin: 0 0 4px 22px; padding-left: 13px; border-left: 1px solid rgba(255,255,255,.15); }
        .nav-group.is-open .sub-nav { display: flex; flex-direction: column; gap: 2px; }
        .sub-nav-link { display: block; padding: 8px 10px; color: #c7d4cb; font-size: 9px; font-weight: 600; }
        .sub-nav-link:hover, .sub-nav-link.is-active { color: #fff; background: rgba(255,255,255,.06); }
        .nav-count { margin-left: auto; color: #e3b775; font-family: var(--font-mono); font-size: 9px; }
        .sidebar-note { margin: auto 4px 25px; padding: 0 9px 20px; color: #9bb0a5; }
        .note-rule { display: block; width: 27px; height: 2px; margin-bottom: 13px; background: var(--coral); }
        .sidebar-note p { margin: 0 0 10px; font-size: 8px; line-height: 1.8; }
        .mono-note { color: #789388; font-size: 8px; }
        .sidebar-account { display: flex; align-items: center; gap: 10px; border-top: 1px solid rgba(255,255,255,.13); padding: 15px 2px 0; }
        .user-avatar { display: grid; width: 31px; height: 31px; flex: 0 0 auto; place-items: center; color: var(--forest); background: #d9b77e; font-family: var(--font-display); font-size: 18px; }
        .account-copy { min-width: 0; flex: 1; }
        .account-copy strong, .account-copy small { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .account-copy strong { font-size: 10px; }
        .account-copy small { margin-top: 3px; color: #9bb0a5; font-size: 9px; }
        .logout-button { color: #c8d5ca; }
        .workspace-main { min-height: 100vh; margin-left: 245px; }
        .topbar { position: sticky; z-index: 3; top: 0; display: flex; min-height: 62px; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding: 0 42px; background: rgba(247, 248, 244, .94); backdrop-filter: blur(12px); }
        .breadcrumb { display: flex; align-items: center; gap: 10px; color: #69786f; font-size: 10px; }
        .breadcrumb span:last-child { color: var(--ink); font-weight: 800; }
        .breadcrumb-slash { color: #b6c0b7; }
        .topbar-meta { display: flex; align-items: center; gap: 8px; color: #7e8a81; font-size: 8px; }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: #5a9b76; box-shadow: 0 0 0 3px #dce9de; }
        .topbar-divider { width: 1px; height: 12px; margin: 0 5px; background: var(--line); }
        .mobile-menu-toggle, .sidebar-scrim { display: none; }
        .page-content { width: min(100%, 1440px); margin: 0 auto; padding: 42px 42px 24px; animation: page-enter .32s ease both; }
        @keyframes page-enter { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin: 0 0 28px; }
        .page-heading h1 { margin: 0; font-family: var(--font-display); font-size: 42px; font-weight: 400; line-height: 1.1; }
        .heading-description { margin: 10px 0 0; color: var(--muted); font-size: 11px; line-height: 1.7; }
        .overview-heading { margin-bottom: 30px; }
        .overview-heading h1 { font-size: 38px; }
        .metric-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); border: 1px solid var(--line); background: var(--white); }
        .metric-card { position: relative; display: flex; min-height: 150px; flex-direction: column; justify-content: space-between; border-right: 1px solid var(--line); padding: 19px 18px 16px; transition: background .2s ease; }
        .metric-card:last-child { border-right: 0; }
        .metric-card:hover { background: #f7faf6; }
        .metric-featured { color: #eef3e9; background: var(--forest); }
        .metric-featured:hover { background: var(--forest-deep); }
        .metric-label { color: #84928a; font-size: 8px; }
        .metric-featured .metric-label { color: #a9bcb0; }
        .metric-value { font-family: var(--font-display); font-size: 48px; font-weight: 400; line-height: 1; }
        .metric-foot { display: flex; justify-content: space-between; color: #89968e; font-size: 9px; }
        .metric-featured .metric-foot { color: #b6c8ba; }
        .metric-foot span { color: var(--coral); font-size: 13px; }
        .overview-lower { display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(230px, .82fr); gap: 28px; margin-top: 34px; }
        .content-section { min-width: 0; }
        .section-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 17px; }
        .section-heading .eyebrow { margin-bottom: 6px; }
        .section-heading h2, .quick-panel h2 { margin: 0; font-family: var(--font-display); font-size: 25px; font-weight: 400; }
        .recent-list { border-top: 1px solid var(--line); }
        .recent-row { display: flex; min-height: 69px; align-items: center; gap: 16px; border-bottom: 1px solid var(--line); transition: background .18s ease; }
        .recent-row:hover { background: rgba(255,255,255,.55); }
        .recent-date { width: 44px; flex: 0 0 auto; color: var(--muted); font-family: var(--font-mono); font-size: 9px; }
        .recent-copy { min-width: 0; flex: 1; }
        .recent-copy strong, .recent-copy small { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .recent-copy strong { font-size: 10px; }
        .recent-copy small { margin-top: 5px; color: var(--muted); font-size: 9px; }
        .status-pill { display: inline-flex; width: fit-content; align-items: center; border: 1px solid transparent; padding: 5px 8px; font-size: 8px; font-weight: 700; white-space: nowrap; }
        .status-draft { border-color: #ecd7b3; color: #8b6733; background: #fbf4e8; }
        .status-review { border-color: #d2dfd7; color: #376d59; background: #edf5ee; }
        .status-final { border-color: #c8d5d2; color: #355d61; background: #eaf1ef; }
        .quick-panel { border-left: 1px solid var(--line); padding: 7px 0 0 24px; }
        .quick-panel .eyebrow { margin-bottom: 8px; }
        .quick-panel h2 { margin-bottom: 18px; }
        .quick-panel > a { display: grid; min-height: 47px; grid-template-columns: 27px 1fr 16px; align-items: center; gap: 8px; border-top: 1px solid var(--line); font-size: 9px; font-weight: 700; }
        .quick-panel > a:hover { color: var(--green); }
        .quick-number { color: #9aa69e; font-family: var(--font-mono); font-size: 8px; }
        .quick-panel > a > span:last-child { color: var(--coral); font-size: 13px; }
        .empty-state { display: flex; min-height: 105px; align-items: center; gap: 16px; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding: 17px 4px; }
        .empty-index { color: #a6b1a8; font-family: var(--font-mono); font-size: 10px; }
        .empty-state > div { flex: 1; }
        .empty-state strong { font-size: 10px; }
        .empty-state p { margin: 5px 0 0; color: var(--muted); font-size: 9px; }
        .page-footer { display: flex; justify-content: space-between; margin-top: 49px; border-top: 1px solid var(--line); padding-top: 13px; color: #9ba69d; font-size: 8px; }
        .form-disclosure { border: 1px solid var(--line); background: #fff; }
        .form-disclosure > summary { display: flex; min-height: 69px; align-items: center; justify-content: space-between; gap: 15px; list-style: none; padding: 12px 18px; }
        .form-disclosure > summary::-webkit-details-marker { display: none; }
        .form-disclosure > summary > span:first-child strong, .form-disclosure > summary > span:first-child small { display: block; }
        .form-disclosure > summary strong { font-size: 11px; }
        .form-disclosure > summary small { margin-top: 5px; color: var(--muted); font-size: 9px; }
        .summary-mark { display: grid; width: 26px; height: 26px; flex: 0 0 auto; place-items: center; border: 1px solid var(--line); color: var(--green); font-size: 16px; transition: transform .2s ease; }
        .form-disclosure[open] .summary-mark { transform: rotate(45deg); }
        .form-panel { margin: -3px 0 27px; }
        .form-panel[open] > summary { border-bottom: 1px solid var(--line); }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 15px; padding: 19px; }
        .field { display: flex; min-width: 0; flex-direction: column; gap: 7px; }
        .field-wide { grid-column: 1 / -1; }
        .field input, .field select, .field textarea { font-weight: 500; resize: vertical; }
        .field textarea { min-height: 80px; padding: 10px 12px; line-height: 1.7; }
        .field-hint { color: var(--muted); font-size: 9px; font-weight: 500; line-height: 1.6; }
        .form-actions { display: flex; justify-content: flex-end; border-top: 1px solid #edf0eb; padding-top: 14px; }
        .compact-disclosure { border: 0; background: transparent; }
        .compact-disclosure summary { min-height: 42px; padding: 0; }
        .compact-disclosure summary::marker { content: ''; }
        .compact-disclosure summary .button { pointer-events: none; }
        .table-section { margin-top: 31px; }
        .inline-search { display: flex; width: min(100%, 250px); align-items: center; border-bottom: 1px solid #bfc9c0; }
        .inline-search input { min-height: 35px; border: 0; padding: 0 5px; background: transparent; }
        .inline-search .icon-button { width: 31px; height: 31px; color: var(--green); }
        .table-scroll { overflow-x: auto; border-top: 1px solid var(--line); }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th { height: 36px; color: #8b988e; font-size: 8px; font-weight: 500; white-space: nowrap; }
        .data-table td { min-height: 60px; border-top: 1px solid var(--line); padding: 12px 10px 12px 0; color: #56645c; font-size: 10px; vertical-align: middle; }
        .data-table td:last-child, .data-table th:last-child { padding-right: 0; }
        .entity-cell { display: flex; align-items: center; gap: 11px; min-width: 210px; }
        .entity-mark { display: grid; width: 31px; height: 34px; flex: 0 0 auto; place-items: center; color: var(--forest); background: var(--mint); font-family: var(--font-display); font-size: 17px; }
        .entity-cell strong, .entity-cell small { display: block; }
        .entity-cell strong { color: var(--ink); font-size: 10px; }
        .entity-cell small, .table-subtext { margin-top: 4px; color: var(--muted); font-size: 8px; }
        .table-count { color: var(--green); font-size: 9px; font-weight: 700; white-space: nowrap; }
        .table-count:hover { text-decoration: underline; }
        .action-cell { width: 43px; text-align: right; }
        .action-cell form { display: flex; justify-content: flex-end; }
        .action-cell .icon-button { width: 30px; height: 30px; }
        .filter-bar { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px; margin: 0 0 18px; border: 1px solid var(--line); padding: 12px; background: #fff; }
        .filter-field { display: flex; min-width: 160px; flex-direction: column; gap: 5px; color: var(--muted); font-size: 8px; font-weight: 700; }
        .filter-field select { min-height: 35px; font-size: 10px; }
        .filter-bar .button { min-height: 35px; }
        .letter-table td { padding-top: 14px; padding-bottom: 14px; }
        .table-action-form { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
        .table-action-form .status-select { min-width: 110px; height: 36px; border: 1px solid var(--line); border-radius: 8px; background: white; padding: 0 10px; font: inherit; color: var(--ink); }
        .table-action-form .table-action-file { max-width: 180px; }
        .table-action-form .action-submit { min-width: 72px; }
        .table-delete-form { margin-top: 8px; display: flex; justify-content: flex-end; }
        .danger-link { color: #b7614f; }
        .checklist-hospital { display: grid; grid-template-columns: 150px 1fr; gap: 9px 14px; margin: 0 0 20px; border: 1px solid var(--line); padding: 16px; background: #fff; }
        .checklist-hospital strong, .checklist-group-heading { color: #718077; font-family: var(--font-mono); font-size: 9px; font-weight: 500; }
        .checklist-table { min-width: 760px; }
        .checklist-table th, .checklist-table td { padding-right: 12px; }
        .checklist-table th { text-align: center; }
        .checklist-table th:nth-child(1), .checklist-table th:nth-child(2), .checklist-table th:nth-last-child(-n+2) { text-align: left; }
        .checklist-group-heading { text-align: center !important; }
        .checklist-indicator { display: grid; width: 28px; height: 28px; place-items: center; margin: 0 auto; color: var(--green); font-size: 17px; font-weight: 800; }
        .checklist-notes { width: 100%; min-width: 170px; min-height: 34px; border: 1px solid var(--line); padding: 6px 8px; font-size: 10px; }
        .checklist-actions { min-width: 105px; }
        .checklist-actions form { display: inline-flex; align-items: center; gap: 8px; }
        .checklist-actions .button { min-height: 32px; padding: 0 9px; }
        .document-number { display: block; color: var(--ink); font-family: var(--font-mono); font-size: 9px; white-space: nowrap; }
        .table-subtext { display: block; }
        .pagination-wrap { padding: 15px 0; }
        .pagination-wrap nav { display: flex; align-items: center; justify-content: space-between; }
        .pagination-wrap nav > div:first-child { color: var(--muted); font-size: 9px; }
        .pagination-wrap nav > div:last-child > span, .pagination-wrap nav > div:last-child > a { display: inline-flex; gap: 5px; }
        .pagination-wrap a, .pagination-wrap span[aria-current] span, .pagination-wrap span[aria-disabled] span { display: grid; min-width: 28px; height: 28px; place-items: center; border: 1px solid var(--line); color: var(--green); background: #fff; font-size: 9px; }
        .pagination-wrap span[aria-current] span { color: #fff; background: var(--forest); }
        .flash-message { margin: 0 0 18px; border-left: 3px solid; padding: 12px 14px; font-size: 10px; }
        .flash-success { border-color: #4c9172; color: #32674f; background: #e8f2e9; }
        .flash-error { border-color: #c56d59; color: #9e4d40; background: #faece7; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%); }

        @media (max-width: 1100px) {
            .sidebar { width: 220px; }
            .workspace-main { margin-left: 220px; }
            .topbar { padding-right: 28px; padding-left: 28px; }
            .page-content { padding-right: 28px; padding-left: 28px; }
            .metric-card { min-height: 138px; padding-right: 12px; padding-left: 12px; }
            .metric-value { font-size: 42px; }
        }
        @media (max-width: 850px) {
            .login-layout { grid-template-columns: .85fr 1.15fr; }
            .login-intro { padding-right: 30px; padding-left: 30px; }
            .login-panel { padding: 32px; }
            .sidebar { z-index: 8; width: 260px; transform: translateX(-100%); transition: transform .22s ease; }
            .workspace-page.nav-open .sidebar { transform: translateX(0); }
            .sidebar-scrim { position: fixed; z-index: 7; inset: 0; border: 0; background: rgba(16,44,41,.4); }
            .workspace-page.nav-open .sidebar-scrim { display: block; }
            .workspace-main { margin-left: 0; }
            .topbar { justify-content: flex-start; gap: 14px; padding: 0 22px; }
            .mobile-menu-toggle { display: flex; width: 34px; flex-direction: column; align-items: center; justify-content: center; gap: 5px; }
            .mobile-menu-toggle span { width: 15px; height: 1px; background: var(--ink); }
            .topbar-meta { margin-left: auto; }
            .page-content { padding: 31px 22px 22px; }
            .overview-lower { grid-template-columns: minmax(0, 1.5fr) minmax(190px, .8fr); gap: 20px; }
        }
        @media (max-width: 620px) {
            .login-layout { display: block; background: #f7f7f3; }
            .login-intro { min-height: 250px; padding: 21px 23px 18px; }
            .login-intro > .brand { display: none; }
            .intro-copy { margin: 0; padding: 27px 0 29px; }
            .intro-copy .eyebrow { margin-bottom: 8px; font-size: 8px; }
            .intro-copy h1 { font-size: 48px; }
            .intro-description { max-width: 305px; margin-top: 12px; font-size: 10px; line-height: 1.6; }
            .intro-foot { font-size: 7px; }
            .login-panel { min-height: auto; padding: 31px 24px 38px; }
            .mobile-brand { display: inline-flex; margin-bottom: 24px; }
            .login-form-wrap h2 { font-size: 31px; }
            .form-lead { margin-bottom: 22px; }
            .topbar { min-height: 55px; padding: 0 15px; }
            .topbar-meta { gap: 6px; font-size: 7px; }
            .topbar-meta > span:nth-last-child(-n+2) { display: none; }
            .breadcrumb { gap: 7px; font-size: 9px; }
            .page-content { padding: 26px 15px 18px; }
            .page-heading { align-items: flex-start; flex-direction: column; gap: 16px; margin-bottom: 21px; }
            .page-heading h1, .overview-heading h1 { font-size: 34px; }
            .heading-description { max-width: 390px; font-size: 10px; }
            .overview-heading .button { align-self: stretch; }
            .metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .metric-card { min-height: 125px; border-bottom: 1px solid var(--line); padding: 14px; }
            .metric-card:nth-child(2) { border-right: 0; }
            .metric-card:nth-last-child(-n+2) { border-bottom: 0; }
            .metric-value { font-size: 39px; }
            .overview-lower { grid-template-columns: 1fr; gap: 28px; margin-top: 28px; }
            .quick-panel { border-top: 1px solid var(--line); border-left: 0; padding: 19px 0 0; }
            .quick-panel h2 { font-size: 23px; }
            .recent-row { gap: 10px; }
            .recent-date { width: 36px; }
            .recent-copy strong { max-width: 175px; }
            .recent-row .status-pill { padding: 4px 5px; font-size: 7px; }
            .section-heading { align-items: flex-start; flex-direction: column; gap: 12px; }
            .section-heading h2 { font-size: 23px; }
            .inline-search { width: 100%; }
            .form-grid { grid-template-columns: 1fr; padding: 14px; }
            .field-wide { grid-column: auto; }
            .form-actions { justify-content: stretch; }
            .form-actions .button { width: 100%; }
            .compact-disclosure { align-self: stretch; }
            .compact-disclosure summary { justify-content: flex-start; }
            .filter-bar { align-items: stretch; flex-direction: column; }
            .filter-field { min-width: 0; }
            .filter-bar .button { width: 100%; }
            .filter-bar .text-link { justify-content: center; padding: 6px; }
            .empty-state { align-items: flex-start; flex-wrap: wrap; }
            .empty-state .text-link { margin-left: 27px; }
            .page-footer { gap: 10px; font-size: 7px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        body { font-size: 17px; }
        .eyebrow, .nav-caption, .sidebar-note p, .mono-note, .topbar-meta, .data-table th, .page-footer { font-size: 13px; }
        .brand strong { font-size: 17px; }
        .brand small { font-size: 12px; }
        .button { font-size: 15px; }
        .text-link { font-size: 14px; }
        .nav-link { font-size: 15px; }
        .nav-number, .nav-count, .mono-note { font-size: 12px; }
        .account-copy strong { font-size: 15px; }
        .account-copy small { font-size: 13px; }
        .breadcrumb { font-size: 14px; }
        .heading-description { font-size: 16px; }
        .metric-label { font-size: 12px; }
        .metric-foot { font-size: 14px; }
        .recent-date, .recent-copy small { font-size: 13px; }
        .recent-copy strong, .empty-state strong { font-size: 15px; }
        .status-pill, .recent-row .status-pill { font-size: 13px; }
        .quick-panel > a { font-size: 14px; }
        .quick-number { font-size: 12px; }
        .empty-index { font-size: 14px; }
        .empty-state p { font-size: 13px; }
        .page-footer { font-size: 12px; }
        .form-disclosure > summary strong { font-size: 15px; }
        .form-disclosure > summary small, .field-hint { font-size: 13px; }
        .field, .filter-field { font-size: 15px; }
        .field input, .field select, .field textarea, .filter-field select, .inline-search input { font-size: 16px; }
        .data-table th { font-size: 12px; }
        .data-table td { font-size: 15px; }
        .entity-cell strong { font-size: 15px; }
        .entity-cell small, .table-subtext { font-size: 13px; }
        .table-count { font-size: 14px; }
        .document-number { font-size: 13px; }
        .pagination-wrap nav > div:first-child, .pagination-wrap a, .pagination-wrap span[aria-current] span, .pagination-wrap span[aria-disabled] span { font-size: 13px; }
        .flash-message { font-size: 15px; }
    </style>
</head>
<body class="workspace-page">
    @php
        $pageTitles = [
            'overview' => 'Ringkasan',
            'hospitals' => 'Rumah Sakit',
            'administration' => 'Surat RS',
            'completeness' => 'Kelengkapan Surat',
            'internal' => 'Surat Internal',
        ];
    @endphp
    <button class="sidebar-scrim" type="button" aria-label="Tutup navigasi"></button>
    <aside class="sidebar">
        <a class="brand brand-light" href="{{ route('workspace.index') }}">
            <span><strong>JMS</strong><small>OPERASIONAL</small></span>
        </a>
        <p class="nav-caption">RUANG KERJA</p>
        <nav class="primary-nav" aria-label="Navigasi utama">
            <a class="nav-link {{ $section === 'overview' ? 'is-active' : '' }}" href="{{ route('workspace.index', ['section' => 'overview']) }}"><span class="nav-number">00</span><span>Ringkasan</span></a>
            <a class="nav-link {{ $section === 'hospitals' ? 'is-active' : '' }}" href="{{ route('workspace.index', ['section' => 'hospitals']) }}"><span class="nav-number">01</span><span>Rumah Sakit</span><span class="nav-count">{{ $hospitalCount }}</span></a>
            <div class="nav-group {{ in_array($section, ['administration', 'completeness'], true) ? 'is-open' : '' }}">
                <a class="nav-link {{ $section === 'administration' ? 'is-active' : '' }}" href="{{ route('workspace.index', ['section' => 'administration']) }}"><span class="nav-number">02</span><span>Surat RS</span></a>
                <div class="sub-nav">
                    <a class="sub-nav-link {{ $section === 'completeness' ? 'is-active' : '' }}" href="{{ route('workspace.index', ['section' => 'completeness']) }}">Kelengkapan Surat</a>
                </div>
            </div>
            <a class="nav-link {{ $section === 'internal' ? 'is-active' : '' }}" href="{{ route('workspace.index', ['section' => 'internal']) }}"><span class="nav-number">03</span><span>Surat Internal</span></a>
        </nav>
        <div class="sidebar-note">
            <span class="note-rule"></span>
            <p>KESEKRETARIATAN<br>KERJA SAMA OPERASIONAL</p>
            <span class="mono-note">JMS / DOCS / 26</span>
        </div>
        <div class="sidebar-account">
            <span class="user-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span>
            <span class="account-copy"><strong>{{ auth()->user()->name }}</strong><small>Administrator</small></span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="icon-button logout-button" type="submit" aria-label="Keluar" title="Keluar">&#8599;</button></form>
        </div>
    </aside>

    <main class="workspace-main">
        <header class="topbar">
            <button class="mobile-menu-toggle icon-button" type="button" aria-label="Buka navigasi"><span></span><span></span></button>
            <div class="breadcrumb"><span>JMS</span><span class="breadcrumb-slash">/</span><span>{{ $pageTitles[$section] }}</span></div>
            <div class="topbar-meta"><span class="status-dot"></span><span>RUANG KERJA AKTIF</span><span class="topbar-divider"></span><span>{{ now()->locale('id')->translatedFormat('d M Y') }}</span></div>
        </header>

        <div class="page-content">
            @if (session('success'))
                <div class="flash-message flash-success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash-message flash-error" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="flash-message flash-error" role="alert">Periksa kembali isian: {{ $errors->first() }}</div>
            @endif

            @if ($section === 'overview')
                <section class="page-heading overview-heading">
                    <div><p class="eyebrow">{{ \Illuminate\Support\Str::upper(now()->locale('id')->translatedFormat('l, d F Y')) }}</p><h1>Selamat datang, {{ auth()->user()->name }}.</h1><p class="heading-description">Pantau kerja sama operasional dan kelola dokumen dalam satu tempat.</p></div>
                    <a class="button button-primary" href="{{ route('workspace.index', ['section' => 'administration']) }}"><span class="button-plus">+</span> Unggah PDF</a>
                </section>
                <section class="metric-grid" aria-label="Ringkasan data">
                    <a class="metric-card metric-featured" href="{{ route('workspace.index', ['section' => 'hospitals']) }}"><span class="metric-label">RUMAH SAKIT MITRA</span><strong class="metric-value">{{ str_pad((string) $hospitalCount, 2, '0', STR_PAD_LEFT) }}</strong><span class="metric-foot">Terdaftar dalam sistem <span>&#8594;</span></span></a>
                    <a class="metric-card" href="{{ route('workspace.index', ['section' => 'administration', 'status' => 'draft']) }}"><span class="metric-label">DRAFT AKTIF</span><strong class="metric-value">{{ str_pad((string) $draftCount, 2, '0', STR_PAD_LEFT) }}</strong><span class="metric-foot">Menunggu dilengkapi <span>&#8594;</span></span></a>
                    <a class="metric-card" href="{{ route('workspace.index', ['section' => 'administration', 'status' => 'review']) }}"><span class="metric-label">DALAM TINJAUAN</span><strong class="metric-value">{{ str_pad((string) $reviewCount, 2, '0', STR_PAD_LEFT) }}</strong><span class="metric-foot">Menunggu tindak lanjut <span>&#8594;</span></span></a>
                    <a class="metric-card" href="{{ route('workspace.index', ['section' => 'administration']) }}"><span class="metric-label">ARSIP PDF</span><strong class="metric-value">{{ str_pad((string) $pdfCount, 2, '0', STR_PAD_LEFT) }}</strong><span class="metric-foot">Surat tersimpan <span>&#8594;</span></span></a>
                </section>
                <section class="overview-lower">
                    <div class="content-section recent-section">
                        <div class="section-heading"><div><p class="eyebrow">AKTIVITAS TERBARU</p><h2>Surat administrasi</h2></div><a class="text-link" href="{{ route('workspace.index', ['section' => 'administration']) }}">Lihat semua <span>&#8594;</span></a></div>
                        @if ($recentLetters->isEmpty())
                            <div class="empty-state"><span class="empty-index">01</span><div><strong>Belum ada surat</strong><p>Unggah PDF surat administrasi untuk mulai mengisi arsip.</p></div><a class="text-link" href="{{ route('workspace.index', ['section' => 'administration']) }}">Mulai unggah <span>&#8594;</span></a></div>
                        @else
                            <div class="recent-list">
                                @foreach ($recentLetters as $letter)
                                    <a class="recent-row" href="{{ route('workspace.index', ['section' => 'administration']) }}"><span class="recent-date">{{ $letter->created_at->format('d M') }}</span><span class="recent-copy"><strong>{{ $letter->document_name ?: $letter->document_number }}</strong><small>{{ $letter->hospital->name }}</small></span><span class="status-pill status-{{ $letter->status }}">{{ ['draft' => 'Draft', 'review' => 'Ditinjau', 'final' => 'Final'][$letter->status] ?? ucfirst($letter->status) }}</span></a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <aside class="quick-panel"><p class="eyebrow">AKSES CEPAT</p><h2>Mulai dari sini.</h2><a href="{{ route('workspace.index', ['section' => 'hospitals']) }}"><span class="quick-number">01</span><span>Tambah rumah sakit</span><span>&#8599;</span></a><a href="{{ route('workspace.index', ['section' => 'administration']) }}"><span class="quick-number">02</span><span>Lihat arsip PDF</span><span>&#8599;</span></a><a href="{{ route('workspace.index', ['section' => 'administration']) }}"><span class="quick-number">03</span><span>Unggah surat PDF</span><span>&#8599;</span></a></aside>
                </section>
            @elseif ($section === 'hospitals')
                <section class="page-heading">
                    <div><p class="eyebrow">DIREKTORI MITRA</p><h1>Rumah Sakit</h1><p class="heading-description">Kelola data mitra dan lihat jumlah dokumen pada setiap rumah sakit.</p></div>
                    <button class="button button-primary" type="button" data-open-details="hospital-form"><span class="button-plus">+</span> Tambah rumah sakit</button>
                </section>
                <details class="form-disclosure form-panel" id="hospital-form">
                    <summary><span><strong>Tambah rumah sakit</strong><small>Data ini digunakan saat membuat surat kerja sama.</small></span><span class="summary-mark">+</span></summary>
                    <form method="POST" action="{{ route('hospitals.store') }}" class="form-grid">
                        @csrf
                        <label class="field field-wide">Nama rumah sakit<input name="name" value="{{ old('name') }}" maxlength="180" required placeholder="Contoh: RS Mitra Sehat"></label>
                        <label class="field">Kota<input name="city" value="{{ old('city') }}" maxlength="120" placeholder="Jakarta"></label>
                        <label class="field">Nama kontak<input name="contact_person" value="{{ old('contact_person') }}" maxlength="120" placeholder="Nama PIC"></label>
                        <label class="field">Email<input type="email" name="email" value="{{ old('email') }}" maxlength="180" placeholder="kontak@rumahsakit.id"></label>
                        <label class="field">Nomor telepon<input name="phone" value="{{ old('phone') }}" maxlength="40" placeholder="021 ..."></label>
                        <label class="field field-wide">Alamat<textarea name="address" rows="2" maxlength="1000" placeholder="Alamat lengkap rumah sakit"></textarea></label>
                        <div class="form-actions field-wide"><button class="button button-primary" type="submit">Simpan rumah sakit <span>&#8594;</span></button></div>
                    </form>
                </details>
                <section class="content-section table-section">
                    <div class="section-heading"><div><p class="eyebrow">DAFTAR MITRA</p><h2>{{ $hospitalCount }} rumah sakit terdaftar</h2></div><form class="inline-search" method="GET" action="{{ route('workspace.index') }}"><input type="hidden" name="section" value="hospitals"><label class="sr-only" for="hospital-search">Cari rumah sakit</label><input id="hospital-search" name="search" value="{{ $search }}" placeholder="Cari nama atau kota"><button class="icon-button" aria-label="Cari" type="submit">&#8594;</button></form></div>
                    @if ($hospitals->isEmpty())
                        <div class="empty-state"><span class="empty-index">--</span><div><strong>Belum ada rumah sakit di sini</strong><p>Tambahkan mitra pertama untuk mulai menyiapkan draft.</p></div></div>
                    @else
                        <div class="table-scroll"><table class="data-table"><thead><tr><th>RUMAH SAKIT</th><th>LOKASI</th><th>KONTAK</th><th>SURAT</th><th><span class="sr-only">Aksi</span></th></tr></thead><tbody>
                            @foreach ($hospitals as $hospital)
                                <tr><td><div class="entity-cell"><span class="entity-mark">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($hospital->name, 0, 1)) }}</span><span><strong>{{ $hospital->name }}</strong><small>{{ $hospital->email ?: 'Email belum ditambahkan' }}</small></span></div></td><td>{{ $hospital->city ?: '—' }}</td><td>{{ $hospital->contact_person ?: $hospital->phone ?: '—' }}</td><td><a class="table-count" href="{{ route('workspace.index', ['section' => 'administration', 'hospital_id' => $hospital->id]) }}">{{ $hospital->administration_letters_count }} dokumen</a></td><td class="action-cell"><form method="POST" action="{{ route('hospitals.destroy', $hospital) }}" data-confirm="Hapus {{ $hospital->name }} dari daftar rumah sakit?">@csrf @method('DELETE')<button class="icon-button danger-action" type="submit" aria-label="Hapus {{ $hospital->name }}" title="Hapus rumah sakit" @disabled($hospital->administration_letters_count > 0)>&#215;</button></form></td></tr>
                            @endforeach
                        </tbody></table></div>
                    @endif
                </section>
            @elseif ($section === 'administration')
                <section class="page-heading">
                    <div><p class="eyebrow">ARSIP DOKUMEN</p><h1>Surat RS</h1><p class="heading-description">Unggah PDF surat, pantau status, dan cari dokumen per rumah sakit.</p></div>
                    <a class="button button-primary" href="#new-administration"><span class="button-plus">+</span> Unggah PDF</a>
                </section>
                <details class="form-disclosure form-panel" id="new-administration">
                    <summary><span><strong>Unggah surat PDF</strong><small>Lengkapi data surat dan pilih file PDF dari perangkat.</small></span><span class="summary-mark">+</span></summary>
                    <form method="POST" action="{{ route('administration.store') }}" class="form-grid" enctype="multipart/form-data" id="administration-form">
                        @csrf
                        <label class="field">Rumah sakit<select name="hospital_id" id="administration-hospital-select" required><option value="">Pilih rumah sakit</option>@foreach ($hospitals as $hospital)<option value="{{ $hospital->id }}" @selected(old('hospital_id') == $hospital->id)>{{ $hospital->name }}</option>@endforeach</select></label>
                        <label class="field">Nama dokumen<select name="checklist_item_id" id="administration-checklist-select" required disabled><option value="">Pilih rumah sakit terlebih dahulu</option>@foreach ($checklistItems as $checklistItem)<option value="{{ $checklistItem->id }}" data-hospital-id="{{ $checklistItem->hospital_id }}" @selected(old('checklist_item_id') == $checklistItem->id)>{{ $checklistItem->document_name }}</option>@endforeach</select></label>
                        <label class="field">Nomor dokumen<input name="document_number" value="{{ old('document_number') }}" required maxlength="100" placeholder="JMS/PKS/2026/001"></label>
                        <label class="field">Status awal<select name="status"><option value="draft" @selected(old('status') === 'draft')>Draft</option><option value="review" @selected(old('status') === 'review')>Dalam tinjauan</option><option value="final" @selected(old('status') === 'final')>Final</option></select></label>
                        <label class="field field-wide">File PDF<input type="file" name="pdf_file" accept="application/pdf,.pdf" required><small class="field-hint">Format PDF, maksimal 20 MB.</small></label>
                        <div class="form-actions field-wide"><button class="button button-primary" type="submit">Simpan surat <span>&#8594;</span></button></div>
                    </form>
                </details>
                <section class="content-section table-section">
                    <div class="section-heading"><div><p class="eyebrow">SEMUA DOKUMEN</p><h2>{{ $letters->total() }} surat RS</h2></div></div>
                    <form method="GET" action="{{ route('workspace.index') }}" class="filter-bar"><input type="hidden" name="section" value="administration"><label class="filter-field"><span>Rumah sakit</span><select name="hospital_id"><option value="">Semua rumah sakit</option>@foreach ($hospitals as $hospital)<option value="{{ $hospital->id }}" @selected(request('hospital_id') == $hospital->id)>{{ $hospital->name }}</option>@endforeach</select></label><label class="filter-field"><span>Status</span><select name="status"><option value="">Semua status</option><option value="draft" @selected(request('status') === 'draft')>Draft</option><option value="review" @selected(request('status') === 'review')>Dalam tinjauan</option><option value="final" @selected(request('status') === 'final')>Final</option></select></label><button class="button button-secondary" type="submit">Terapkan filter</button>@if (request()->hasAny(['hospital_id', 'status']))<a class="text-link" href="{{ route('workspace.index', ['section' => 'administration']) }}">Reset</a>@endif</form>
                    @if ($letters->isEmpty())
                        <div class="empty-state"><span class="empty-index">--</span><div><strong>Tidak ada surat yang cocok</strong><p>Unggah PDF baru atau ubah filter untuk melihat dokumen lainnya.</p></div></div>
                    @else
                        <div class="table-scroll"><table class="data-table letter-table"><thead><tr><th>NOMOR / DIBUAT</th><th>RUMAH SAKIT</th><th>FILE PDF</th><th>STATUS</th><th>AKSI</th></tr></thead><tbody>
                            @foreach ($letters as $letter)
                                <tr><td><strong class="document-number">{{ $letter->document_name ?: $letter->document_number }}</strong><small class="table-subtext">{{ $letter->document_number }} · {{ $letter->created_at->format('d M Y') }}</small><small class="table-subtext">Terakhir diperbarui: {{ $letter->updated_at->timezone('Asia/Jakarta')->translatedFormat('d M Y · H:i') }}</small></td><td>{{ $letter->hospital->name }}</td><td>@if ($letter->pdf_path)<a class="text-link" href="{{ route('administration.view', $letter) }}" target="_blank" rel="noopener">Lihat PDF <span>&#8599;</span></a><br><a class="text-link" href="{{ route('administration.pdf', $letter) }}">Unduh PDF <span>&#8599;</span></a>@else<span class="table-subtext">PDF belum tersedia</span>@endif</td><td><form method="POST" action="{{ route('administration.update', $letter) }}" enctype="multipart/form-data" class="table-action-form">@csrf @method('PUT')<input type="hidden" name="document_name" value="{{ $letter->document_name ?: $letter->document_number }}"><input type="hidden" name="document_number" value="{{ $letter->document_number }}"><select name="status" class="status-select" aria-label="Ubah status surat"><option value="draft" @selected($letter->status === 'draft')>Draft</option><option value="review" @selected($letter->status === 'review')>Ditinjau</option><option value="final" @selected($letter->status === 'final')>Final</option></select><input type="file" name="pdf_file" accept="application/pdf,.pdf" class="table-action-file"><button class="button button-secondary action-submit" type="submit">Ganti</button></form></td><td><form method="POST" action="{{ route('administration.destroy', $letter) }}" data-confirm="Hapus surat {{ $letter->document_name ?: $letter->document_number }}? Tindakan ini tidak dapat dibatalkan." class="table-delete-form">@csrf @method('DELETE')<button class="text-link danger-link" type="submit">Hapus</button></form></td></tr>
                            @endforeach
                        </tbody></table></div>
                        <div class="pagination-wrap">{{ $letters->links() }}</div>
                    @endif
                </section>
            @elseif ($section === 'completeness')
                <section class="page-heading">
                    <div><p class="eyebrow">ARSIP SURAT RS</p><h1>Kelengkapan Surat</h1><p class="heading-description">Periksa dan kelola dokumen yang diperlukan untuk setiap rumah sakit.</p></div>
                </section>
                <form method="GET" action="{{ route('workspace.index') }}" class="filter-bar">
                    <input type="hidden" name="section" value="completeness">
                    <label class="filter-field"><span>Rumah sakit</span><select name="hospital_id" required><option value="">Pilih rumah sakit</option>@foreach ($hospitals as $hospital)<option value="{{ $hospital->id }}" @selected($checklistHospital?->id === $hospital->id)>{{ $hospital->name }}</option>@endforeach</select></label>
                    <button class="button button-secondary" type="submit">Tampilkan daftar</button>
                </form>
                @if ($checklistHospital)
                    <section class="content-section checklist-section">
                        <div class="checklist-hospital"><strong>NAMA RUMAH SAKIT</strong><span>{{ $checklistHospital->name }}</span><strong>LOKASI</strong><span>{{ $checklistHospital->city ?: 'Belum diisi' }}</span></div>
                        <details class="form-disclosure form-panel">
                            <summary><span><strong>Tambah jenis dokumen</strong><small>Jenis dokumen ini hanya ditambahkan untuk {{ $checklistHospital->name }}.</small></span><span class="summary-mark">+</span></summary>
                            <form method="POST" action="{{ route('checklist-items.store', $checklistHospital) }}" class="form-grid">
                                @csrf
                                <label class="field field-wide">Nama dokumen<input name="document_name" value="{{ old('document_name') }}" required maxlength="200" placeholder="Nama dokumen yang perlu dilengkapi"></label>
                                <div class="form-actions field-wide"><button class="button button-primary" type="submit">Tambah dokumen <span>&#8594;</span></button></div>
                            </form>
                        </details>
                        @if ($checklistDocuments->isEmpty())
                            <div class="empty-state"><span class="empty-index">--</span><div><strong>Belum ada jenis dokumen</strong><p>Tambahkan jenis dokumen untuk mulai mencatat kelengkapan rumah sakit ini.</p></div></div>
                        @else
                            <div class="table-scroll"><table class="data-table checklist-table"><thead><tr><th rowspan="2">NO</th><th rowspan="2">DOKUMEN</th><th colspan="2" class="checklist-group-heading">KELENGKAPAN</th><th rowspan="2">KETERANGAN</th><th rowspan="2">AKSI</th></tr><tr><th>ADA</th><th>TIDAK</th></tr></thead><tbody>
                                @foreach ($checklistDocuments as $checklistItem)
                                    @php($updateFormId = 'update-checklist-'.$checklistItem->id)
                                    @php($linkedLetter = $checklistItem->administrationLetters->first())
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $checklistItem->document_name }}</td>
                                        <td><span class="checklist-indicator" aria-label="{{ $linkedLetter ? 'Ada' : '' }}">{{ $linkedLetter ? '✓' : '' }}</span></td>
                                        <td><span class="checklist-indicator" aria-label="{{ ! $linkedLetter ? 'Tidak ada' : '' }}">{{ ! $linkedLetter ? '✓' : '' }}</span></td>
                                        <td><input form="{{ $updateFormId }}" class="checklist-notes" type="text" name="notes" value="{{ $checklistItem->notes }}" maxlength="2000" aria-label="Keterangan: {{ $checklistItem->document_name }}" placeholder="Keterangan"></td>
                                        <td class="checklist-actions">
                                            @if ($linkedLetter)
                                                <a class="text-link" href="{{ route('administration.view', $linkedLetter) }}" target="_blank" rel="noopener">Lihat surat <span>&#8599;</span></a>
                                            @endif
                                            <form id="{{ $updateFormId }}" method="POST" action="{{ route('checklist-items.update', $checklistItem) }}">@csrf @method('PUT')<button class="button button-secondary" type="submit">Simpan</button></form>
                                            <form method="POST" action="{{ route('checklist-items.destroy', $checklistItem) }}" data-confirm="Hapus jenis dokumen {{ $checklistItem->document_name }} dari {{ $checklistHospital->name }}?">@csrf @method('DELETE')<button class="text-link danger-link" type="submit">Hapus</button></form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody></table></div>
                        @endif
                    </section>
                @elseif ($hospitals->isEmpty())
                    <div class="empty-state"><span class="empty-index">--</span><div><strong>Belum ada rumah sakit</strong><p>Tambahkan rumah sakit terlebih dahulu sebelum mengatur kelengkapan surat.</p></div></div>
                @endif
            @elseif ($section === 'internal')
                <section class="page-heading">
                    <div><p class="eyebrow">SURAT TAMBAHAN</p><h1>Surat Internal</h1><p class="heading-description">Daftar surat tambahan yang tidak terkait dengan rumah sakit dan tidak masuk ke arsip administrasi mitra.</p></div>
                    <a class="button button-primary" href="#new-internal-letter"><span class="button-plus">+</span> Upload PDF</a>
                </section>
                <details class="form-disclosure form-panel" id="new-internal-letter">
                    <summary><span><strong>Tambah surat internal</strong><small>Unggah dokumen internal atau surat pendukung tanpa kaitan rumah sakit.</small></span><span class="summary-mark">+</span></summary>
                    <form method="POST" action="{{ route('internal.store') }}" class="form-grid" enctype="multipart/form-data">
                        @csrf
                        <label class="field">Nama dokumen<input name="document_name" value="{{ old('document_name') }}" required maxlength="200" placeholder="Contoh: Surat Undangan Rapat Internal"></label>
                        <label class="field">Nomor dokumen<input name="document_number" value="{{ old('document_number') }}" required maxlength="100" placeholder="JMS/INT/2026/001"></label>
                        <label class="field">Status awal<select name="status"><option value="draft">Draft</option><option value="review">Dalam tinjauan</option><option value="final">Final</option></select></label>
                        <label class="field field-wide">File PDF<input type="file" name="pdf_file" accept="application/pdf,.pdf" required><small class="field-hint">Format PDF, maksimal 20 MB.</small></label>
                        <div class="form-actions field-wide"><button class="button button-primary" type="submit">Simpan surat <span>&#8594;</span></button></div>
                    </form>
                </details>
                <section class="content-section table-section">
                    <div class="section-heading"><div><p class="eyebrow">SEMUA SURAT</p><h2>{{ $internalLetters->total() }} surat internal</h2></div></div>
                    @if ($internalLetters->isEmpty())
                        <div class="empty-state"><span class="empty-index">--</span><div><strong>Belum ada surat internal</strong><p>Bagian ini bisa dipakai untuk surat pendukung non-mitra seperti memo, nota, atau dokumen internal.</p></div></div>
                    @else
                        <div class="table-scroll"><table class="data-table letter-table"><thead><tr><th>NOMOR / DIBUAT</th><th>FILE PDF</th><th>STATUS</th><th>AKSI</th></tr></thead><tbody>
                            @foreach ($internalLetters as $letter)
                                <tr><td><strong class="document-number">{{ $letter->document_name ?: $letter->document_number }}</strong><small class="table-subtext">{{ $letter->document_number }} · {{ $letter->created_at->format('d M Y') }}</small><small class="table-subtext">Terakhir diperbarui: {{ $letter->updated_at->timezone('Asia/Jakarta')->translatedFormat('d M Y · H:i') }}</small></td><td>@if ($letter->pdf_path)<a class="text-link" href="{{ route('internal.view', $letter) }}" target="_blank" rel="noopener">Lihat PDF <span>&#8599;</span></a><br><a class="text-link" href="{{ route('internal.pdf', $letter) }}">Unduh PDF <span>&#8599;</span></a>@else<span class="table-subtext">PDF belum tersedia</span>@endif</td><td><form method="POST" action="{{ route('internal.update', $letter) }}" enctype="multipart/form-data" class="table-action-form">@csrf @method('PUT')<input type="hidden" name="document_name" value="{{ $letter->document_name ?: $letter->document_number }}"><input type="hidden" name="document_number" value="{{ $letter->document_number }}"><select name="status" class="status-select" aria-label="Ubah status surat internal"><option value="draft" @selected($letter->status === 'draft')>Draft</option><option value="review" @selected($letter->status === 'review')>Ditinjau</option><option value="final" @selected($letter->status === 'final')>Final</option></select><input type="file" name="pdf_file" accept="application/pdf,.pdf" class="table-action-file"><button class="button button-secondary action-submit" type="submit">Ganti</button></form></td><td><form method="POST" action="{{ route('internal.destroy', $letter) }}" data-confirm="Hapus surat {{ $letter->document_name ?: $letter->document_number }}? Tindakan ini tidak dapat dibatalkan." class="table-delete-form">@csrf @method('DELETE')<button class="text-link danger-link" type="submit">Hapus</button></form></td></tr>
                            @endforeach
                        </tbody></table></div>
                        <div class="pagination-wrap">{{ $internalLetters->links() }}</div>
                    @endif
                </section>
            @endif
            <footer class="page-footer"><span>PT JMS · SISTEM DOKUMEN KERJA SAMA</span><span>INTERNAL / {{ now()->format('Y') }}</span></footer>
        </div>
    </main>
    <script src="{{ asset('js/draft-management.js') }}" defer></script>
</body>
</html>