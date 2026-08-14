<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-mark">LT</div>
        <div class="brand-text">
            <strong>LoanTrack</strong>
            <span>Payments</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ ($activeNav ?? '') === 'dashboard' ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h7v7H4V4zm9 0h7v5h-7V4zM4 13h7v7H4v-7zm9 3h7v4h-7v-4z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('payments.index') }}" class="nav-item {{ ($activeNav ?? '') === 'payments' ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h10v2H4v-2z"/></svg>
            Payments
        </a>
        <a href="{{ route('payments.create') }}" class="nav-item {{ ($activeNav ?? '') === 'create' ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6V5z"/></svg>
            Add Payment
        </a>
        <a href="{{ route('documents.index') }}" class="nav-item {{ ($activeNav ?? '') === 'documents' ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2h9l5 5v15H6V2zm8 1.5V8h4.5L14 3.5zM8 12h8v2H8v-2zm0 4h8v2H8v-2z"/></svg>
            Documents
        </a>
        <a href="{{ route('reports.index') }}" class="nav-item {{ ($activeNav ?? '') === 'reports' ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19h3V9H5v10zm5 0h3V5h-3v14zm5 0h3v-7h-3v7z"/></svg>
            Reports
        </a>
        <a href="{{ route('settings.index') }}" class="nav-item {{ ($activeNav ?? '') === 'settings' ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.14 12.94a7.5 7.5 0 0 0 .06-1 7.5 7.5 0 0 0-.06-1l2.03-1.58-1.92-3.32-2.39.96a7.4 7.4 0 0 0-1.73-1L14.5 2h-5l-.63 2.99a7.4 7.4 0 0 0-1.73 1l-2.39-.96L2.83 8.35 4.86 9.93a7.5 7.5 0 0 0-.06 1 7.5 7.5 0 0 0 .06 1L2.83 13.5l1.92 3.32 2.39-.96c.53.4 1.11.74 1.73 1L9.5 22h5l.63-2.99c.62-.26 1.2-.6 1.73-1l2.39.96 1.92-3.32-2.03-1.71zM12 15.5A3.5 3.5 0 1 1 12 8.5a3.5 3.5 0 0 1 0 7z"/></svg>
            Settings
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr($settings->borrower_name ?? 'B', 0, 1)) }}</div>
        <div class="user-meta">
            <strong>{{ $settings->borrower_name ?? 'Borrower' }}</strong>
            <span>{{ $settings->loan_title ?? 'Loan' }}</span>
        </div>
    </div>
</aside>
