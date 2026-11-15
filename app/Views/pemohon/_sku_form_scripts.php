<script>
    (function () {
        document.querySelectorAll('.input-currency').forEach(function (input) {
            input.addEventListener('input', function () {
                var digits = this.value.replace(/\D/g, '');
                this.value = digits ? Number(digits).toLocaleString('id-ID') : '';
            });
        });

        var maxBytes = 2 * 1024 * 1024;
        document.querySelectorAll('.dropzone-card').forEach(function (card) {
            var input = card.querySelector('.dropzone-input');
            var emptyBox = card.querySelector('[data-empty]');
            var previewBox = card.querySelector('[data-preview]');
            var thumbBox = card.querySelector('[data-thumb]');
            var nameBox = card.querySelector('[data-name]');
            var sizeBox = card.querySelector('[data-size]');
            var badgeBox = card.querySelector('[data-badge]');
            var errorBox = card.querySelector('[data-error]');
            var clearBtn = card.querySelector('[data-clear]');
            var allowedExts = card.dataset.accept.split(',').map(function (s) {
                return s.trim().toLowerCase().replace('.', '');
            });

            function formatBytes(bytes) {
                return bytes < 1024 * 1024
                    ? Math.max(1, Math.round(bytes / 1024)) + ' KB'
                    : (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            }

            function resetCard() {
                input.value = '';
                card.classList.remove('is-filled', 'is-invalid');
                emptyBox.hidden = false;
                previewBox.hidden = true;
                thumbBox.innerHTML = '';
                badgeBox.textContent = '';
                errorBox.hidden = true;
                errorBox.textContent = '';
            }

            function showError(msg) {
                input.value = '';
                card.classList.remove('is-filled');
                card.classList.add('is-invalid');
                emptyBox.hidden = false;
                previewBox.hidden = true;
                errorBox.hidden = false;
                errorBox.textContent = msg;
            }

            function handleFile(file) {
                if (!file) {
                    resetCard();
                    return;
                }

                var ext = (file.name.split('.').pop() || '').toLowerCase();
                if (allowedExts.indexOf(ext) === -1) {
                    showError('Format harus ' + allowedExts.join('/').toUpperCase() + '.');
                    return;
                }

                if (file.size > maxBytes) {
                    showError('Ukuran file melebihi batas 2 MB.');
                    return;
                }

                card.classList.remove('is-invalid');
                card.classList.add('is-filled');
                errorBox.hidden = true;
                emptyBox.hidden = true;
                previewBox.hidden = false;
                badgeBox.textContent = '✓ Siap';
                nameBox.textContent = file.name;
                sizeBox.textContent = formatBytes(file.size);

                if (file.type.indexOf('image/') === 0) {
                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.onload = function () { URL.revokeObjectURL(img.src); };
                    thumbBox.innerHTML = '';
                    thumbBox.appendChild(img);
                } else {
                    thumbBox.textContent = 'PDF';
                }
            }

            card.addEventListener('click', function (e) {
                if (e.target === clearBtn) return;
                input.click();
            });

            clearBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                resetCard();
            });

            input.addEventListener('change', function () {
                handleFile(this.files && this.files[0]);
            });

            card.addEventListener('dragover', function (e) {
                e.preventDefault();
                card.classList.add('is-dragover');
            });

            card.addEventListener('dragleave', function () {
                card.classList.remove('is-dragover');
            });

            card.addEventListener('drop', function (e) {
                e.preventDefault();
                card.classList.remove('is-dragover');
                var file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
                if (!file) return;

                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                handleFile(file);
            });
        });

        document.querySelectorAll('form[data-submit-guard]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('.btn-form-submit');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Mengirim...';
                }
            });
        });
    })();
</script>
