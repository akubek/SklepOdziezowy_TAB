document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Pobranie magazynu danych
    const payloadElement = document.getElementById('reports-data-payload');
    
    if (payloadElement) {
        // 2. Odczytanie aktywnej zakładki
        const activeTab = payloadElement.dataset.activeTab;
        if (activeTab === 'demo') {
            const demoTabBtn = new bootstrap.Tab(document.getElementById('demo-tab'));
            demoTabBtn.show();
        }

        // 3. Odczytanie i parsowanie danych sprzedaży
        const rawSalesData = payloadElement.dataset.sales;
        if (rawSalesData && rawSalesData !== '[]' && rawSalesData !== 'null') {
            const salesData = JSON.parse(rawSalesData);
            
            // Logika UI
            document.getElementById('sales-empty-state').classList.add('d-none');
            document.getElementById('sales-summary').classList.remove('d-none');
            document.getElementById('sales-chart-container').classList.remove('d-none');
            
            document.getElementById('total-revenue-val').innerText = salesData.totals.revenue + ' PLN';
            document.getElementById('total-items-val').innerText = salesData.totals.items + ' szt.';

            // renderSalesChart(salesData.trend);
        }

        // 4. Odczytanie i parsowanie danych demograficznych
        const rawDemoData = payloadElement.dataset.demo;
        if (rawDemoData && rawDemoData !== '[]' && rawDemoData !== 'null') {
            const demoData = JSON.parse(rawDemoData);
            
            document.getElementById('demo-empty-state').classList.add('d-none');
            document.getElementById('demo-results-container').classList.remove('d-none');

            if (demoData.length === 0) {
                alert("Brak danych spełniających wybrane kryteria.");
            } else {
                // renderPopularityChart(demoData);
            }
        }
    }
    // ==========================================
    // 1. ZAAWANSOWANE DRZEWKO KATEGORII
    // ==========================================
    const categoryCheckboxes = document.querySelectorAll('.category-cb');
    const catAllCheckbox = document.getElementById('cat_all');

    // Obsługa kliknięcia w "Wszystkie kategorie"
    if (catAllCheckbox) {
        catAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            // Najpierw wymuś zmianę na korzeniach (głównych kategoriach)
            document.querySelectorAll('.category-cb[data-parent="0"]').forEach(root => {
                root.checked = isChecked;
                forceChildrenState(root.getAttribute('data-id'), isChecked);
            });
            
            // Przelicz stan, by wyczyścić ewentualne "kropki"
            updateEntireTree();
        });
    }

    // Obsługa kliknięcia w pojedynczą kategorię
    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const isChecked = this.checked;
            const id = this.getAttribute('data-id');

            // 1. Narzuć stan na wszystkie dzieci w dół
            forceChildrenState(id, isChecked);

            // 2. Przelicz całe drzewko w górę (by ustawić kropki i pełne checkboxy rodziców)
            updateEntireTree();
        });
    });

    // Funkcja spychająca stan na dzieci
    function forceChildrenState(parentId, isChecked) {
        document.querySelectorAll(`.category-cb[data-parent="${parentId}"]`).forEach(child => {
            child.checked = isChecked;
            child.indeterminate = false; // Usuwa kropkę, jeśli była
            forceChildrenState(child.getAttribute('data-id'), isChecked);
        });
    }

    // Funkcja przeliczająca całe drzewo od korzeni (parent=0)
    function updateEntireTree() {
        const roots = document.querySelectorAll('.category-cb[data-parent="0"]');
        roots.forEach(root => evaluateNode(root));
        
        updateCatAllCheckbox();
        optimizePayload();
    }

    // Serce logiki: sprawdza stan dzieci, by zdecydować o stanie rodzica
    function evaluateNode(node) {
        const id = node.getAttribute('data-id');
        const children = document.querySelectorAll(`.category-cb[data-parent="${id}"]`);
        
        // Jeśli nie ma dzieci, zwracamy jego stan (1 = pełny, 0 = pusty)
        if (children.length === 0) {
            return node.checked ? 1 : 0;
        }
        
        let checkedCount = 0;
        let indeterminateCount = 0;
        
        // Rekurencyjnie sprawdź dzieci
        children.forEach(child => {
            const state = evaluateNode(child);
            if (state === 1) checkedCount++;
            if (state === 0.5) indeterminateCount++;
        });
        
        // Ustalenie stanu obecnego węzła (rodzica)
        if (checkedCount === children.length && children.length > 0) {
            // Wszystkie dzieci zaznaczone -> Zaznacz rodzica na pełno
            node.checked = true;
            node.indeterminate = false;
            return 1;
        } else if (checkedCount > 0 || indeterminateCount > 0) {
            // Część dzieci zaznaczona -> Ustaw "kropkę/kreskę"
            node.checked = false;
            node.indeterminate = true;
            return 0.5;
        } else {
            // Żadne dziecko nie jest zaznaczone -> Odznacz
            node.checked = false;
            node.indeterminate = false;
            return 0;
        }
    }

    // Aktualizacja przycisku "Wszystkie kategorie" na samej górze
    function updateCatAllCheckbox() {
        if (!catAllCheckbox) return;
        const roots = document.querySelectorAll('.category-cb[data-parent="0"]');
        
        let checkedCount = 0;
        let indeterminateCount = 0;
        
        roots.forEach(root => {
            if (root.checked) checkedCount++;
            if (root.indeterminate) indeterminateCount++;
        });
        
        if (checkedCount === roots.length && roots.length > 0) {
            catAllCheckbox.checked = true;
            catAllCheckbox.indeterminate = false;
        } else if (checkedCount > 0 || indeterminateCount > 0) {
            catAllCheckbox.checked = false;
            catAllCheckbox.indeterminate = true;
        } else {
            catAllCheckbox.checked = false;
            catAllCheckbox.indeterminate = false;
        }
    }

    // Optymalizacja payloadu (odpinanie atrybutu name od dzieci, gdy rodzic ma zaznaczenie)
    function optimizePayload() {
        // Zresetuj wszystkim (dodaj atrybut name)
        document.querySelectorAll('.category-cb').forEach(cb => {
            cb.setAttribute('name', 'categories[]');
        });
        
        // Odpinaj atrybut name dzieciom w pełni zaznaczonych rodziców
        document.querySelectorAll('.category-cb:checked').forEach(checkedNode => {
            removeNameFromDescendants(checkedNode.getAttribute('data-id'));
        });
    }

    function removeNameFromDescendants(parentId) {
        document.querySelectorAll(`.category-cb[data-parent="${parentId}"]`).forEach(child => {
            child.removeAttribute('name'); // Element wizualnie jest ok, ale backend go nie dostanie
            removeNameFromDescendants(child.getAttribute('data-id'));
        });
    }

    // ==========================================
    // 2. LISTA MAREK
    // ==========================================
    const brandCheckboxes = document.querySelectorAll('.brand-cb');
    const brandAllCheckbox = document.getElementById('brand_all');

    if (brandAllCheckbox) {
        brandAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            brandCheckboxes.forEach(cb => cb.checked = isChecked);
        });
    }

    brandCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBrandAllCheckbox);
    });

    function updateBrandAllCheckbox() {
        if (!brandAllCheckbox) return;
        
        const total = brandCheckboxes.length;
        const checkedCount = document.querySelectorAll('.brand-cb:checked').length;
        
        if (checkedCount === total && total > 0) {
            brandAllCheckbox.checked = true;
            brandAllCheckbox.indeterminate = false;
        } else if (checkedCount > 0) {
            brandAllCheckbox.checked = false;
            brandAllCheckbox.indeterminate = true;
        } else {
            brandAllCheckbox.checked = false;
            brandAllCheckbox.indeterminate = false;
        }
    }

    // Wywołaj inicjalizacyjnie (jeśli przeglądarka zapamiętała stan po np. cofnięciu)
    updateEntireTree();
    updateBrandAllCheckbox();
    
    const collapses = document.querySelectorAll('.collapse');
    
    collapses.forEach(collapseEl => {
        // Gdy kontener się otwiera -> zmień strzałkę na "w dół"
        collapseEl.addEventListener('show.bs.collapse', function (e) {
            // Upewniamy się, że event dotyczy tego konkretnego kontenera, a nie zagnieżdżonego
            if (e.target === this) {
                // Znajdź przycisk (strzałkę) sterujący tym kontenerem i podmień klasę
                const toggleIcon = document.querySelector(`[data-bs-target="#${this.id}"]`);
                if (toggleIcon) {
                    toggleIcon.classList.remove('bi-chevron-right');
                    toggleIcon.classList.add('bi-chevron-down');
                }
            }
        });

        // Gdy kontener się zamyka -> zmień strzałkę z powrotem w prawo
        collapseEl.addEventListener('hide.bs.collapse', function (e) {
            if (e.target === this) {
                const toggleIcon = document.querySelector(`[data-bs-target="#${this.id}"]`);
                if (toggleIcon) {
                    toggleIcon.classList.remove('bi-chevron-down');
                    toggleIcon.classList.add('bi-chevron-right');
                }
            }
        });
    });

    // ==========================================
    // 3. WALIDACJA DAT (OD <= DO)
    // ==========================================
    function setupDateValidation(formId, fromName, toName, errorFeedbackId = null) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const fromInput = form.querySelector(`[name="${fromName}"]`);
        const toInput = form.querySelector(`[name="${toName}"]`);
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorFeedback = errorFeedbackId ? document.getElementById(errorFeedbackId) : null;

        function validate() {
            // Jeśli któreś pole jest puste, nie sprawdzamy błędu (np. dla opcjonalnych przedziałów)
            if (!fromInput.value || !toInput.value) {
                clearErrors();
                return;
            }

            const dFrom = new Date(fromInput.value);
            const dTo = new Date(toInput.value);

            // Jeśli OD jest większe (późniejsze) niż DO
            if (dFrom > dTo) {
                submitBtn.disabled = true;
                fromInput.classList.add('is-invalid');
                toInput.classList.add('is-invalid');
                if (errorFeedback) errorFeedback.style.setProperty('display', 'block', 'important');
            } else {
                clearErrors();
            }
        }

        function clearErrors() {
            submitBtn.disabled = false;
            fromInput.classList.remove('is-invalid');
            toInput.classList.remove('is-invalid');
            if (errorFeedback) errorFeedback.style.setProperty('display', 'none', 'important');
        }

        fromInput.addEventListener('change', validate);
        toInput.addEventListener('change', validate);
    }

    // Inicjalizacja walidacji dla obu formularzy
    setupDateValidation('sales-filters', 'date_from', 'date_to');
    setupDateValidation('demo-filters', 'active_from', 'active_to', 'demo-date-error');


    // ==========================================
    // 4. OBSŁUGA CZYSZCZENIA FORMULARZY (Reset)
    // ==========================================
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('reset', () => {
            // Natywny 'reset' przywraca formularz do stanu domyślnego, ale wydarza się to
            // w trakcie zdarzenia. Używamy setTimeout(..., 10), aby pozwolić przeglądarce 
            // wyczyścić checkboxy, a DOPIERO POTEM uruchamiamy nasz skrypt przeliczający kropki.
            setTimeout(() => {
                // 1. Zdejmij wszystkie podświetlenia błędów (czerwone ramki)
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = false;
                
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                
                const demoError = document.getElementById('demo-date-error');
                if (demoError) demoError.style.setProperty('display', 'none', 'important');

                // 2. Wymuś odświeżenie customowego drzewka checkboxów (funkcje zdefiniowane wyżej w pliku)
                if (typeof updateEntireTree === 'function') updateEntireTree();
                if (typeof updateBrandAllCheckbox === 'function') updateBrandAllCheckbox();
                
            }, 10);
        });
    });
});