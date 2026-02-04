<?php require_once 'dbconn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini_Auth_System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --sp-navy: #0f172a; --sp-blue: #3b82f6; }
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: var(--sp-navy); border-bottom: 4px solid var(--sp-blue); }
        .card { border: none; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .btn-primary { background: var(--sp-blue); border: none; font-weight: 600; padding: 12px; }
        label { font-weight: 600; color: #475569; margin-bottom: 5px; display: block; }
        .form-control, .form-select { border-radius: 8px; padding: 12px; }
    </style>
</head>

<body>
<nav class="navbar navbar-dark mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-cpu-fill text-info me-2"></i>TECH</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn btn-outline-light btn-sm px-3">SIGN OUT</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container">