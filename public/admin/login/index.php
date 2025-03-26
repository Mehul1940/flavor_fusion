<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = parse_input($_POST['email']);
    $password = parse_input($_POST['password']);

    if (empty($email) || empty($password)) {
        show_alert("Please enter both email and password.");
    } else {
        $admin = $connection->findOne("users", ["email" => $email]);
        if ($admin && password_verify($password, $admin["password"])) {
            login($admin);
            redirect("admin");
        } else {
            show_alert("Invalid email or password.");
        }
    }
}
?>

<?php include_once "../../../includes/header.php" ?>
<div class="auth-container">
    <div class="col-md-6 d-none d-md-block p-0 auth-image">
        <div class="auth-image-content">
            <h2 class="display-4 fw-bold">Admin Login</h2>
            <p class="lead">Sign in to manage the FlavorFusion dashboard.</p>
        </div>
    </div>

    <div class="col-md-6 auth-form-container">
        <div class="auth-form">
            <div class="auth-logo text-center">
                <h1><span style="color: var(--primary-color);">Flavor</span><span style="color: var(--primary-blue);">Fusion</span></h1>
                <p class="text-muted">Admin Panel Access</p>
            </div>
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" id="email" placeholder="admin@example.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
            </form>

            <div class="auth-footer">
                <p><a href="<?= ROOT . "forgot-password" ?>">Forgot password?</a></p>
            </div>
        </div>
    </div>
</div>
<?php include_once "../../../includes/admin-footer.php" ?>