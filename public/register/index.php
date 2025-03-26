<?php
include_once '../../init.php';
include_once DB_ROOT . 'database.php';

enable_unprotected_route();

$errors = [];
$user_info = [];



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $errors = validate_user_info($connection);

    $user_info["name"] = parse_input($_POST["name"]);
    $user_info["email"] = parse_input($_POST["email"]);
    $user_info["password"] = password_hash(parse_input($_POST["password1"]), PASSWORD_DEFAULT);


    if (count($errors) === 0) {


        $user_info['profile_pic'] = 'default-profile.png';
        $user_info['role'] = 'customer';
        $result  = $connection->save('users', $user_info);

        if ($result) {
            redirect("login");
        }
    }
}

function validate_user_info($connection)
{
    $errors = [];

    $name = parse_input($_POST["name"]);
    $email = parse_input($_POST["email"]);
    $password1 = parse_input($_POST["password1"]);
    $password2 = parse_input($_POST["password2"]);

    if (empty($name)) {
        $errors['name_error'] = 'Please enter your name';
    } else if (strlen($name) < 4) {
        $errors['name_error'] = 'Please enter a valid name';
    }

    if (empty($email)) {
        $errors['email_error'] = 'Please enter your email';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email_error'] = 'Please enter valid email';
    } else {
        $result = $connection->find("users", ['email' => $email]);

        if (count($result) !== 0) $errors['email_error'] = 'Email already exits. Please login';
    }

    if (empty($password1)) {
        $errors['password1_error'] = 'Please enter your password';
    } else if (strlen($password1) < 8) {
        $errors['password1_error'] = 'Password must be 8 characters long';
    }

    if (empty($password2)) {
        $errors['password2_error'] = 'Please confirm your password';
    } else if ($password1 !== $password2) {
        $errors['password2_error'] = 'Confirm password does not match';
    }

    return $errors;
}
?>

<?php include_once "../../includes/header.php" ?>
<div class="auth-container">
    <div class="col-md-6 d-none d-md-block p-0 auth-image">
        <div class="auth-image-content">
            <h2 class="display-4 fw-bold">Join FlavorFusion</h2>
            <p class="lead">Sign up and explore a variety of delicious snacks.</p>
        </div>
    </div>

    <div class="col-md-6 auth-form-container">
        <div class="auth-form">
            <div class="auth-logo text-center">
                <h1><span style="color: var(--primary-color);">Flavor</span><span>Fusion</span></h1>
                <p class="text-muted">Create your account</p>
            </div>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="name" placeholder="Enter your full name" name="name" value="<?= $user_info['name'] ?? '' ?>" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['email_error'] ?? "" ?></p>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" value="<?= $user_info['email'] ?? '' ?>" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['email_error'] ?? "" ?></p>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Create a password" name="password1" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['password1_error'] ?? "" ?></p>
                </div>

                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm-password" placeholder="Confirm your password" name="password2" required>
                    </div>
                    <p class="text-danger fw-semibold"><?= $errors['password2_error'] ?? "" ?></p>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="<?= ROOT . '/login' ?>">Sign in</a></p>
            </div>
        </div>
    </div>
</div>
<?php include_once "../../includes/footer.php" ?>