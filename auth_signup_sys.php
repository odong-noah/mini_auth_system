<?php require_once 'header.php'; ?>

<div class="row align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="col-md-5 px-4">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold" style="color: #0f172a;">SignUp</h4>

                    <hr>
                </div>

                <div id="alertBox"></div>

                <form id="signupForm" autocomplete="off">
                    <!-- Autofill Trap -->
                    <div style="display:none;">
                        <input type="text" name="trap_u">
                        <input type="password" name="trap_p">
                    </div>

                    <div class="mb-3">
                        <label for="s_u" class="fw-bold small mb-1">USERNAME</label>
                        <input type="text" id="s_u" class="form-control" autocomplete="off" value="" required>
                    </div>

                    <div class="mb-3">
                        <label for="s_e" class="fw-bold small mb-1">EMAIL ADDRESS</label>
                        <input type="email" id="s_e" class="form-control" autocomplete="off" value="" required>
                    </div>

                    <div class="mb-3">
                        <label for="s_r" class="fw-bold small mb-1">ACCOUNT ROLE</label>
                        <select id="s_r" class="form-select" required>
                            <option value="" disabled selected>Choose Role</option>
                            <option value="user">Normal User</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="s_p" class="fw-bold small mb-1">PASSWORD</label>
                        <input type="password" id="s_p" class="form-control" autocomplete="new-password" value="" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" id="regBtn">
                        REGISTER ACCOUNT
                    </button>

                    <div class="mt-3 text-center small">Already have an account?<a href="auth_login_sys.php" class="text-decoration-none fw-bold">Login here</a></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function nuclearWipe() {
    const form = document.getElementById('signupForm');
    if(form) form.reset();
    document.getElementById('s_u').value = "";
    document.getElementById('s_e').value = "";
    document.getElementById('s_p').value = "";
}

window.addEventListener('load', () => setTimeout(nuclearWipe, 50));

document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('regBtn');
    const ab = document.getElementById('alertBox');
    btn.disabled = true;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "api/auth_register_api.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    
    xhr.onreadystatechange = function() {
        if (this.readyState === 4) {
            btn.disabled = false;
            try {
                const res = JSON.parse(this.responseText);
                ab.innerHTML = `<div class="alert alert-${res.success ? 'success' : 'danger'} py-2 small border-0 shadow-sm mb-4">${res.message}</div>`;
                if(res.success) {
                    nuclearWipe();
                    setTimeout(() => { window.location.href = 'auth_login_sys.php'; }, 1000);
                }
            } catch(e) { 
                ab.innerHTML = `<div class="alert alert-danger py-2 small">System Error</div>`;
            }
        }
    };
    xhr.send(JSON.stringify({
        username: document.getElementById('s_u').value,
        email: document.getElementById('s_e').value,
        role: document.getElementById('s_r').value,
        password: document.getElementById('s_p').value
    }));
});
</script>
