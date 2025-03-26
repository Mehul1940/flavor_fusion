<?php
include_once '../../init.php';
include_once DB_ROOT . 'database.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = parse_input($_POST["email"]);

    $user = $connection->findOne("users", ["email" => $email]);

    if (!$user) {
        show_alert("No account found");
    } else {
        $token = bin2hex(random_bytes(32));

        $connection->update("users", $user['id'], [
            "token" => $token,
        ]);

        $reset_link =  "http://localhost/$PROJECT_NAME/reset-password?token=$token";
        $subject = "Password Reset Request";
        $message = "<p>Hello,</p>
                        <p>You requested a password reset. Click the link below to reset your password:</p>
                        <p><a href='$reset_link'>Reset Password</a></p>";

        $email_sent = sendEmail($email, $subject, $message);

        if ($email_sent) {
            show_alert("A password reset link has been sent to your email.");
        } else {
            show_alert("Failed to send email. Try again later.");
        }
    }
}



?>

<?php include_once "../../includes/header.php" ?>
<div class="auth-container">
    <div class="col-md-6 d-none d-md-block p-0 auth-image">
        <div class="auth-image-content">
            <h2 class="display-4 fw-bold">Reset Your Password</h2>
            <p class="lead">Enter your email to receive password reset instructions.</p>
        </div>
    </div>

    <div class="col-md-6 auth-form-container">
        <div class="auth-form">
            <div class="auth-logo text-center">
                <h1><span style="color: var(--primary-color);">Flavor</span><span style="color: var(--primary-blue);">Fusion</span></h1>
                <p class="text-muted">Forgot your password? No worries.</p>
            </div>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Send Reset Link</button>
            </form>

            <div class="auth-footer">
                <p>Remember your password? <a href="<?= ROOT . '/login' ?>">Sign in</a></p>
                </p>
            </div>
        </div>
    </div>
</div>
<?php include_once "../../includes/footer.php" ?>