// Udostępniamy funkcje globalnie dla innych skryptów (np. dla resetu i odtwarzania stanu)
window.forceChildrenState = function (parentId, isChecked) {
    document.querySelectorAll(`.category-cb[data-parent="${parentId}"]`).forEach(child => {
        child.checked = isChecked;
        child.indeterminate = false;
        window.forceChildrenState(child.getAttribute('data-id'), isChecked);
    });
};

window.updateEntireTree = function () {
    const roots = document.querySelectorAll('.category-cb[data-parent="0"]');
    roots.forEach(root => evaluateNode(root));
    updateCatAllCheckbox();
    optimizePayload();
};

window.updateBrandAllCheckbox = function () {
    const brandAllCheckbox = document.getElementById('brand_all');
    if (!brandAllCheckbox) return;

    const brandCheckboxes = document.querySelectorAll('.brand-cb');
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
};

// Funkcje prywatne dla tego pliku
function evaluateNode(node) {
    const id = node.getAttribute('data-id');
    const children = document.querySelectorAll(`.category-cb[data-parent="${id}"]`);
    if (children.length === 0) return node.checked ? 1 : 0;

    let checkedCount = 0;
    let indeterminateCount = 0;

    children.forEach(child => {
        const state = evaluateNode(child);
        if (state === 1) checkedCount++;
        if (state === 0.5) indeterminateCount++;
    });

    if (checkedCount === children.length && children.length > 0) {
        node.checked = true; node.indeterminate = false; return 1;
    } else if (checkedCount > 0 || indeterminateCount > 0) {
        node.checked = false; node.indeterminate = true; return 0.5;
    } else {
        node.checked = false; node.indeterminate = false; return 0;
    }
}

function updateCatAllCheckbox() {
    const catAllCheckbox = document.getElementById('cat_all');
    if (!catAllCheckbox) return;
    const roots = document.querySelectorAll('.category-cb[data-parent="0"]');

    let checkedCount = 0; let indeterminateCount = 0;
    roots.forEach(root => {
        if (root.checked) checkedCount++;
        if (root.indeterminate) indeterminateCount++;
    });

    if (checkedCount === roots.length && roots.length > 0) {
        catAllCheckbox.checked = true; catAllCheckbox.indeterminate = false;
    } else if (checkedCount > 0 || indeterminateCount > 0) {
        catAllCheckbox.checked = false; catAllCheckbox.indeterminate = true;
    } else {
        catAllCheckbox.checked = false; catAllCheckbox.indeterminate = false;
    }
}

function optimizePayload() {
    document.querySelectorAll('.category-cb').forEach(cb => cb.setAttribute('name', 'categories[]'));
    document.querySelectorAll('.category-cb:checked').forEach(checkedNode => {
        removeNameFromDescendants(checkedNode.getAttribute('data-id'));
    });
}

function removeNameFromDescendants(parentId) {
    document.querySelectorAll(`.category-cb[data-parent="${parentId}"]`).forEach(child => {
        child.removeAttribute('name');
        removeNameFromDescendants(child.getAttribute('data-id'));
    });
}

// Inicjalizacja zdarzeń po załadowaniu DOM
document.addEventListener('DOMContentLoaded', () => {
    const catAllCheckbox = document.getElementById('cat_all');
    const categoryCheckboxes = document.querySelectorAll('.category-cb');
    const brandAllCheckbox = document.getElementById('brand_all');
    const brandCheckboxes = document.querySelectorAll('.brand-cb');

    if (catAllCheckbox) {
        catAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.category-cb[data-parent="0"]').forEach(root => {
                root.checked = isChecked;
                window.forceChildrenState(root.getAttribute('data-id'), isChecked);
            });
            window.updateEntireTree();
        });
    }

    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            window.forceChildrenState(this.getAttribute('data-id'), this.checked);
            window.updateEntireTree();
        });
    });

    if (brandAllCheckbox) {
        brandAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            brandCheckboxes.forEach(cb => cb.checked = isChecked);
        });
    }

    brandCheckboxes.forEach(cb => cb.addEventListener('change', window.updateBrandAllCheckbox));

    // Strzałki drzewka (Bootstrap Collapse)
    document.querySelectorAll('.collapse').forEach(collapseEl => {
        collapseEl.addEventListener('show.bs.collapse', function (e) {
            if (e.target === this) {
                const icon = document.querySelector(`[data-bs-target="#${this.id}"]`);
                if (icon) { icon.classList.remove('bi-chevron-right'); icon.classList.add('bi-chevron-down'); }
            }
        });
        collapseEl.addEventListener('hide.bs.collapse', function (e) {
            if (e.target === this) {
                const icon = document.querySelector(`[data-bs-target="#${this.id}"]`);
                if (icon) { icon.classList.remove('bi-chevron-down'); icon.classList.add('bi-chevron-right'); }
            }
        });
    });
});
