// public/js/profile.js
document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('editProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const profileInputs = document.querySelectorAll('.profile-input');
    const profileActions = document.getElementById('profileActions');

    if (editBtn && profileActions && profileInputs && profileActions) {
        // Zapisujemy oryginalne wartości, aby móc do nich wrócić po kliknięciu "Anuluj"
        const originalValues = {};
        profileInputs.forEach(input => {
            originalValues[input.name] = input.value;
        });

        // Po kliknięciu "Edytuj dane"
        editBtn.addEventListener('click', function () {
            // Odblokowujemy wszystkie pola (usuwamy 'disabled')
            profileInputs.forEach(input => input.removeAttribute('disabled'));

            // Pokazujemy przyciski Zapisz/Anuluj, ukrywamy przycisk Edytuj
            profileActions.style.display = 'block';
            editBtn.style.display = 'none';

            // Automatycznie ustawiamy kursor w pierwszym polu (Imię)
            profileInputs[0].focus();
        });

        // Po kliknięciu "Anuluj"
        cancelBtn.addEventListener('click', function () {
            profileInputs.forEach(input => {
                // Przywracamy oryginalne wartości
                input.value = originalValues[input.name];
                // Ponownie blokujemy pola
                input.setAttribute('disabled', 'disabled');
            });

            // Ukrywamy Zapisz/Anuluj, pokazujemy z powrotem przycisk Edytuj
            profileActions.style.display = 'none';
            editBtn.style.display = 'block';
        });

    }
    const currentPasswordInput = document.querySelector('input[name="current_password"]');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');

    if (newPasswordInput && confirmPasswordInput && currentPasswordInput) {
        const passwordForm = newPasswordInput.closest('form');
        const submitPasswordBtn = passwordForm.querySelector('button[type="submit"]');

        // Domyślnie blokujemy przycisk
        submitPasswordBtn.disabled = true;

        const validatePasswordChange = () => {
            const currentLen = currentPasswordInput.value.length;
            const newLen = newPasswordInput.value.length;

            // 1. Stare hasło po prostu musi być wpisane (nie puste)
            const isCurrentValid = currentLen > 0;

            // 2. Nowe hasło musi mieć od 8 do 72 znaków
            const isNewValid = newLen >= 8 && newLen <= 64;

            // 3. Powtórzenie musi być takie samo jak nowe
            const isConfirmValid = confirmPasswordInput.value === newPasswordInput.value && newLen > 0;

            // Logika podświetlania na zielono/czerwono (klasy z Bootstrapa)
            if (newLen > 0) {
                newPasswordInput.classList.toggle('is-valid', isNewValid);
                newPasswordInput.classList.toggle('is-invalid', !isNewValid);
            } else {
                newPasswordInput.classList.remove('is-valid', 'is-invalid');
            }

            if (confirmPasswordInput.value.length > 0) {
                confirmPasswordInput.classList.toggle('is-valid', isConfirmValid);
                confirmPasswordInput.classList.toggle('is-invalid', !isConfirmValid);
            } else {
                confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            }

            // Przycisk aktywny tylko gdy wszystko jest OK
            submitPasswordBtn.disabled = !(isCurrentValid && isNewValid && isConfirmValid);
        };

        // Podpinamy nasłuchiwanie na każde wpisanie znaku
        currentPasswordInput.addEventListener('input', validatePasswordChange);
        newPasswordInput.addEventListener('input', validatePasswordChange);
        confirmPasswordInput.addEventListener('input', validatePasswordChange);
    }
});

