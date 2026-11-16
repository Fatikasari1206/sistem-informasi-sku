<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
    <style>
        .chart-controls-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .period-switch {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
        }

        .period-btn {
            border: none;
            background: transparent;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.36rem 0.95rem;
            border-radius: 9999px;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        }

        .period-btn:hover {
            color: #0f172a;
        }

        .period-btn.is-active {
            background: #5246f0;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(82, 70, 240, 0.25);
        }

        .bar-chart-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.35rem 1.25rem 1rem;
        }

        .bars-stage {
            position: relative;
            height: 270px;
            display: grid;
            align-items: end;
            gap: 0.65rem;
            padding: 1.65rem 0.5rem 0;
            border-bottom: 1px solid #cbd5e1;
            background-image: linear-gradient(to top, rgba(203, 213, 225, 0.45) 1px, transparent 1px);
            background-size: 100% 25%;
        }

        .bar-col {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 0.35rem;
            position: relative;
        }

        .bar-value {
            font-size: 0.71rem;
            font-weight: 700;
            color: #0f172a;
        }

        .bar-pill {
            width: 100%;
            max-width: 42px;
            min-height: 4px;
            border-radius: 8px 8px 3px 3px;
            background: #e2e8f0;
            transition: height 0.25s ease, background-color 0.2s ease;
        }

        .bar-pill.has-value {
            background: linear-gradient(180deg, #5246f0 0%, #3b82f6 100%);
        }

        .bar-tooltip {
            position: absolute;
            top: -0.2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #0f172a;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.28rem 0.55rem;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.12s ease;
            z-index: 10;
        }

        .bar-col:hover .bar-tooltip {
            opacity: 1;
        }

        .labels-row {
            display: grid;
            gap: 0.65rem;
            padding: 0.6rem 0.5rem 0;
        }

        .bar-label {
            font-size: 0.71rem;
            font-weight: 600;
            color: #475569;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 720px) {
            .bars-stage {
                height: 220px;
                gap: 0.35rem;
            }

            .labels-row {
                gap: 0.35rem;
            }

            .bar-label {
                font-size: 0.64rem;
            }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('lurah/_sidebar_nav', ['activeMenu' => 'dashboard']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- 3 KPI Cards -->
    <section class="kpi-grid-3" aria-label="Ringkasan Pengajuan SKU">
        <div class="kpi-card">
            <div class="kpi-label">TOTAL PENGAJUAN SKU</div>
            <div class="kpi-value"><?= (int) $stats['total_sku'] ?> Pengajuan</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">BERKAS MENUNGGU TTE</div>
            <div class="kpi-value"><?= (int) $stats['menunggu_tte'] ?> Berkas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">PADUKUHAN TERAKTIF</div>
            <div class="kpi-value"><?= esc($stats['top_padukuhan']) ?></div>
        </div>
    </section>

    <!-- Panel Grafik Batang Permohonan SKU -->
    <section class="panel-card">
        <div class="panel-head">
            <h2 class="panel-title">Grafik Permohonan Surat Keterangan Usaha (SKU)</h2>

            <div class="chart-controls-group">
                <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                    <option value="">Padukuhan</option>
                    <?php foreach ($padukuhanList as $p): ?>
                        <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="period-switch" role="tablist" aria-label="Filter Periode Grafik">
                    <button type="button" class="period-btn is-active" data-mode="bulanan" role="tab" aria-selected="true">Bulanan</button>
                    <button type="button" class="period-btn" data-mode="tahunan" role="tab" aria-selected="false">Tahunan</button>
                </div>
            </div>
        </div>

        <div class="bar-chart-box">
            <div class="bars-stage" id="sku-bars-stage"></div>
            <div class="labels-row" id="sku-labels-row"></div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= view('partials/_pagination_script') ?>
    <script>
        (function () {
            var records         = <?= json_encode($chartRecords, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            var padukuhanSelect = document.getElementById('filter-padukuhan');
            var periodButtons   = document.querySelectorAll('.period-btn[data-mode]');
            var stageEl         = document.getElementById('sku-bars-stage');
            var labelsRowEl     = document.getElementById('sku-labels-row');

            if (window.initScrollablePadukuhanFilter && padukuhanSelect) {
                window.initScrollablePadukuhanFilter(padukuhanSelect);
            }

            var currentMode = 'bulanan';
            var MONTHS_ID   = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            function pad2(n) {
                return n < 10 ? '0' + n : String(n);
            }

            function buildSeries() {
                var selectedPad = padukuhanSelect ? padukuhanSelect.value.trim().toLowerCase() : '';
                var filtered = records.filter(function (rec) {
                    if (!selectedPad) return true;
                    return String(rec.padukuhan || '').trim().toLowerCase() === selectedPad;
                });

                var now  = new Date();
                var year = now.getFullYear();
                var mon  = now.getMonth() + 1;
                var day  = now.getDate();

                if (currentMode === 'tahunan') {
                    var monthCounts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                    filtered.forEach(function (rec) {
                        var parts = String(rec.tanggal || '').split('-');
                        if (parts.length !== 3) return;
                        var y = parseInt(parts[0], 10);
                        var m = parseInt(parts[1], 10);
                        if (y === year && m >= 1 && m <= 12) {
                            monthCounts[m - 1]++;
                        }
                    });

                    return MONTHS_ID.map(function (lbl, idx) {
                        return {
                            label: lbl,
                            value: monthCounts[idx],
                            tooltip: lbl + ' ' + year + ' \u2014 ' + monthCounts[idx] + ' SKU'
                        };
                    });
                }

                // Mode Bulanan: Akumulasi pembuatan SKU per hari pada bulan berjalan (tampilkan 10 titik terakhir atau seluruh hari hingga hari ini)
                var dailyCounts = {};
                filtered.forEach(function (rec) {
                    var parts = String(rec.tanggal || '').split('-');
                    if (parts.length !== 3) return;
                    var y = parseInt(parts[0], 10);
                    var m = parseInt(parts[1], 10);
                    var d = parseInt(parts[2], 10);
                    if (y === year && m === mon && d >= 1 && d <= day) {
                        dailyCounts[d] = (dailyCounts[d] || 0) + 1;
                    }
                });

                var cumulative = 0;
                var allDays    = [];
                for (var dIdx = 1; dIdx <= day; dIdx++) {
                    var added = dailyCounts[dIdx] || 0;
                    cumulative += added;
                    allDays.push({
                        label: dIdx === day ? 'Hari Ini' : pad2(dIdx) + ' ' + MONTHS_ID[mon - 1],
                        value: cumulative,
                        tooltip: pad2(dIdx) + ' ' + MONTHS_ID[mon - 1] + ' \u2014 Akumulasi: ' + cumulative + ' SKU' + (added > 0 ? ' (+' + added + ')' : '')
                    });
                }

                // Agar bar chart tetap lega dan terbaca jelas di 1 layar, tampilkan maksimal 12 hari terakhir
                return allDays.length > 12 ? allDays.slice(allDays.length - 12) : allDays;
            }

            function renderChart() {
                var series = buildSeries();
                var maxVal = 1;
                series.forEach(function (item) {
                    if (item.value > maxVal) maxVal = item.value;
                });

                var colTemplate = 'repeat(' + Math.max(1, series.length) + ', minmax(0, 1fr))';
                stageEl.style.gridTemplateColumns     = colTemplate;
                labelsRowEl.style.gridTemplateColumns = colTemplate;

                stageEl.innerHTML     = '';
                labelsRowEl.innerHTML = '';

                series.forEach(function (item) {
                    var col = document.createElement('div');
                    col.className = 'bar-col';

                    var tooltip = document.createElement('div');
                    tooltip.className = 'bar-tooltip';
                    tooltip.textContent = item.tooltip;

                    var val = document.createElement('span');
                    val.className = 'bar-value';
                    val.textContent = String(item.value);

                    var bar = document.createElement('div');
                    bar.className = 'bar-pill' + (item.value > 0 ? ' has-value' : '');
                    bar.style.height = (item.value > 0 ? Math.max(8, Math.round((item.value / maxVal) * 78)) : 2) + '%';

                    col.appendChild(tooltip);
                    col.appendChild(val);
                    col.appendChild(bar);
                    stageEl.appendChild(col);

                    var lbl = document.createElement('div');
                    lbl.className = 'bar-label';
                    lbl.textContent = item.label;
                    labelsRowEl.appendChild(lbl);
                });
            }

            periodButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    currentMode = btn.getAttribute('data-mode') || 'bulanan';
                    periodButtons.forEach(function (other) {
                        var active = other === btn;
                        other.classList.toggle('is-active', active);
                        other.setAttribute('aria-selected', active ? 'true' : 'false');
                    });
                    renderChart();
                });
            });

            if (padukuhanSelect) {
                padukuhanSelect.addEventListener('change', renderChart);
            }

            renderChart();
        })();
    </script>
<?= $this->endSection() ?>
