const form = document.getElementById("registerForm");
const strengthBar = document.getElementById("strength-bar");
const strengthText = document.getElementById("strength-text");

// Password strength function
function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    strengthBar.style.width = (strength * 25) + "%";

    switch (strength) {
        case 0:
            strengthBar.style.background = "#ccc";
            strengthText.textContent = "";
            break;
        case 1:
            strengthBar.style.background = "red";
            strengthText.textContent = "Very Weak";
            break;
        case 2:
            strengthBar.style.background = "orange";    
            strengthText.textContent = "Weak";
            break;
        case 3:
            strengthBar.style.background = "yellowgreen";
            strengthText.textContent = "Medium";
            break;
        case 4:
            strengthBar.style.background = "green";
            strengthText.textContent = "Strong";
            break;
    }
}

// Update strength bar while typing
document.getElementById("password").addEventListener("input", function () {
    checkPasswordStrength(this.value);
});

// Form submission with validation
form.addEventListener("submit", function (event) {
    event.preventDefault(); // temporarily prevent submission for validation
    let valid = true;

    // Name Validation
    const name = document.getElementById("name").value.trim();
    if (name.length < 3) {
        document.getElementById("nameError").textContent = "Name must be at least 3 characters.";
        valid = false;
    } else {
        document.getElementById("nameError").textContent = "";
    }

    // Email Validation
    const email = document.getElementById("email").value.trim();
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        document.getElementById("emailError").textContent = "Enter a valid email.";
        valid = false;
    } else {
        document.getElementById("emailError").textContent = "";
    }

    // Phone Validation
    const phone = document.getElementById("phone").value.trim();
    if (!/^\d{10}$/.test(phone)) {
        document.getElementById("phoneError").textContent = "Enter a valid 10-digit phone number.";
        valid = false;
    } else {
        document.getElementById("phoneError").textContent = "";
    }

    // Username Validation
    const username = document.getElementById("username").value.trim();
    if (username.length < 4) {
        document.getElementById("usernameError").textContent = "Username must be at least 4 characters.";
        valid = false;
    } else {
        document.getElementById("usernameError").textContent = "";
    }

    // Password Validation
    const password = document.getElementById("password").value.trim();
    if (password.length < 8) {
        document.getElementById("passwordError").textContent = "Password must be at least 8 characters.";
        valid = false;
    } else {
        document.getElementById("passwordError").textContent = "";
    }

    // Confirm Password Validation
    const confirmPassword = document.getElementById("confirmPassword").value.trim();
    if (password !== confirmPassword) {
        document.getElementById("confirmPasswordError").textContent = "Passwords do not match.";
        valid = false;
    } else {
        document.getElementById("confirmPasswordError").textContent = "";
    }

    // If valid, submit the form to PHP
    if (valid) {
        form.submit(); // <-- submits to submit_registration.php
    }
});
