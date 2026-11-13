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

async function submitAccountRequest(endpoint, payload) {
    const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });

    let data;
    try {
        data = await response.json();
    } catch (error) {
        throw new Error(
            `The PHP endpoint returned an invalid response (HTTP ${response.status}). Check that Apache is running and review its error log.`
        );
    }

    if (!response.ok && !data.message) {
        throw new Error(`The PHP request failed (HTTP ${response.status}).`);
    }

    return data;
}

function setAccountMessage(element, message, status = 'error') {
    element.textContent = message;
    element.classList.toggle('success', status === 'success');
    element.classList.toggle('error', status === 'error');
}

// Handle account registration.
function handleRegister(e) {
    e.preventDefault();
    
    const nameEl = document.getElementById('register-name');
    const emailEl = document.getElementById('register-email');
    const passEl = document.getElementById('register-password');
    const confirmEl = document.getElementById('confirm-password') || document.getElementById('register-confirm-password');
    const msgEl = document.getElementById('register-message');

    const name = nameEl ? nameEl.value.trim() : "";
    const email = emailEl ? emailEl.value.trim() : "";
    const password = passEl ? passEl.value : "";
    const confirmPassword = confirmEl ? confirmEl.value : "";

    if (!name || !email || !password || !confirmPassword) {
        setAccountMessage(msgEl, "Please complete all fields.");
        return;
    }

    if (password !== confirmPassword) {
        setAccountMessage(msgEl, "Passwords do not match.");
        return;
    }

    if (password.length < 8) {
        setAccountMessage(msgEl, "Password must be at least 8 characters.");
        return;
    }

    submitAccountRequest('../PHP/register.php', {
            name: name,
            email: email,
            password: password,
            confirmPassword: confirmPassword
    })
    .then(data => {
        if (data.success) {
            setAccountMessage(
                msgEl,
                data.message || "Account created successfully! Redirecting...",
                "success"
            );
            setTimeout(() => {
                window.location.href = 'Profile.php';
            }, 1000);
        } else {
            setAccountMessage(msgEl, data.message || data.error || "Registration failed.");
        }
    })
    .catch(err => {
        console.error("Registration error:", err);
        setAccountMessage(msgEl, err.message || "An error occurred. Please try again.");
    });
}

// Handle account sign-in.
function handleLogin(e) {
    e.preventDefault();

    const emailEl = document.getElementById('login-email');
    const passEl = document.getElementById('login-password');
    const msgEl = document.getElementById('login-message');

    const email = emailEl ? emailEl.value.trim() : "";
    const password = passEl ? passEl.value : "";

    if (!email || !password) {
        setAccountMessage(msgEl, "Please complete all fields.");
        return;
    }

    submitAccountRequest('../PHP/login.php', {
        email: email,
        password: password
    })
    .then(data => {
        if (data.success) {
            setAccountMessage(
                msgEl,
                data.message || "Login successful! Redirecting...",
                "success"
            );
            setTimeout(() => {
                window.location.href = 'Profile.php';
            }, 1000);
        } else {
            setAccountMessage(msgEl, data.message || data.error || "Invalid email or password.");
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        setAccountMessage(msgEl, err.message || "An error occurred. Please try again.");
    });
}

function initializeProfilePage() {
    const form = document.getElementById('profileUpdateForm');
    if (!form) return;

    const nameInput = document.getElementById('fullName');
    const phoneInput = document.getElementById('phoneNumber');
    const emailInput = document.getElementById('userEmail');
    const notificationsInput = document.getElementById('notifications');
    const nameDisplay = document.getElementById('profile-name-display');
    const emailDisplay = document.getElementById('displayEmailSub');
    const message = document.getElementById('profile-message');

    fetch('../PHP/user.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Could not load your profile (HTTP ${response.status}).`);
            }
            return response.json();
        })
        .then(profile => {
            if (!profile.logged_in) {
                window.location.href = 'account.php';
                return;
            }

            nameInput.value = profile.full_name || '';
            phoneInput.value = profile.phone || '';
            emailInput.value = profile.email || '';
            emailDisplay.textContent = profile.email || '';
            notificationsInput.checked = Boolean(profile.notifications);
            nameDisplay.textContent = profile.full_name || '';
        })
        .catch(error => {
            console.error("Profile loading error:", error);
            message.textContent = error.message || "Could not load your profile.";
            message.className = 'form-message error';
        });

    form.addEventListener('submit', event => {
        event.preventDefault();
        message.textContent = '';
        message.className = 'form-message';

        submitAccountRequest('../PHP/update_profile.php', {
            name: nameInput.value.trim(),
            phone: phoneInput.value.trim(),
            notifications: notificationsInput.checked
        })
            .then(response => {
                message.textContent = response.message || (
                    response.success
                        ? "Profile updated."
                        : "Could not update your profile."
                );
                message.className = `form-message ${response.success ? 'success' : 'error'}`;
                if (response.success) {
                    nameDisplay.textContent = nameInput.value.trim();
                }
            })
            .catch(error => {
                console.error("Profile update error:", error);
                message.textContent = error.message || "Could not update your profile.";
                message.className = 'form-message error';
            });
    });
}

initializeProfilePage();