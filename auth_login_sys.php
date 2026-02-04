<?php require_once 'header.php'; ?>

<div class="row align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5 px-4">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold i  text-info me-2" style="color: #0f172a;">Login</h4>
                    <p class="text-muted color: #0f172a; small">Login to Access the system</p>
                    <hr>
                </div>
                <div id="alertBox"></div>
                <form id="loginForm" autocomplete="off">
                    <div class="mb-3">
                        <label for="l_u" class="fw-bold small">USERNAME</label>
                        <input type="text" id="l_u" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="l_p" class="fw-bold small">PASSWORD</label>
                        <!-- Added Input Group for Eye Icon Toggle -->
                        <div class="input-group">
                            <input type="password" id="l_p" class="form-control" autocomplete="new-password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleLoginPass()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" id="logBtn">Login</button>
                    <div class="mt-4 text-center small">Don't have an account?
                        <a href="auth_signup_sys.php" class="text-decoration-none fw-bold">Create account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Intuitive Eye Icon Toggle Function
function toggleLoginPass() {
    const p = document.getElementById('l_p');
    const icon = document.getElementById('eyeIcon');
    if (p.type === "password") {
        p.type = "text";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        p.type = "password";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

window.addEventListener('load', function() {
    document.getElementById('loginForm').reset();
    document.getElementById('l_u').value = "";
    document.getElementById('l_p').value = "";
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "api/auth_login_api.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (this.readyState === 4) {
            try {
                const res = JSON.parse(this.responseText);
                if (res.success) { 
                    window.location.href = res.redirect; 
                } else { 
                    document.getElementById('alertBox').innerHTML = `<div class="alert alert-danger py-2 small border-0">${res.message}</div>`; 
                }
            } catch (e) {
                console.error("Auth Error: Could not parse server response");
            }
        }
    };
    xhr.send(JSON.stringify({
        username: document.getElementById('l_u').value,
        password: document.getElementById('l_p').value
    }));
});
</script>