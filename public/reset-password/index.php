<?php
include_once '../../init.php';
include_once DB_ROOT . 'database.php';

enable_unprotected_route();

$errors = [];
$user_info = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = validate_user_info();
    $user_info["password"] = parse_input($_POST["password"]);
    $user_info["confirm_password"] = parse_input($_POST["confirm_password"]);
    $token = parse_input($_GET["token"]);

    if (count($errors) === 0) {
        $user = $connection->findOne("users", ["token" => $token]);

        if (!$user) {
            show_alert("Invalid or expired token.");
        } else {
            $hashed_password = password_hash($user_info["password"], PASSWORD_DEFAULT);

            $connection->update("users", $user["id"], [
                "password" => $hashed_password,
                "token" => null,
            ]);

            show_alert("Password reset successfully. You can now log in.", "success");
            redirect("/login");
        }
    }
}

function validate_user_info()
{
    $errors = [];

    if (empty($_POST['password'])) {
        $errors['password_error'] = 'Please enter a new password';
    } elseif (strlen($_POST['password']) < 8) {
        $errors['password_error'] = 'Password must be at least 8 characters';
    }

    if (empty($_POST['confirm_password'])) {
        $errors['confirm_password_error'] = 'Please confirm your password';
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $errors['confirm_password_error'] = 'Passwords do not match';
    }

    return $errors;
}
?>

<?php include_once "../../includes/header.php"; ?>

<div class="auth-container">
    <div class="col-md-6 d-none d-md-block p-0 auth-image">
        <div class="auth-image-content">
            <h2 class="display-4 fw-bold">Create a New Password</h2>
            <p class="lead">Enter your new password to reset your account access.</p>
        </div>
    </div>

    <div class="col-md-6 auth-form-container">
        <div class="auth-form">
            <div class="auth-logo text-center">
                <h1><span style="color: var(--primary-color);">Flavor</span><span style="color: var(--primary-blue);">Fusion</span></h1>
                <p class="text-muted">Set your new password below.</p>
            </div>

            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['password_error'] ?? "" ?></p>
                </div>

                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm new password" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['confirm_password_error'] ?? "" ?></p>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
            </form>

            <div class="auth-footer">
                <p>Back to <a href="<?= ROOT . '/login' ?>">Sign in</a></p>
            </div>
        </div>
    </div>
</div>

<?php include_once "../../includes/footer.php"; ?>