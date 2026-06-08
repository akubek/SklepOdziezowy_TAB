document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. ODTWARZANIE STANU Z URL
    // ==========================================
    function restoreFormState() {
        const params = new URLSearchParams(window.location.search);
        if (!params.has('action')) return;

        ['date_from', 'date_to', 'active_from', 'active_to', 'cities'].forEach(name => {
            if (params.has(name)) {
                const input = document.querySelector(`[name="${name}"]`);
                if (input) input.value = params.get(name);
            }
        });

        if (params.has('group_by_type')) {
            const radio = document.querySelector(`[name="group_by_type"][value="${params.get('group_by_type')}"]`);
            if (radio) radio.checked = true;
        }

        ['categories[]', 'brands[]', 'age_groups[]', 'genders[]'].forEach(name => {
            const values = params.getAll(name);
            if (values.length > 0) {
                values.forEach(val => {
                    const cb = document.querySelector(`input[name="${name}"][value="${val}"]`);
                    if (cb) {
                        cb.checked = true;
                        if (cb.classList.contains('category-cb') && window.forceChildrenState) {
                            window.forceChildrenState(cb.getAttribute('data-id'), true);
                        }
                    }
                });
            }
        });

        if (params.has('all_brands') && params.get('all_brands') === '1') {
            const brandAllCb = document.getElementById('brand_all');
            if (brandAllCb) {
                brandAllCb.checked = true;
                // Zaznaczamy też wizualnie wszystkie dzieci, żeby UI było spójne
                document.querySelectorAll('.brand-cb').forEach(cb => cb.checked = true);
            }
        }

        if (params.has('all_time') && params.get('all_time') === '1') {
            const allTimeCb = document.getElementById('all_time_sales');
            if (allTimeCb) {
                allTimeCb.checked = true;
                allTimeCb.dispatchEvent(new Event('change')); // Wymusza wyszarzenie dat
            }
        }

        if (window.updateEntireTree) window.updateEntireTree();
        if (window.updateBrandAllCheckbox) window.updateBrandAllCheckbox();
    }



    // ==========================================
    // 2. WALIDACJA DAT
    // ==========================================
    function setupDateValidation(formId, fromName, toName, errorFeedbackId = null) {
        const form = document.getElementById(formId);
        if (!form) return;

        const fromInput = form.querySelector(`[name="${fromName}"]`);
        const toInput = form.querySelector(`[name="${toName}"]`);
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorFeedback = errorFeedbackId ? document.getElementById(errorFeedbackId) : null;
        const allTimeCheckbox = form.querySelector('input[name="all_time"]');

        if (allTimeCheckbox) {
            allTimeCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;
                // Włącza lub wyłącza (wyszarza) pola dat
                fromInput.disabled = isChecked;
                toInput.disabled = isChecked;

                if (isChecked) {
                    clearErrors(); // Czyści błędy, jeśli manager wcześniej wpisał złe daty
                } else {
                    validate(); // Sprawdza ponownie po odznaczeniu
                }
            });
        }

        function validate() {
            if (!fromInput.value || !toInput.value) { clearErrors(); return; }
            const dFrom = new Date(fromInput.value);
            const dTo = new Date(toInput.value);

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

    setupDateValidation('sales-filters', 'date_from', 'date_to');
    setupDateValidation('demo-filters', 'active_from', 'active_to', 'demo-date-error');

    restoreFormState();

    // ==========================================
    // 3. WALIDACJA WYSYŁKI I OPTYMALIZACJA
    // ==========================================
    const salesForm = document.getElementById('sales-filters');
    if (salesForm) {
        salesForm.addEventListener('submit', function (e) {
            const catCheckedCount = document.querySelectorAll('.category-cb:checked').length;
            const brandCheckedCount = document.querySelectorAll('.brand-cb:checked').length;
            let isValid = true;

            if (catCheckedCount === 0) {
                document.getElementById('cat-error').style.setProperty('display', 'block', 'important');
                document.getElementById('cat-container').classList.add('border-danger');
                isValid = false;
            } else {
                document.getElementById('cat-error').style.setProperty('display', 'none', 'important');
                document.getElementById('cat-container').classList.remove('border-danger');
            }

            if (brandCheckedCount === 0) {
                document.getElementById('brand-error').style.setProperty('display', 'block', 'important');
                document.getElementById('brand-container').classList.add('border-danger');
                isValid = false;
            } else {
                document.getElementById('brand-error').style.setProperty('display', 'none', 'important');
                document.getElementById('brand-container').classList.remove('border-danger');
            }

            const brandAllCb = document.getElementById('brand_all');
            if (brandAllCb.checked) {
                brandAllCb.setAttribute('name', 'all_brands');
                brandAllCb.value = '1';
                document.querySelectorAll('.brand-cb').forEach(cb => cb.removeAttribute('name'));
            } else {
                brandAllCb.removeAttribute('name');
                document.querySelectorAll('.brand-cb').forEach(cb => cb.setAttribute('name', 'brands[]'));
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                //sessionStorage.setItem('reportScrollPos', window.scrollY);
            }
        });
    }

    // ==========================================
    // 4. RESET FORMULARZA
    // ==========================================
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('reset', () => {
            setTimeout(() => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = false;
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const demoError = document.getElementById('demo-date-error');
                if (demoError) demoError.style.setProperty('display', 'none', 'important');

                if (window.updateEntireTree) window.updateEntireTree();
                if (window.updateBrandAllCheckbox) window.updateBrandAllCheckbox();
            }, 10);
        });
    });
});
