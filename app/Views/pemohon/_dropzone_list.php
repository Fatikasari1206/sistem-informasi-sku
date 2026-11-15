<div class="upload-stack">
    <?php foreach ($dokumenList as $dok): ?>
        <?php $isRequired = $dok['required'] ?? true; ?>
        <div class="dropzone-card" data-input="<?= esc($dok['input']) ?>" data-accept="<?= esc($dok['accept']) ?>">
            <input
                type="file"
                name="<?= esc($dok['input']) ?>"
                id="<?= esc($dok['input']) ?>"
                class="dropzone-input"
                accept="<?= esc($dok['accept']) ?>"
                <?= $isRequired ? 'required' : '' ?>
            >

            <div class="dropzone-head">
                <span class="dropzone-title"><?= esc($dok['title']) ?></span>
                <span class="dropzone-badge" data-badge></span>
            </div>

            <div class="dropzone-body" data-empty>
                <span class="dropzone-prompt">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    Pilih berkas atau tarik ke sini
                </span>
                <span class="dropzone-hint"><?= esc($dok['hint']) ?></span>
            </div>

            <div class="dropzone-preview" data-preview hidden>
                <div class="preview-thumb" data-thumb></div>
                <div class="preview-meta">
                    <span class="preview-name" data-name></span>
                    <span class="preview-size" data-size></span>
                </div>
                <button type="button" class="preview-clear" data-clear title="Ganti berkas">&times;</button>
            </div>

            <div class="dropzone-error" data-error hidden></div>
        </div>
    <?php endforeach; ?>
</div>
