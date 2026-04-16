document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    if (!form) return; // Uruchamiaj tylko na stronie rejestracji

    const submitBtn = document.getElementById('register-btn');
    const inputs = form.querySelectorAll('input');

    // Funkcja weryfikująca pojedyncze pole
    const validateInput = (input) => {
        let isValid = true;

        if (input.id === 'email') {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = re.test(input.value.trim());
        } else if (input.id === 'password') {
            isValid = input.value.length >= 8;
        } else {
            // Walidacja imienia i nazwiska (nie mogą być puste)
            isValid = input.value.trim().length > 0;
        }

        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }

        return isValid;
    }
    const checkForm = () => {
        let isFormValid = true;
        inputs.forEach(input => {
            if (input.value.trim() === '' || input.classList.contains('is-invalid')) {
                isFormValid = false;
            }
        });
        submitBtn.disabled = !isFormValid;
    };

    // Przypisanie zdarzeń do każdego pola
    inputs.forEach(input => {
        // Sprawdź w trakcie pisania
        input.addEventListener('input', () => {
            validateInput(input);
            checkForm();
        });
        // Sprawdź przy kliknięciu poza pole
        input.addEventListener('blur', () => {
            validateInput(input);
            checkForm();
        });
    });
});