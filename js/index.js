document.addEventListener("DOMContentLoaded", () => {

    // --- ELEMENTS ---
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const loginMsg = document.getElementById("login-message");
    const registerMsg = document.getElementById("register-message");

    const loginTab = document.getElementById("show-login");
    const registerTab = document.getElementById("show-register");
    const loginFormWrapper = document.getElementById("login-form");
    const registerFormWrapper = document.getElementById("register-form");

    // --- FORM SWITCH ---
    const setActiveForm = (activeFormWrapper, inactiveFormWrapper, activeTab, inactiveTab) => {
        activeFormWrapper.classList.add("active");
        inactiveFormWrapper.classList.remove("active");

        activeTab.classList.add("active");
        inactiveTab.classList.remove("active");

        // clear messages
        loginMsg.style.display = "none";
        registerMsg.style.display = "none";
    };

    loginTab.addEventListener("click", () => {
        setActiveForm(loginFormWrapper, registerFormWrapper, loginTab, registerTab);
    });

    registerTab.addEventListener("click", () => {
        setActiveForm(registerFormWrapper, loginFormWrapper, registerTab, loginTab);
    });

    // --- HELPER: SHOW MESSAGE ---
    const showMsg = (element, message, type) => {
        element.textContent = message;
        element.className = type === "success" ? "success-message" : "error-message";
        element.style.display = "block";
    };

    // --- LOGIN FORM SUBMIT ---
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(loginForm);
        const res = await fetch("php/auth.php", { method: "POST", body: formData });
        const data = await res.json();

        showMsg(loginMsg, data.message, data.status);

        if (data.status === "success") {
            // redirect after short delay for UX
            setTimeout(() => window.location.href = "php/dashboard.php", 500);
        }
    });

    // --- REGISTER FORM SUBMIT ---
    registerForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(registerForm);
        const res = await fetch("php/auth.php", { method: "POST", body: formData });
        const data = await res.json();

        showMsg(registerMsg, data.message, data.status);

        if (data.status === "success") {
            // switch to login form after success
            setTimeout(() => setActiveForm(loginFormWrapper, registerFormWrapper, loginTab, registerTab), 500);
        }
    });

});
