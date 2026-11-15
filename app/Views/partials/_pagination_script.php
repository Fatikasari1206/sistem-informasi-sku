<script>
window.initTablePagination = function(options) {
    var tableId      = options.tableId;
    var containerId  = options.containerId;
    var pageSize     = options.pageSize || 5;
    var itemLabel    = options.itemLabel || 'data';
    var onPageChange = options.onPageChange || null;

    var table     = document.getElementById(tableId);
    var container = document.getElementById(containerId);
    if (!table || !container) return null;

    var currentPage = 1;

    function render() {
        var allRows = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
        allRows.forEach(function(row) {
            if (row.getAttribute('data-filter-hidden') === 'true') {
                row.style.display = 'none';
            }
        });

        var emptyFilterRow = table.querySelector('tbody tr.empty-filter-row');
        var visibleRows = allRows.filter(function(row) {
            return !row.classList.contains('empty-filter-row') && row.getAttribute('data-filter-hidden') !== 'true';
        });
        var totalItems  = visibleRows.length;

        if (totalItems === 0) {
            if (!emptyFilterRow) {
                var colCount = table.querySelectorAll('thead th').length || 7;
                emptyFilterRow = document.createElement('tr');
                emptyFilterRow.className = 'empty-filter-row';
                emptyFilterRow.innerHTML = '<td colspan="' + colCount + '" style="text-align:center; padding: 2rem 1rem; color: #64748b; font-size: 0.8rem;">Tidak ada data untuk wilayah Padukuhan yang dipilih.</td>';
                table.querySelector('tbody').appendChild(emptyFilterRow);
            }
            emptyFilterRow.style.display = '';
            container.innerHTML = '';
            return;
        }

        if (emptyFilterRow) {
            emptyFilterRow.style.display = 'none';
        }

        var totalPages = Math.max(1, Math.ceil(totalItems / pageSize));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        var startIdx = (currentPage - 1) * pageSize;
        var endIdx   = Math.min(startIdx + pageSize, totalItems);

        // Tampilkan hanya baris halaman aktif
        visibleRows.forEach(function(row, idx) {
            if (idx >= startIdx && idx < endIdx) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        var rangeText = (startIdx + 1) + '-' + endIdx + ' dari ' + totalItems.toLocaleString('id-ID');

        var pagesHtml    = '';
        var prevDisabled = currentPage === 1 ? 'disabled' : '';
        var nextDisabled = currentPage === totalPages ? 'disabled' : '';

        var maxVisiblePages = 5;
        var startPage = Math.max(1, currentPage - 2);
        var endPage   = Math.min(totalPages, startPage + maxVisiblePages - 1);
        if (endPage - startPage < maxVisiblePages - 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        pagesHtml += '<button type="button" class="pag-btn prev" ' + prevDisabled + ' aria-label="Halaman Sebelumnya">&lsaquo;</button>';

        if (startPage > 1) {
            pagesHtml += '<button type="button" class="pag-btn" data-page="1">1</button>';
            if (startPage > 2) {
                pagesHtml += '<span class="pag-ellipsis">...</span>';
            }
        }

        for (var p = startPage; p <= endPage; p++) {
            var activeClass = p === currentPage ? 'is-active' : '';
            pagesHtml += '<button type="button" class="pag-btn ' + activeClass + '" data-page="' + p + '">' + p + '</button>';
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pagesHtml += '<span class="pag-ellipsis">...</span>';
            }
            pagesHtml += '<button type="button" class="pag-btn" data-page="' + totalPages + '">' + totalPages.toLocaleString('id-ID') + '</button>';
        }

        pagesHtml += '<button type="button" class="pag-btn next" ' + nextDisabled + ' aria-label="Halaman Selanjutnya">&rsaquo;</button>';

        container.innerHTML =
            '<div class="table-pagination-footer">' +
                '<div class="pagination-pill-box">' + pagesHtml + '</div>' +
            '</div>';

        var prevBtn = container.querySelector('.pag-btn.prev');
        var nextBtn = container.querySelector('.pag-btn.next');
        var numBtns = container.querySelectorAll('.pag-btn[data-page]');

        if (prevBtn && !prevBtn.disabled) {
            prevBtn.addEventListener('click', function() {
                currentPage--;
                render();
                if (onPageChange) onPageChange(currentPage);
            });
        }

        if (nextBtn && !nextBtn.disabled) {
            nextBtn.addEventListener('click', function() {
                currentPage++;
                render();
                if (onPageChange) onPageChange(currentPage);
            });
        }

        numBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var targetPage = parseInt(btn.getAttribute('data-page'), 10);
                if (!isNaN(targetPage) && targetPage !== currentPage) {
                    currentPage = targetPage;
                    render();
                    if (onPageChange) onPageChange(currentPage);
                }
            });
        });
    }

    render();

    return {
        refresh: function() {
            currentPage = 1;
            render();
        },
        goToPage: function(p) {
            currentPage = p;
            render();
        }
    };
};

window.initScrollablePadukuhanFilter = function(selectEl) {
    if (!selectEl || !selectEl.parentNode || selectEl.dataset.padukuhanEnhanced === 'true') return;
    selectEl.dataset.padukuhanEnhanced = 'true';

    function cleanLabel(text) {
        return String(text || '').replace(/\s*[\u25BE\u25BC]\s*$/, '').trim();
    }

    var wrapper = document.createElement('div');
    wrapper.className = 'padukuhan-dropdown';

    var trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'padukuhan-trigger';
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-expanded', 'false');

    var triggerText = document.createElement('span');
    var selectedOption = selectEl.options[selectEl.selectedIndex] || selectEl.options[0];
    triggerText.textContent = selectedOption ? cleanLabel(selectedOption.textContent) : 'Padukuhan';

    var chevron = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    chevron.setAttribute('width', '14');
    chevron.setAttribute('height', '14');
    chevron.setAttribute('viewBox', '0 0 24 24');
    chevron.setAttribute('fill', 'none');
    chevron.setAttribute('stroke', 'currentColor');
    chevron.setAttribute('stroke-width', '2.3');
    chevron.setAttribute('stroke-linecap', 'round');
    chevron.setAttribute('stroke-linejoin', 'round');
    var poly = document.createElementNS('http://www.w3.org/2000/svg', 'polyline');
    poly.setAttribute('points', '6 9 12 15 18 9');
    chevron.appendChild(poly);

    trigger.appendChild(triggerText);
    trigger.appendChild(chevron);

    var menu = document.createElement('div');
    menu.className = 'padukuhan-menu';
    menu.setAttribute('role', 'listbox');

    var optionButtons = [];
    Array.prototype.forEach.call(selectEl.options, function (opt) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'padukuhan-option' + (opt.value === selectEl.value ? ' is-selected' : '');
        btn.setAttribute('role', 'option');
        btn.setAttribute('aria-selected', opt.value === selectEl.value ? 'true' : 'false');
        btn.dataset.value = opt.value;
        btn.textContent = cleanLabel(opt.textContent);

        btn.addEventListener('click', function () {
            selectEl.value = opt.value;
            triggerText.textContent = btn.textContent;
            optionButtons.forEach(function (other) {
                var active = other === btn;
                other.classList.toggle('is-selected', active);
                other.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            wrapper.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
            selectEl.dispatchEvent(new Event('change'));
        });

        optionButtons.push(btn);
        menu.appendChild(btn);
    });

    selectEl.style.display = 'none';
    selectEl.parentNode.insertBefore(wrapper, selectEl);
    wrapper.appendChild(trigger);
    wrapper.appendChild(menu);
    wrapper.appendChild(selectEl);

    function closeDropdown() {
        wrapper.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        var willOpen = !wrapper.classList.contains('is-open');
        wrapper.classList.toggle('is-open', willOpen);
        trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        if (willOpen) {
            var activeBtn = menu.querySelector('.padukuhan-option.is-selected');
            if (activeBtn) {
                activeBtn.scrollIntoView({ block: 'nearest' });
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) {
            closeDropdown();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && wrapper.classList.contains('is-open')) {
            closeDropdown();
        }
    });
};
</script>
