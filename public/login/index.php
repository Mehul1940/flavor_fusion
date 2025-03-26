<?php
include_once '../../init.php';
include_once DB_ROOT . 'database.php';

enable_unprotected_route();


$errors = [];
$user_info = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = validate_user_info();
    $user_info["email"] = parse_input($_POST["email"]);
    $user_info["password"] = parse_input($_POST["password"]);

    if (count($errors) === 0) {
        $users = $connection->find("users", ["email" => $user_info["email"]]);

        if (count($users) === 0) {
            show_alert("Invalid email or password");
        } else {
            $user = $users[0];

            if (!password_verify($user_info["password"], $user["password"])) {
                show_alert("Invalid email or password");
            } else {
                login($user);
                redirect("");
            }
        }
    }
}

function validate_user_info()
{
    $errors = [];

    if (empty($_POST['email'])) {
        $errors['email_error'] = 'Please enter your email';
    }

    if (empty($_POST['password'])) {
        $errors['password_error'] = 'Please enter your password';
    }

    return $errors;
}


?>

<?php include_once "../../includes/header.php" ?>
<div class="auth-container">
    <div class="col-md-6 d-none d-md-block p-0 auth-image">
        <div class="auth-image-content">
            <h2 class="display-4 fw-bold">Welcome to FlavorFusion</h2>
            <p class="lead">Discover a world of delicious snacks at your fingertips.</p>
        </div>
    </div>

    <div class="col-md-6 auth-form-container">
        <div class="auth-form">
            <div class="auth-logo text-center">
                <h1><span style="color: var(--primary-color);">Flavor</span><span style="color: var(--primary-blue);">Fusion</span></h1>
                <p class="text-muted">Sign in to your account</p>
            </div>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['email_error'] ?? "" ?></p>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['password_error'] ?? "" ?></p>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <a href="<?= ROOT . '/forgot-password' ?>" class="text-decoration-none" style="color: var(--primary-color);">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="<?= ROOT . '/register' ?>">Sign up</a></p>
            </div>
        </div>
    </div>
</div>
<?php include_once "../../includes/footer.php" ?>