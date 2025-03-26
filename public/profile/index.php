<?php
include_once "../../init.php";
include_once DB_ROOT . 'database.php';

enable_protected_route();

$errors = [];
$user_info = [];
$auth_user = get_auth_user();
$latest_user = $connection->findById("users", $auth_user["id"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $errors = validate_user_info($connection);

    $user_info["name"] = parse_input($_POST["name"]);
    $user_info["email"] = parse_input($_POST["email"]);
    $user_info["phone"] = parse_input($_POST["phone"]);


    if (count($errors) === 0) {
        $result = $connection->update("users", $auth_user["id"], $user_info);

        if ($result) {
            show_alert("Profile Updated");
            redirect("profile");
        }
    }
}

function validate_user_info($connection)
{
    $errors = [];
    global $auth_user;
    $name = parse_input($_POST["name"]);
    $email = parse_input($_POST["email"]);
    $phone = parse_input($_POST["phone"]);

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
        if (count($result) !== 0 && $result[0]["email"] !== $auth_user["email"]) $errors['email_error'] = 'Email already exits';
    }

    if (empty($phone)) {
        $errors['phone_error'] = 'Please enter your phone';
    }

    return $errors;
}


?>

<?php include_once '../../includes/header.php' ?>
<?php include_once '../../includes/nav.php' ?>


<div class="container py-5">
    <div class="row">
        <?php $active = "profile";
        include_once '../../includes/user_sidebar.php';
        ?>
        <div class="col-md-9">
            <div class="profile-content bg-white p-4 rounded shadow-sm">
                <h3 class="mb-4">Update Profile</h3>
                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" name="name" value="<?= $latest_user["name"] ?? "" ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['name_error'] ?? "" ?></p>

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" value="<?= $latest_user["email"] ?? "" ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['email_error'] ?? "" ?></p>

                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" placeholder="Enter your phone number" name="phone" value="<?= $latest_user["phone"] ?? "" ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['phone_error'] ?? "" ?></p>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php' ?>