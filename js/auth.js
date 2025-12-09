document.addEventListener('DOMContentLoaded', () => {
    const showLoginButton = document.getElementById('show-login');
    const showRegisterButton = document.getElementById('show-register');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    const setActiveForm = (activeForm, inactiveForm, activeButton, inactiveButton) => {
        inactiveForm.classList.remove('active');
        activeForm.classList.add('active');
        inactiveButton.classList.remove('active');
        activeButton.classList.add('active');
    };

    showLoginButton.addEventListener('click', () => {
        setActiveForm(loginForm, registerForm, showLoginButton, showRegisterButton);
    });

    showRegisterButton.addEventListener('click', () => {
        setActiveForm(registerForm, loginForm, showRegisterButton, showLoginButton);
    });
    //--------------------------------

    const loginFormElement = loginForm.querySelector('form');
    const registerFormElement = registerForm.querySelector('form');

    const AUTH_API_URL = 'http://localhost:63342/extracker/backend/auth.php';

    const handleFormSubmit = async (event) => {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries()); // Düzgün işləyəcək, çünki HTML-də name="username" var

        try {
            const response = await fetch(AUTH_API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Uğurlu girişdən sonra istifadəçini yönləndir
                window.location.href = 'dashboard.html';
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('A network error occurred.');
        }
    };

    loginFormElement.addEventListener('submit', handleFormSubmit);
    registerFormElement.addEventListener('submit', handleFormSubmit);

});