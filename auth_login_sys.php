<?php require_once 'header.php'; ?>

<div class="row align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5 px-4">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold" style="color: #0f172a;">Login</h4>
                    <p class="text-muted small">Login to Access the system</p>
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
                        <input type="password" id="l_p" class="form-control" autocomplete="new-password" required>
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
            const res = JSON.parse(this.responseText);
            if (res.success) { window.location.href = res.redirect; }
            else { document.getElementById('alertBox').innerHTML = `<div class="alert alert-danger py-2 small border-0">${res.message}</div>`; }
        }
    };
    xhr.send(JSON.stringify({
        username: document.getElementById('l_u').value,
        password: document.getElementById('l_p').value
    }));
});
</script>
<?php require_once 'footer.php'; ?>