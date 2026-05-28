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
});

