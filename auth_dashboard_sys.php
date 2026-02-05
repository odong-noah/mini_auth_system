<?php
require_once 'header.php'; // header.php must call session_start()
if (!isset($_SESSION['user_id'])) {
    header("Location: auth_login_sys.php");
    exit();
}
?>

<!-- Add SweetAlert2 Library for the Notification Cards -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white p-5 rounded-4 border-start border-5 border-primary shadow-sm mb-4">
    <h2 class="fw-bold">Welcome, <?php echo clean_string($_SESSION['username']); ?>!</h2>
    <p class="text-muted mb-0">Role: <span class="badge bg-primary"><?php echo strtoupper($_SESSION['role']); ?></span></p>
</div>

<?php if ($_SESSION['role'] === 'admin'): ?>

<div class="card p-4 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 text-navy"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>ADMIN PANEL: USERS</h5>
        <button class="btn btn-primary btn-sm px-3 fw-bold" onclick="prepAdd()">+ ADD USER</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr><th>USERNAME</th><th>EMAIL</th><th>ROLE</th><th class="text-end">ACTIONS</th></tr>
            </thead>
            <tbody id="userTable"></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="adminForm" class="modal-content border-0">
            <div class="modal-header bg-dark text-white border-0"><h5 class="modal-title fw-bold" id="mTitle">USER RECORD</h5></div>
            <div class="modal-body p-4">
                <input type="hidden" id="m_id">
                <div class="mb-3"><label class="small fw-bold">Username</label><input type="text" id="m_u" class="form-control" required></div>
                <div class="mb-3"><label class="small fw-bold">Email</label><input type="email" id="m_e" class="form-control" required></div>
                <div class="mb-3"><label class="small fw-bold">Role</label>
                    <select id="m_r" class="form-select"><option value="user">User</option><option value="admin">Admin</option></select>
                </div>
                <div class="mb-1"><label class="small fw-bold">Password</label><input type="password" id="m_p" class="form-control"></div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0"><button type="submit" class="btn btn-primary w-100 py-2">SAVE CHANGES</button></div>
        </form>
    </div>
</div>

<script>
let uModal;

// Utility function to show success notifications
function notifySuccess(msg) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: msg,
        timer: 2000,
        showConfirmButton: false
    });
}

// Utility function to show error notifications
function notifyError(msg) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: msg
    });
}

function load() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "api/auth_admin_api.php?action=list", true);
    xhr.onload = function() {
        try {
            const res = JSON.parse(this.responseText);
            if(!res.success) return notifyError(res.message);
            let h = '';
            res.users.forEach(u => {
                h += `<tr>
                    <td class="fw-bold">${u.username}</td>
                    <td>${u.email}</td>
                    <td><span class="badge bg-secondary">${u.role}</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light border me-1" onclick='prepEdit(${JSON.stringify(u)})'><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="del(${u.id})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            });
            document.getElementById('userTable').innerHTML = h;
        } catch(e) { console.error("Data parse error"); }
    };
    xhr.send();
}

function prepAdd() {
    document.getElementById('mTitle').innerText="ADD USER";
    document.getElementById('m_id').value="";
    document.getElementById('adminForm').reset();
    uModal.show();
}

function prepEdit(u) {
    document.getElementById('mTitle').innerText="EDIT USER";
    document.getElementById('m_id').value=u.id;
    document.getElementById('m_u').value=u.username;
    document.getElementById('m_e').value=u.email;
    document.getElementById('m_r').value=u.role;
    uModal.show();
}

// Updated Delete function with Confirmation Card
function del(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete user'
    }).then((result) => {
        if (result.isConfirmed) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST","api/auth_admin_api.php?action=delete",true);
            xhr.setRequestHeader("Content-Type","application/json");
            xhr.onload = () => {
                notifySuccess('User has been deleted successfully.');
                load();
            };
            xhr.send(JSON.stringify({id:id}));
        }
    });
}

window.addEventListener('load', function() {
    uModal = new bootstrap.Modal(document.getElementById('userModal'));
    load();

    document.getElementById('adminForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('m_id').value;
        const actionType = id ? "update" : "add";
        
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "api/auth_admin_api.php?action=" + actionType, true);
        xhr.setRequestHeader("Content-Type","application/json");
        xhr.onload = ()=> { 
            const res = JSON.parse(xhr.responseText);
            if(res.success) {
                uModal.hide(); 
                load(); 
                notifySuccess(actionType === "add" ? "User added successfully!" : "User updated successfully!");
            } else {
                notifyError(res.message);
            }
        };
        xhr.send(JSON.stringify({
            id:id,
            username:document.getElementById('m_u').value,
            email:document.getElementById('m_e').value,
            role:document.getElementById('m_r').value,
            password:document.getElementById('m_p').value
        }));
    });
});
</script>

<?php else: ?>
<div class="card p-5 text-center shadow-sm border-0">
    <i class="bi bi-person-check-fill text-primary display-1 mb-3"></i>
    <h4 class="fw-bold">Standard Account Access</h4>
    <p class="text-muted">Hello standard user. Welcome to the system.</p>
</div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>