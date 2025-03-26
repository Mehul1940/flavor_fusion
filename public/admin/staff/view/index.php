<?php
include_once '../../../../init.php';
include DB_ROOT . 'database.php';

enable_admin_route();

$staff = null;

if (isset($_GET['id'])) {
    $staffId = parse_input($_GET['id']);
    $staff = $connection->findById("users", $staffId);

    if (!$staff) {
        redirect("admin/staff");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $name = parse_input($_POST['name']);
    $email = parse_input($_POST['email']);
    $phone = parse_input($_POST['phone']);
    $password = !empty($_POST['password']) ? password_hash(parse_input($_POST['password']), PASSWORD_DEFAULT) : $staff['password'];

    $errors = validate_staff_info($connection);

    if (count($errors) === 0) {
        $result = $connection->update("users", $staff["id"], [
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "password" => $password
        ]);

        if ($result) {
            redirect("admin/staff");
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $connection->delete("users", $staffId);
    redirect("admin/staff");
}

function validate_staff_info($connection)
{
    $errors = [];

    $name = parse_input($_POST["name"]);
    $email = parse_input($_POST["email"]);
    $phone = parse_input($_POST["phone"]);
    $password1 = parse_input($_POST["password"]);

    if (empty($name)) {
        $errors['name_error'] = 'Please enter staff name';
    } else if (strlen($name) < 4) {
        $errors['name_error'] = 'Staff name must be at least 4 characters long';
    }

    if (empty($email)) {
        $errors['email_error'] = 'Please enter staff email';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email_error'] = 'Please enter a valid email';
    } else {
        $result = $connection->find("users", ['email' => $email]);
        if (count($result) !== 0) $errors['email_error'] = 'Email already exists. Please use another email.';
    }

    if (empty($phone)) {
        $errors['phone_error'] = 'Please enter staff phone number';
    } else if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone_error'] = 'Please enter a valid 10-digit phone number';
    }

    if (empty($password1)) {
        $errors['password_error'] = 'Please enter a password';
    } else if (strlen($password1) < 8) {
        $errors['password_error'] = 'Password must be at least 8 characters long';
    }

    return $errors;
}

?>

<?php include_once '../../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "staff";
    include_once '../../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between">
                <h2 class="section-title"><?= $staff ? "Edit Staff Member" : "Staff Not Found"; ?></h2>
                <?php if ($staff) : ?>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                        <button type="submit" name="delete" class="fw-semibold btn btn-danger">Delete Staff</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($staff) : ?>
            <form action="" method="POST">
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $staff['name']; ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['name_error'] ?? "" ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $staff['email']; ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['email_error'] ?? "" ?></p>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= $staff['phone']; ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['phone_error'] ?? "" ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (Leave blank to keep existing)</label>
                        <input type="password" name="password" class="form-control">
                        <p class="text-danger fw-semibold"><?= $errors['password_error'] ?? "" ?></p>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update" class="btn btn-primary">Update Staff</button>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <p class="text-danger">Staff member not found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../../../../includes/admin-footer.php'; ?>