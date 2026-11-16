<style>
    .doc-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.62);
        backdrop-filter: blur(4px);
        z-index: 1100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
    }

    .doc-modal-backdrop.is-open {
        display: flex;
    }

    .doc-modal-card {
        width: 100%;
        max-width: 820px;
        max-height: 90vh;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 24px 55px rgba(15, 23, 42, 0.28);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .doc-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1.2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .doc-modal-head strong {
        font-size: 0.88rem;
        color: #0f172a;
    }

    .doc-modal-close {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 8px;
        background: #f1f5f9;
        color: #334155;
        font-size: 1.1rem;
        line-height: 1;
        cursor: pointer;
    }

    .doc-modal-close:hover {
        background: #e2e8f0;
    }

    .doc-modal-body {
        background: #f8fafc;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 420px;
        max-height: calc(90vh - 60px);
        overflow: auto;
    }

    .doc-modal-body img {
        max-width: 100%;
        max-height: 75vh;
        border-radius: 8px;
        object-fit: contain;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    }

    .doc-modal-body iframe {
        width: 100%;
        height: 72vh;
        border: none;
        border-radius: 8px;
        background: #ffffff;
    }
</style>
<div class="doc-modal-backdrop" id="doc-preview-modal" role="dialog" aria-modal="true" aria-labelledby="doc-modal-title">
    <div class="doc-modal-card">
        <div class="doc-modal-head">
            <strong id="doc-modal-title">Pratinjau Berkas</strong>
            <button type="button" class="doc-modal-close" id="doc-modal-close" aria-label="Tutup">&times;</button>
        </div>
        <div class="doc-modal-body" id="doc-modal-body"></div>
    </div>
</div>
<script>
    (function () {
        var modal = document.getElementById('doc-preview-modal');
        var titleEl = document.getElementById('doc-modal-title');
        var bodyEl = document.getElementById('doc-modal-body');
        var closeBtn = document.getElementById('doc-modal-close');
        if (!modal || !titleEl || !bodyEl || !closeBtn) return;

        function closeModal() {
            modal.classList.remove('is-open');
            bodyEl.innerHTML = '';
        }

        document.querySelectorAll('[data-doc-preview]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                titleEl.textContent = btn.dataset.title || 'Pratinjau Berkas';
                bodyEl.innerHTML = '';

                if (btn.dataset.kind === 'image') {
                    var img = document.createElement('img');
                    img.src = btn.dataset.url;
                    img.alt = titleEl.textContent;
                    bodyEl.appendChild(img);
                } else {
                    var frame = document.createElement('iframe');
                    frame.src = btn.dataset.url;
                    frame.title = titleEl.textContent;
                    bodyEl.appendChild(frame);
                }

                modal.classList.add('is-open');
            });
        });

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
        });
    })();
</script>
