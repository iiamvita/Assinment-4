function showRegister() {
    document.getElementById('register-form').classList.remove('hidden');
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('register-tab').classList.add('active');
    document.getElementById('login-tab').classList.remove('active');
}

function showLogin() {
    document.getElementById('login-form').classList.remove('hidden');
    document.getElementById('register-form').classList.add('hidden');
    document.getElementById('login-tab').classList.add('active');
    document.getElementById('register-tab').classList.remove('active');
}

function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Hide';
    } else {
        input.type = 'password';
        btn.textContent = 'Show';
    }
}

// Handle Registration AJAX
function handleRegister(e) {
    e.preventDefault();
    
    const nameEl = document.getElementById('register-name');
    const emailEl = document.getElementById('register-email');
    const passEl = document.getElementById('register-password');
    // Fallback check for confirm password ID
    const confirmEl = document.getElementById('confirm-password') || document.getElementById('register-confirm-password');
    const msgEl = document.getElementById('register-message');

    // Debugging: Press F12 -> Console to see if any element is missing
    console.log("Register Form Elements:", {
        name: nameEl ? nameEl.value : "MISSING ID",
        email: emailEl ? emailEl.value : "MISSING ID",
        password: passEl ? passEl.value : "MISSING ID",
        confirmPassword: confirmEl ? confirmEl.value : "MISSING ID"
    });

    const name = nameEl ? nameEl.value.trim() : "";
    const email = emailEl ? emailEl.value.trim() : "";
    const password = passEl ? passEl.value.trim() : "";
    const confirmPassword = confirmEl ? confirmEl.value.trim() : "";

    msgEl.style.color = "#d9534f";

    if (!name || !email || !password || !confirmPassword) {
        msgEl.textContent = "Please complete all fields.";
        return;
    }

    if (password !== confirmPassword) {
        msgEl.textContent = "Passwords do not match.";
        return;
    }

    if (password.length < 8) {
        msgEl.textContent = "Password must be at least 8 characters.";
        return;
    }

    fetch('../PHP/register.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify({
            name: name,
            email: email,
            password: password,
            confirmPassword: confirmPassword
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgEl.style.color = "#28a745";
            msgEl.textContent = data.message || "Account created successfully! Redirecting...";
            setTimeout(() => {
                window.location.href = 'profile.php';
            }, 1000);
        } else {
            msgEl.textContent = data.message || data.error || "Registration failed.";
        }
    })
    .catch(err => {
        console.error("Registration error:", err);
        msgEl.textContent = "An error occurred. Please try again.";
    });
}

// Handle Login AJAX
function handleLogin(e) {
    e.preventDefault();

    const emailEl = document.getElementById('login-email');
    const passEl = document.getElementById('login-password');
    const msgEl = document.getElementById('login-message');

    const email = emailEl ? emailEl.value.trim() : "";
    const password = passEl ? passEl.value.trim() : "";

    msgEl.style.color = "#d9534f";

    if (!email || !password) {
        msgEl.textContent = "Please complete all fields.";
        return;
    }

    fetch('../PHP/login.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgEl.style.color = "#28a745";
            msgEl.textContent = data.message || "Login successful! Redirecting...";
            setTimeout(() => {
                window.location.href = 'profile.php';
            }, 1000);
        } else {
            msgEl.textContent = data.message || data.error || "Invalid email or password.";
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        msgEl.textContent = "An error occurred. Please try again.";
    });
}