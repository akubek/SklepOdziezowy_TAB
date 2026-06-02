document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. ZARZĄDZANIE DRZEWKIEM CHECKBOXÓW
    // ==========================================
    const categoryCheckboxes = document.querySelectorAll('.category-cb');
    const allCheckbox = document.getElementById('cat_all');

    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const isChecked = this.checked;
            const id = this.getAttribute('data-id');
            const parentId = this.getAttribute('data-parent');

            checkChildren(id, isChecked);

            if (!isChecked && parentId !== '0') {
                uncheckParents(parentId);
            }
        });
    });

    function checkChildren(parentId, isChecked) {
        document.querySelectorAll(`.category-cb[data-parent="${parentId}"]`).forEach(child => {
            child.checked = isChecked;
            checkChildren(child.getAttribute('data-id'), isChecked);
        });
    }

    function uncheckParents(parentId) {
        if (parentId === '0') return;
        const parentCb = document.querySelector(`.category-cb[data-id="${parentId}"]`);
        if (parentCb) {
            parentCb.checked = false;
            uncheckParents(parentCb.getAttribute('data-parent'));
        }
    }

    if (allCheckbox) {
        allCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            categoryCheckboxes.forEach(cb => cb.checked = isChecked);
        });
    }

    // ==========================================
    // 2. ANIMACJA STRZAŁEK DRZEWKA (Ikony Bootstrap)
    // ==========================================
    // Nasłuchujemy zdarzeń pokazywania i ukrywania kontenerów 'collapse' z Bootstrapa
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
});
