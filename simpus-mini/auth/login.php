<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
include '../includes/header.php';
?>

<div class="container mt-5" style="max-width: 400px;">
    <h2>Login Petugas</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    
    <form action="proses_login.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <!-- Tambahan Checkbox Remember Me -->
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember_me" class="form-check-input" id="remember_me">
            <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>