<style>
    .sku-form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.35rem 1.6rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    }

    .sku-form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .sku-form-header h1 {
        font-size: 1.08rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
    }

    .sku-form-header p {
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 0.15rem;
    }

    .sku-form-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-radius: 8px;
        padding: 0.55rem 0.85rem;
        font-size: 0.74rem;
        margin-bottom: 0.9rem;
    }

    .sku-form-alert ul {
        margin-left: 1rem;
    }

    .sku-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.4rem;
        align-items: start;
    }

    .sku-section-title {
        font-size: 0.68rem;
        font-weight: 700;
        color: #475569;
        letter-spacing: 0.05em;
        margin-bottom: 0.6rem;
    }

    .sku-field {
        margin-bottom: 0.65rem;
    }

    .sku-field label {
        display: block;
        font-size: 0.73rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .sku-field input,
    .sku-field select,
    .sku-field textarea {
        width: 100%;
        height: 36px;
        padding: 0 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.78rem;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .sku-field textarea {
        height: auto;
        padding: 0.45rem 0.75rem;
        resize: vertical;
        line-height: 1.4;
    }

    .sku-field input::placeholder,
    .sku-field textarea::placeholder {
        color: #94a3b8;
    }

    .sku-field input:focus,
    .sku-field select:focus,
    .sku-field textarea:focus {
        border-color: #5246f0;
        box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
    }

    .sku-field input[readonly],
    .sku-field textarea[readonly] {
        background: #f8fafc;
        color: #475569;
    }

    .sku-field input.is-expired {
        color: #dc2626;
        font-weight: 600;
    }

    .sku-field input.is-active {
        color: #15803d;
        font-weight: 600;
    }

    .sku-subgrid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
    }

    .sku-inline-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.15rem 0 0.65rem;
        font-size: 0.74rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
    }

    .sku-inline-check input[type="checkbox"] {
        width: 15px;
        height: 15px;
        accent-color: #5246f0;
        cursor: pointer;
    }

    /* Industry-Standard Upload Dropzone */
    .upload-stack {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .dropzone-card {
        position: relative;
        border: 1.5px dashed #cbd5e1;
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        background: #f8fafc;
        cursor: pointer;
        transition: border-color 0.15s ease, background-color 0.15s ease;
    }

    .dropzone-card:hover,
    .dropzone-card.is-dragover {
        border-color: #5246f0;
        background: #f5f3ff;
    }

    .dropzone-card.is-filled {
        border-style: solid;
        border-color: #10b981;
        background: #f0fdf4;
    }

    .dropzone-card.is-invalid {
        border-style: solid;
        border-color: #ef4444;
        background: #fef2f2;
    }

    .dropzone-input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .dropzone-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.28rem;
    }

    .dropzone-title {
        font-size: 0.73rem;
        font-weight: 600;
        color: #0f172a;
    }

    .dropzone-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.12rem 0.48rem;
        border-radius: 9999px;
        display: none;
    }

    .dropzone-card.is-filled .dropzone-badge {
        display: inline-block;
        background: #dcfce7;
        color: #15803d;
    }

    .dropzone-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .dropzone-prompt {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: #5246f0;
    }

    .dropzone-hint {
        font-size: 0.67rem;
        color: #64748b;
    }

    .dropzone-preview {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .dropzone-preview[hidden] {
        display: none;
    }

    .preview-thumb {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #e2e8f0;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 700;
        overflow: hidden;
        flex-shrink: 0;
    }

    .preview-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-meta {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
    }

    .preview-name {
        font-size: 0.72rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .preview-size {
        font-size: 0.67rem;
        color: #64748b;
        flex-shrink: 0;
    }

    .preview-clear {
        width: 22px;
        height: 22px;
        border: none;
        background: #e2e8f0;
        color: #334155;
        border-radius: 9999px;
        font-size: 0.85rem;
        line-height: 1;
        cursor: pointer;
        flex-shrink: 0;
    }

    .preview-clear:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    .dropzone-error {
        margin-top: 0.25rem;
        font-size: 0.68rem;
        font-weight: 600;
        color: #dc2626;
    }

    .sku-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.6rem;
        margin-top: 1rem;
        padding-top: 0.85rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn-form-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 1.25rem;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-form-cancel:hover {
        background: #e2e8f0;
    }

    .btn-form-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 1.45rem;
        border: none;
        border-radius: 9999px;
        background: #5246f0;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(82, 70, 240, 0.25);
    }

    .btn-form-submit:hover:not(:disabled) {
        background: #463ae0;
    }

    .btn-form-submit:disabled {
        opacity: 0.7;
        cursor: wait;
    }

    .sku-empty-box {
        text-align: center;
        padding: 2rem 1rem 0.5rem;
        color: #475569;
        font-size: 0.82rem;
    }

    .sku-empty-box strong {
        display: block;
        color: #0f172a;
        font-size: 0.95rem;
        margin-bottom: 0.35rem;
    }

    @media (max-width: 860px) {
        .sku-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
