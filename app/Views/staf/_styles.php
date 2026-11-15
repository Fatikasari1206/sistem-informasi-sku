<style>
    /* 3 KPI Stat Cards */
    .kpi-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.95rem;
    }

    .kpi-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .kpi-label {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.45rem;
    }

    .kpi-value {
        font-size: 1.28rem;
        font-weight: 800;
        color: #d97706;
        letter-spacing: -0.02em;
    }

    /* Main Panel Card */
    .panel-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.25rem 1.35rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        flex: 1;
    }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .panel-title {
        font-size: 0.96rem;
        font-weight: 700;
        color: #0f172a;
    }

    .panel-subtitle {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 0.18rem;
    }

    .filter-select {
        height: 36px;
        padding: 0 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 9999px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.76rem;
        font-weight: 600;
        outline: none;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: #5246f0;
        box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
    }

    /* Custom Scrollable Filter Dropdown (5 items visible inside card) */
    .padukuhan-dropdown {
        position: relative;
        display: inline-block;
    }

    .padukuhan-trigger {
        height: 36px;
        min-width: 155px;
        padding: 0 0.9rem 0 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 9999px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.76rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        cursor: pointer;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
    }

    .padukuhan-trigger:hover {
        background: #ffffff;
        border-color: #94a3b8;
    }

    .padukuhan-dropdown.is-open .padukuhan-trigger {
        background: #ffffff;
        border-color: #5246f0;
        box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
    }

    .padukuhan-trigger svg {
        color: #475569;
        flex-shrink: 0;
        transition: transform 0.15s ease;
    }

    .padukuhan-dropdown.is-open .padukuhan-trigger svg {
        transform: rotate(180deg);
    }

    .padukuhan-menu {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        width: 220px;
        max-height: 182px; /* 5 items x 34px + 12px padding */
        overflow-y: auto;
        padding: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
        z-index: 60;
        display: none;
    }

    .padukuhan-dropdown.is-open .padukuhan-menu {
        display: block;
    }

    .padukuhan-menu::-webkit-scrollbar {
        width: 6px;
    }

    .padukuhan-menu::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 9999px;
    }

    .padukuhan-menu::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }

    .padukuhan-option {
        width: 100%;
        height: 34px;
        padding: 0 0.75rem;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #334155;
        font-size: 0.76rem;
        font-weight: 500;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color 0.12s ease, color 0.12s ease;
    }

    .padukuhan-option:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .padukuhan-option.is-selected {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 700;
    }

    /* Table */
    .table-wrap {
        width: 100%;
        overflow-x: auto;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.4rem;
    }

    .staf-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 780px;
    }

    .staf-table thead tr {
        background: #272c50;
    }

    .staf-table thead th {
        padding: 0.82rem 0.9rem;
        text-align: left;
        font-size: 0.69rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: #ffffff;
        white-space: nowrap;
    }

    .staf-table thead th:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .staf-table thead th:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .staf-table tbody td {
        padding: 0.88rem 0.9rem;
        font-size: 0.79rem;
        color: #334155;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        transition: background-color 0.12s ease;
    }

    .staf-table tbody tr:nth-child(even) td {
        background: #f1f5f9;
    }

    .staf-table tbody tr:last-child td {
        border-bottom: none;
    }

    .staf-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .staf-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .staf-table tbody tr:hover td {
        background: #e2e8f0;
    }

    .cell-strong {
        font-weight: 700;
        color: #0f172a;
    }

    .pemohon-name {
        display: block;
        font-weight: 700;
        color: #0f172a;
    }

    .pemohon-nik {
        display: block;
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    .pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.28rem 0.72rem;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .service-baru {
        background: #e0e7ff;
        color: #4338ca;
    }

    .service-perpanjangan {
        background: #fef3c7;
        color: #b45309;
    }

    .status-green {
        background: #dcfce7;
        color: #15803d;
    }

    .status-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .status-red {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-indigo {
        background: #e0e7ff;
        color: #4338ca;
    }

    .status-neutral {
        background: #f1f5f9;
        color: #475569;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 30px;
        padding: 0 0.95rem;
        border-radius: 9999px;
        font-size: 0.73rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        background: #5246f0;
        color: #ffffff;
        transition: opacity 0.15s ease, transform 0.15s ease;
        white-space: nowrap;
    }

    .action-btn:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }

    .action-btn.emerald {
        background: #0d9468;
    }

    .action-btn.slate {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #64748b;
        font-size: 0.84rem;
    }

    .empty-state strong {
        display: block;
        color: #0f172a;
        font-size: 0.95rem;
        margin-bottom: 0.35rem;
    }

    /* Periksa 2-column detail layout */
    .periksa-grid {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    .info-box {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.1rem;
        background: #f8fafc;
    }

    .info-box h3 {
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.75rem;
    }

    .kv-list {
        display: grid;
        grid-template-columns: 145px 1fr;
        gap: 0.45rem 0.75rem;
        font-size: 0.79rem;
    }

    .kv-label {
        color: #64748b;
        font-weight: 500;
    }

    .kv-value {
        color: #0f172a;
        font-weight: 600;
    }

    .doc-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.7rem 0.85rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        margin-bottom: 0.55rem;
    }

    .doc-item:last-child {
        margin-bottom: 0;
    }

    .doc-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
    }

    .doc-meta {
        font-size: 0.71rem;
        color: #64748b;
        margin-top: 0.12rem;
    }

    .action-panel {
        margin-top: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.1rem;
        background: #ffffff;
    }

    .action-panel textarea,
    .action-panel input[type="text"],
    .inline-input {
        width: 100%;
        padding: 0.55rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        font-size: 0.79rem;
        color: #0f172a;
        outline: none;
    }

    .action-panel textarea:focus,
    .action-panel input[type="text"]:focus,
    .inline-input:focus {
        border-color: #5246f0;
        box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
    }

    .action-btn-row {
        display: flex;
        gap: 0.55rem;
        flex-wrap: wrap;
        margin-top: 0.75rem;
    }

    .btn-submit-lg {
        height: 36px;
        padding: 0 1.15rem;
        border: none;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        color: #ffffff;
    }

    .btn-approve { background: #0d9468; }
    .btn-revisi  { background: #d97706; }
    .btn-reject  { background: #dc2626; }

    .nomor-form {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    /* Horizontal Distribution Chart (Lurah Dashboard) */
    .chart-list {
        display: flex;
        flex-direction: column;
        gap: 0.72rem;
    }

    .chart-row {
        display: grid;
        grid-template-columns: 170px 1fr 72px;
        align-items: center;
        gap: 0.9rem;
    }

    .chart-label {
        font-size: 0.78rem;
        font-weight: 500;
        color: #475569;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chart-track {
        height: 11px;
        background: #f1f5f9;
        border-radius: 9999px;
        overflow: hidden;
    }

    .chart-fill {
        height: 100%;
        border-radius: 9999px;
        min-width: 10px;
    }

    .chart-count {
        font-size: 0.76rem;
        font-weight: 700;
        color: #0f172a;
        text-align: right;
        white-space: nowrap;
    }

    .panel-footer-cta {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.05rem;
    }

    .cta-btn-lg {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 1.35rem;
        border-radius: 9999px;
        background: #5246f0;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(82, 70, 240, 0.22);
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .cta-btn-lg:hover {
        opacity: 0.94;
        transform: translateY(-1px);
    }

    /* 3-dots Action Dropdown */
    .action-dropdown {
        position: relative;
        display: inline-block;
    }

    .btn-action-dots {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .btn-action-dots:hover,
    .action-dropdown.is-open .btn-action-dots {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .action-menu {
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        min-width: 135px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        padding: 0.35rem;
        z-index: 50;
        display: none;
        text-align: left;
    }

    .action-dropdown.is-open .action-menu {
        display: block;
    }

    .action-menu-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.5rem 0.75rem;
        border: none;
        background: transparent;
        color: #334155;
        font-size: 0.76rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 6px;
        transition: background 0.12s ease, color 0.12s ease;
    }

    .action-menu-item svg {
        flex-shrink: 0;
    }

    .action-menu-item:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .action-menu-item.warning {
        color: #d97706;
    }

    .action-menu-item.warning:hover {
        background: #fef3c7;
        color: #b45309;
    }

    .action-menu-item.success {
        color: #16a34a;
    }

    .action-menu-item.success:hover {
        background: #dcfce7;
        color: #15803d;
    }

    .action-menu-item.danger {
        color: #dc2626;
    }

    .action-menu-item.danger:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    .cell-actions {
        text-align: center;
        overflow: visible !important;
    }

    /* Shared Admin/Staff Modal Overlay & Pop-up Card */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        z-index: 120;
    }

    .modal-backdrop.is-open {
        display: flex;
    }

    .modal-card {
        width: 100%;
        max-width: 520px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.22);
        overflow: hidden;
        animation: modalPop 0.16s ease-out;
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.2rem 1.4rem 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .modal-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
    }

    .modal-subtitle {
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .modal-close {
        width: 30px;
        height: 30px;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        background: #f8fafc;
        color: #475569;
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .modal-body {
        padding: 1.15rem 1.4rem 1.35rem;
    }

    .modal-errors {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-size: 0.76rem;
        margin-bottom: 0.95rem;
    }

    .modal-errors ul {
        margin: 0;
        padding-left: 1.1rem;
    }

    .form-field {
        margin-bottom: 0.85rem;
    }

    .form-field label {
        display: block;
        font-size: 0.74rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.35rem;
    }

    .form-field input,
    .form-field select,
    .form-field textarea {
        width: 100%;
        height: 38px;
        padding: 0 0.85rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        font-size: 0.8rem;
        color: #0f172a;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-field textarea {
        height: 64px;
        padding: 0.55rem 0.85rem;
        resize: vertical;
    }

    .form-field input:focus,
    .form-field select:focus,
    .form-field textarea:focus {
        border-color: #5246f0;
        box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 0.75rem;
    }

    .modal-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.6rem;
        margin-top: 1.1rem;
    }

    .btn-cancel {
        height: 38px;
        padding: 0 1.05rem;
        border: 1px solid #cbd5e1;
        border-radius: 9999px;
        background: #f8fafc;
        color: #334155;
        font-size: 0.77rem;
        font-weight: 600;
        cursor: pointer;
    }

    @media (max-width: 600px) {
        .form-grid-2,
        .form-grid-3 {
            grid-template-columns: 1fr;
        }
    }

    /* Table Pagination Footer */
    .table-pagination-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 0.85rem;
        padding: 0.35rem 0.25rem 0.15rem;
    }

    .pagination-pill-box {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.28rem 0.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }

    .pag-btn {
        min-width: 28px;
        height: 28px;
        padding: 0 0.4rem;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #475569;
        font-size: 0.76rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.12s ease;
        user-select: none;
    }

    .pag-btn:hover:not(:disabled):not(.is-active) {
        background: #f1f5f9;
        color: #0f172a;
    }

    .pag-btn.is-active {
        background: #5246f0;
        color: #ffffff;
        font-weight: 700;
    }

    .pag-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .pag-ellipsis {
        padding: 0 0.2rem;
        font-size: 0.76rem;
        color: #94a3b8;
        user-select: none;
    }

    @media (max-width: 960px) {
        .kpi-grid-3 {
            grid-template-columns: 1fr;
        }

        .chart-row {
            grid-template-columns: 130px 1fr 64px;
            gap: 0.6rem;
        }

        .periksa-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
