<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
    <style>
        .chart-controls {
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
            font-size: 0.74rem;
            font-weight: 600;
            padding: 0.38rem 0.9rem;
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

        .chart-meta-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.1rem;
        }

        .chart-legend {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.74rem;
            font-weight: 600;
            color: #475569;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .legend-swatch {
            width: 10px;
            height: 10px;
            border-radius: 3px;
        }

        .chart-total-badge {
            font-size: 0.74rem;
            font-weight: 700;
            color: #1e293b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
        }

        .login-chart-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.35rem 1.25rem 1rem;
        }

        .bars-stage {
            position: relative;
            height: 250px;
            display: grid;
            align-items: end;
            gap: 0.85rem;
            padding: 1.5rem 0.5rem 0;
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
            font-size: 0.72rem;
            font-weight: 700;
            color: #0f172a;
        }

        .bar-stack {
            width: 100%;
            max-width: 44px;
            min-height: 4px;
            display: flex;
            flex-direction: column-reverse;
            border-radius: 8px 8px 3px 3px;
            overflow: hidden;
            background: #e2e8f0;
            transition: height 0.25s ease;
        }

        .bar-seg-pemohon {
            background: #5246f0;
            width: 100%;
            transition: height 0.25s ease;
        }

        .bar-seg-aparatur {
            background: #0d9488;
            width: 100%;
            transition: height 0.25s ease;
        }

        .bar-tooltip {
            position: absolute;
            top: -0.25rem;
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
            gap: 0.85rem;
            padding: 0.6rem 0.5rem 0;
        }

        .bar-label {
            font-size: 0.73rem;
            font-weight: 600;
            color: #475569;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 640px) {
            .bars-stage {
                height: 210px;
                gap: 0.45rem;
            }

            .labels-row {
                gap: 0.45rem;
            }

            .bar-label {
                font-size: 0.66rem;
            }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('admin/_sidebar_nav', ['activeMenu' => 'dashboard']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- 3 KPI Cards -->
    <section class="kpi-grid-3" aria-label="Ringkasan Data Sistem">
        <div class="kpi-card">
            <div class="kpi-label">AKUN WARGA TERDAFTAR</div>
            <div class="kpi-value"><?= number_format((int) $stats['total_pemohon'], 0, ',', '.') ?> Jiwa</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">AKUN APARATUR INTERNAL</div>
            <div class="kpi-value"><?= number_format((int) $stats['total_aparatur'], 0, ',', '.') ?> Petugas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">MASTER NIK CONDONGCATUR</div>
            <div class="kpi-value"><?= number_format((int) $stats['total_nik'], 0, ',', '.') ?> Data</div>
        </div>
    </section>

    <!-- Grafik Log Login -->
    <section class="panel-card">
        <div class="panel-head">
            <h2 class="panel-title">Grafik Log Login Sistem</h2>

            <div class="chart-controls" role="tablist" aria-label="Filter Periode Grafik Login">
                <button type="button" class="period-btn is-active" data-period="harian" role="tab" aria-selected="true">Harian</button>
                <button type="button" class="period-btn" data-period="mingguan" role="tab" aria-selected="false">Mingguan</button>
                <button type="button" class="period-btn" data-period="bulanan" role="tab" aria-selected="false">Bulanan</button>
            </div>
        </div>

        <div class="chart-meta-bar">
            <div class="chart-legend">
                <span class="legend-item">
                    <span class="legend-swatch" style="background: #5246f0;"></span>
                    Pemohon
                </span>
                <span class="legend-item">
                    <span class="legend-swatch" style="background: #0d9488;"></span>
                    Aparatur Internal
                </span>
            </div>
            <span class="chart-total-badge" id="chart-total-badge">Total: 0 Login</span>
        </div>

        <div class="login-chart-box">
            <div class="bars-stage" id="login-bars-stage"></div>
            <div class="labels-row" id="login-labels-row"></div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function () {
        var seriesMap = <?= json_encode($loginChartSeries, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        var stage = document.getElementById('login-bars-stage');
        var labelsRow = document.getElementById('login-labels-row');
        var totalBadge = document.getElementById('chart-total-badge');
        var buttons = document.querySelectorAll('[data-period]');

        function renderPeriod(period) {
            var data = seriesMap[period] || [];
            var maxVal = 1;
            var sumTotal = 0;

            data.forEach(function (item) {
                sumTotal += item.total;
                if (item.total > maxVal) {
                    maxVal = item.total;
                }
            });

            var colTemplate = 'repeat(' + Math.max(1, data.length) + ', minmax(0, 1fr))';
            stage.style.gridTemplateColumns = colTemplate;
            labelsRow.style.gridTemplateColumns = colTemplate;
            totalBadge.textContent = 'Total: ' + sumTotal + ' Login';

            stage.innerHTML = '';
            labelsRow.innerHTML = '';

            data.forEach(function (item) {
                var col = document.createElement('div');
                col.className = 'bar-col';

                var tooltip = document.createElement('div');
                tooltip.className = 'bar-tooltip';
                tooltip.textContent = item.label + ' — Pemohon: ' + item.pemohon + ', Aparatur: ' + item.aparatur;

                var val = document.createElement('span');
                val.className = 'bar-value';
                val.textContent = String(item.total);

                var stack = document.createElement('div');
                stack.className = 'bar-stack';

                var totalPct = item.total > 0 ? Math.max(6, Math.round((item.total / maxVal) * 78)) : 2;
                stack.style.height = totalPct + '%';

                if (item.total > 0) {
                    var pemohonPct = Math.round((item.pemohon / item.total) * 100);
                    var aparaturPct = 100 - pemohonPct;

                    if (item.pemohon > 0) {
                        var segPemohon = document.createElement('div');
                        segPemohon.className = 'bar-seg-pemohon';
                        segPemohon.style.height = pemohonPct + '%';
                        stack.appendChild(segPemohon);
                    }
                    if (item.aparatur > 0) {
                        var segAparatur = document.createElement('div');
                        segAparatur.className = 'bar-seg-aparatur';
                        segAparatur.style.height = aparaturPct + '%';
                        stack.appendChild(segAparatur);
                    }
                }

                col.appendChild(tooltip);
                col.appendChild(val);
                col.appendChild(stack);
                stage.appendChild(col);

                var lbl = document.createElement('div');
                lbl.className = 'bar-label';
                lbl.textContent = item.label;
                labelsRow.appendChild(lbl);
            });
        }

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var period = btn.dataset.period;
                buttons.forEach(function (other) {
                    var active = other === btn;
                    other.classList.toggle('is-active', active);
                    other.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                renderPeriod(period);
            });
        });

        renderPeriod('harian');
    })();
</script>
<?= $this->endSection() ?>
