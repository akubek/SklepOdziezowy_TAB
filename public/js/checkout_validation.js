document.addEventListener('DOMContentLoaded', () => {
    const checkoutForm = document.getElementById('checkout-form');

    if (!checkoutForm) return;

    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const email = document.getElementById('email');
    const street = document.getElementById('street');
    const city = document.getElementById('city');
    const zipCode = document.getElementById('zip_code');
    const paczkomat = document.getElementById('paczkomat');
    const submitBtn = document.getElementById('checkout-btn');

    const checkEmail = (value) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(value.trim().toLowerCase());
    };

    const checkZipCode = (value) => {
        const re = /^\d{2}-\d{3}$/;
        return re.test(value.trim());
    };

    const checkPaczkomat = (value) => {
        // Standard InPost locker code format: 3 letters followed by numbers (and optional suffix)
        // Common format: AAA01, AAA01A, AAA01N etc.
        const re = /^[A-Z]{3}\d{2,5}[A-Z]?$/i;
        return re.test(value.trim());
    };

    const toggleClasses = (input, isValid) => {
        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    };

    const validate = () => {
        let isFormValid = true;

        // First Name: max 32
        const isFirstNameValid = firstName.value.trim().length > 0 && firstName.value.trim().length <= 32;
        toggleClasses(firstName, isFirstNameValid);
        if (!isFirstNameValid) isFormValid = false;

        // Last Name: max 32
        const isLastNameValid = lastName.value.trim().length > 0 && lastName.value.trim().length <= 32;
        toggleClasses(lastName, isLastNameValid);
        if (!isLastNameValid) isFormValid = false;

        // Email
        const isEmailValid = checkEmail(email.value);
        toggleClasses(email, isEmailValid);
        if (!isEmailValid) isFormValid = false;

        // Street: max 64
        const isStreetValid = street.value.trim().length > 0 && street.value.trim().length <= 64;
        toggleClasses(street, isStreetValid);
        if (!isStreetValid) isFormValid = false;

        // City: max 32
        const isCityValid = city.value.trim().length > 0 && city.value.trim().length <= 32;
        toggleClasses(city, isCityValid);
        if (!isCityValid) isFormValid = false;

        // Zip Code
        const isZipValid = checkZipCode(zipCode.value);
        toggleClasses(zipCode, isZipValid);
        if (!isZipValid) isFormValid = false;

        // Paczkomat
        const isPaczkomatValid = checkPaczkomat(paczkomat.value);
        toggleClasses(paczkomat, isPaczkomatValid);
        if (!isPaczkomatValid) isFormValid = false;

        submitBtn.disabled = !isFormValid;
    };

    // Initial validation
    validate();

    [firstName, lastName, email, street, city, zipCode, paczkomat].forEach(input => {
        input.addEventListener('input', validate);
        input.addEventListener('blur', validate);
    });
});
