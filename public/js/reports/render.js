let salesChartInstance = null;

function renderSalesChart(trendData) {
    const ctx = document.getElementById('salesTrendChart').getContext('2d');

    // Niszczymy poprzedni wykres, jeśli strona odtwarza go ponownie
    if (salesChartInstance) {
        salesChartInstance.destroy();
    }

    // Ekstrakcja danych dla Chart.js
    const labels = trendData.map(row => row.order_date);
    const revenueData = trendData.map(row => parseFloat(row.daily_revenue));
    const itemsData = trendData.map(row => parseInt(row.daily_items, 10));

    salesChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Przychód (PLN)',
                    data: revenueData,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)', // primary
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-revenue', // Przypisanie do lewej osi
                    order: 2
                },
                {
                    label: 'Sprzedane przedmioty (szt.)',
                    data: itemsData,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)', // success
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-items', // Przypisanie do prawej osi
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false, // Tooltip pokaże oba słupki po najechaniu na dany dzień
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                zoom: {
                    pan: {
                        enabled: true, // Pozwala na "chwytanie" wykresu i przesuwanie
                        mode: 'x',     // Przesuwamy tylko w lewo/prawo (po dacie)
                    },
                    zoom: {
                        wheel: {
                            enabled: true, // Kółko myszy przybliża/oddala
                        },
                        pinch: {
                            enabled: true  // Gesty palcami na ekranach dotykowych
                        },
                        mode: 'x', // Przybliżamy tylko oś czasu (oś Y dopasuje się sama)
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: { display: true, text: 'Data sprzedaży' }
                },
                'y-revenue': {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Przychód (PLN)' }
                },
                'y-items': {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: { display: true, text: 'Ilość sztuk' },
                    grid: {
                        drawOnChartArea: false // Ukrywamy siatkę dla drugiej osi, by się nie krzyżowały
                    }
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const payloadElement = document.getElementById('reports-data-payload');
    if (!payloadElement) return;

    // Obsługa zakładek
    const activeTab = payloadElement.dataset.activeTab;
    if (activeTab === 'demo') {
        const demoTabBtn = new bootstrap.Tab(document.getElementById('demo-tab'));
        if (demoTabBtn) demoTabBtn.show();
    }

    // ==========================================
    // RENDEROWANIE SPRZEDAŻY
    // ==========================================
    const rawSalesData = payloadElement.dataset.sales;
    if (rawSalesData && rawSalesData !== '[]' && rawSalesData !== 'null') {
        const salesData = JSON.parse(rawSalesData);

        document.getElementById('sales-empty-state').classList.add('d-none');
        document.getElementById('sales-report-header').classList.remove('d-none');
        document.getElementById('sales-summary').classList.remove('d-none');
        document.getElementById('sales-chart-container').classList.remove('d-none');
        document.getElementById('sales-details-container').classList.remove('d-none');

        const isAllTime = document.getElementById('all_time_sales')?.checked;
        const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
        const dateTo = document.querySelector('input[name="date_to"]')?.value || '';
        const formatDate = (dateStr) => dateStr ? dateStr.split('-').reverse().join('.') : '';

        if (isAllTime) {
            document.getElementById('sales-report-title').innerText = `Raport sprzedaży: Cały okres funkcjonowania sklepu`;
        } else {
            document.getElementById('sales-report-title').innerText = `Raport sprzedaży: ${formatDate(dateFrom)} – ${formatDate(dateTo)}`;
        }

        const selectedCategories = Array.from(document.querySelectorAll('.category-cb:checked'))
            .filter(cb => cb.hasAttribute('name'))
            .map(cb => {
                let path = []; let currentCb = cb;
                while (currentCb) {
                    path.unshift(currentCb.nextElementSibling.innerText.trim());
                    const parentId = currentCb.getAttribute('data-parent');
                    if (!parentId || parentId === '0') break;
                    currentCb = document.querySelector(`.category-cb[data-id="${parentId}"]`);
                }
                return path.join(' ➔ ');
            });

        const selectedBrands = Array.from(document.querySelectorAll('.brand-cb:checked')).map(cb => cb.nextElementSibling.innerText.trim());

        let filtersText = '';
        if (document.getElementById('cat_all')?.checked && document.getElementById('brand_all')?.checked) {
            filtersText = 'Raport dla wszystkich marek i kategorii';
        } else {
            const catText = selectedCategories.length > 0 ? selectedCategories.join(', ') : 'Brak';
            const brandText = selectedBrands.length > 0 ? selectedBrands.join(', ') : 'Brak';
            filtersText = `Zastosowane filtry ➔ Kategorie: [${catText}] | Marki: [${brandText}]`;
        }

        document.getElementById('sales-report-filters').innerText = filtersText;
        document.getElementById('total-revenue-val').innerText = salesData.totals.revenue + ' PLN';
        document.getElementById('total-items-val').innerText = salesData.totals.items + ' szt.';

        const tbody = document.getElementById('sales-table-body');
        tbody.innerHTML = '';

        const activeDays = salesData.trend.filter(dayRow => parseFloat(dayRow.daily_revenue) > 0);

        if (activeDays && activeDays.length > 0) {
            activeDays.forEach(dayRow => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="fw-bold">${dayRow.order_date}</td>
                    <td>${dayRow.daily_items} szt.</td>
                    <td class="text-success fw-bold">${parseFloat(dayRow.daily_revenue).toFixed(2)} PLN</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="3" class="text-muted text-center py-4">Brak danych sprzedaży spełniających wybrane kryteria.</td></tr>';
        }

        if (salesData.trend && salesData.trend.length > 0) {
            renderSalesChart(salesData.trend);
        }
        // ==========================================
        // ODTWARZANIE DOKŁADNEJ POZYCJI SCROLLA
        // ==========================================
        //const savedScrollPos = sessionStorage.getItem('reportScrollPos');
        //if (savedScrollPos !== null) {
        // Używamy setTimeout, aby dać przeglądarce mikrosekundę na przeliczenie 
        // nowych wysokości elementów (tabel, wykresów) przed przewinięciem
        //    setTimeout(() => {
        //        window.scrollTo({
        //            top: parseInt(savedScrollPos, 10),
        //            behavior: 'instant' // "instant" sprawia, że nie widać jazdy ekranu
        //        });
        // Czyścimy pamięć po użyciu, by nie psuć scrolla przy zwykłym odświeżeniu
        //        sessionStorage.removeItem('reportScrollPos');
        //    }, 10);
        //}
        //document.documentElement.style.visibility = '';
    }

    // ==========================================
    // RENDEROWANIE DEMOGRAFII
    // ==========================================
    const rawDemoData = payloadElement.dataset.demo;
    if (rawDemoData && rawDemoData !== 'null') {
        const demoData = JSON.parse(rawDemoData);

        // 1. Odkrywanie UI
        document.getElementById('demo-empty-state').classList.add('d-none');
        document.getElementById('demo-report-header').classList.remove('d-none');
        //document.getElementById('demo-chart-container').classList.remove('d-none');
        document.getElementById('demo-details-container').classList.remove('d-none');

        // 2. Budowanie nagłówka kontekstowego
        const isAllTimeDemo = document.getElementById('all_time_demo')?.checked;
        const activeFrom = document.querySelector('input[name="active_from"]')?.value || '';
        const activeTo = document.querySelector('input[name="active_to"]')?.value || '';
        const formatDate = (dateStr) => dateStr ? dateStr.split('-').reverse().join('.') : '';

        if (isAllTimeDemo) {
            document.getElementById('demo-report-title').innerText = `Raport Demograficzny: Klienci z całego okresu`;
        } else {
            document.getElementById('demo-report-title').innerText = `Raport Demograficzny: Klienci aktywni od ${formatDate(activeFrom)} do ${formatDate(activeTo)}`;
        }

        const selectedAges = Array.from(document.querySelectorAll('.age-cb:checked')).map(cb => cb.nextElementSibling.innerText.trim());
        const selectedGenders = Array.from(document.querySelectorAll('.gender-cb:checked')).map(cb => cb.nextElementSibling.innerText.trim());
        const citiesStr = document.querySelector('input[name="cities"]')?.value.trim() || 'Wszystkie';
        const groupType = document.querySelector('input[name="group_by_type"]:checked').value;

        const headerName = (groupType === 'brands') ? 'Marka' :
            (groupType === 'categories') ? 'Kategoria' : 'Produkt';

        const filtersText = `Wiek: [${selectedAges.join(', ')}] | Płeć: [${selectedGenders.join(', ')}] | Miasta: [${citiesStr}] | Grupowanie: ${groupType === 'brands' ? 'Marki' : (groupType === 'categories' ? 'Kategorie' : 'Produkty')}`;
        document.getElementById('demo-report-filters').innerText = filtersText;
        document.getElementById('demo-table-name-header').innerText = headerName;

        // 3. Generowanie Tabeli Rankingu
        const tbodyDemo = document.getElementById('demo-table-body');
        tbodyDemo.innerHTML = '';

        if (demoData.length > 0) {
            demoData.forEach((row, index) => {
                const tr = document.createElement('tr');

                let placeDecoration = `${index + 1}.`;

                tr.innerHTML = `
                    <td class="fw-bold">${placeDecoration}</td>
                    <td class="text-start ps-4">${row.item_name}</td>
                    <td class="text-success fw-bold">${row.total_bought} szt.</td>
                `;
                tbodyDemo.appendChild(tr);
            });

            // 4. Miejsce na wywołanie Chart.js (wykres słupkowy lub kołowy dla Top 10)
            // renderDemographicsChart(demoData);

        } else {
            tbodyDemo.innerHTML = '<tr><td colspan="3" class="text-muted text-center py-4">Brak danych demograficznych spełniających wybrane kryteria.</td></tr>';
            document.getElementById('demo-chart-container').classList.add('d-none');
        }
    }
});
