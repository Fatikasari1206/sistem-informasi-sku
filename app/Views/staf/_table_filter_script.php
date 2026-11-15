<?= view('partials/_pagination_script') ?>
<script>
    (function () {
        var padukuhanSelect = document.getElementById('filter-padukuhan');
        var rows            = document.querySelectorAll('#staf-queue-table tbody tr');

        var pagination = window.initTablePagination({
            tableId: 'staf-queue-table',
            containerId: 'staf-pagination',
            pageSize: 5,
            itemLabel: 'pengajuan SKU'
        });

        function applyFilters() {
            var padukuhan = padukuhanSelect ? padukuhanSelect.value.trim().toLowerCase() : '';

            rows.forEach(function (row) {
                if (row.classList.contains('empty-filter-row')) return;
                var rowPadukuhan   = (row.dataset.padukuhan || '').toLowerCase();
                var matchPadukuhan = !padukuhan || rowPadukuhan === padukuhan;

                if (matchPadukuhan) {
                    row.removeAttribute('data-filter-hidden');
                } else {
                    row.setAttribute('data-filter-hidden', 'true');
                }
            });

            if (pagination) {
                pagination.refresh();
            }
        }

        if (window.initScrollablePadukuhanFilter) {
            window.initScrollablePadukuhanFilter(padukuhanSelect);
        }

        if (padukuhanSelect) {
            padukuhanSelect.addEventListener('change', applyFilters);
        }
    })();
</script>
