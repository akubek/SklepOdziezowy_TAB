document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    // neither login form nor register form
    if (!loginForm && !registerForm) { 
        return; 
    }

    //shared helper functions
    const checkEmail = (input) => {
        let cleanEmail = input.value.trim().toLowerCase();
        input.value = cleanEmail;
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(cleanEmail);
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

    //login form check
    if (loginForm) {
        const submitBtn = loginForm.querySelector('button[type="submit"]');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        // submit button is enabled by default in form in case the script doesn't load
        // if the script was properly loaded disable the submit button because:
        // fresh page should have empty fields -> invalid form -> disable submit
        submitBtn.disabled = true;

        const validateLogin = () => {
            const isEmailValid = checkEmail(emailInput);
            const isPasswordValid = passwordInput.value.trim().length > 0;
    
            toggleClasses(emailInput, isEmailValid);
            toggleClasses(passwordInput, isPasswordValid);

            submitBtn.disabled = !(isEmailValid && isPasswordValid);
        };

        emailInput.addEventListener('input', validateLogin);
        emailInput.addEventListener('blur', validateLogin);
        passwordInput.addEventListener('input', validateLogin);
        passwordInput.addEventListener('change', validateLogin);
        passwordInput.addEventListener('blur', validateLogin);
        
    }
    
    //register form check
    if (registerForm) {
        const submitBtn = registerForm.querySelector('button[type="submit"]');
        const inputs = registerForm.querySelectorAll('input');

        const validateRegister = () => {
            let isFormValid = true;

            inputs.forEach(input => {
                let isValid = true;
                if (input.id === 'email') {
                    isValid = checkEmail(input);
                } else if (input.id === 'password') {
                    isValid = input.value.length >= 8;
                } else {
                    isValid = input.value.trim().length > 0; // Imię i nazwisko
                }

                toggleClasses(input, isValid);
                
                if (!isValid) isFormValid = false;
            });

            submitBtn.disabled = !isFormValid;
        };

        inputs.forEach(input => {
            input.addEventListener('input', validateRegister);
            input.addEventListener('blur', validateRegister);
        });
    }

});
