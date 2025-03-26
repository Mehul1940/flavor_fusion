<?php
include_once "../../../init.php";
include_once DB_ROOT . 'database.php';

enable_protected_route();


$errors = [];
$address_info = [];
$auth_user = get_auth_user();

$address = $connection->findOne("addresses", ["user_id" => $auth_user["id"]]);

if ($address) {
    $address_info = $address;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $address_info["address_line1"] = parse_input($_POST["address_line1"]);
    $address_info["address_line2"] = parse_input($_POST["address_line2"]);
    $address_info["city"] = parse_input($_POST["city"]);
    $address_info["state"] = parse_input($_POST["state"]);
    $address_info["pin_code"] = parse_input($_POST["pin_code"]);
    $address_info["user_id"] = $auth_user["id"];

    $errors = validate_address();

    if (count($errors) === 0) {
        $address = $connection->find("addresses", ["user_id" => $auth_user["id"]]);
        if ($address) {
            $connection->update("addresses", $address[0]["id"], $address_info);
        } else {
            $result = $connection->save("addresses", $address_info);
            if ($result) redirect("profile/address");
        }
        show_alert("Address updated!");
    }
}
function validate_address()
{
    $errors = [];

    $address_line1 = parse_input($_POST["address_line1"] ?? '');
    $address_line2 = parse_input($_POST["address_line2"] ?? '');
    $city = parse_input($_POST["city"] ?? '');
    $state = parse_input($_POST["state"] ?? '');
    $pincode = parse_input($_POST["pin_code"] ?? '');

    if (empty($address_line1)) {
        $errors['address_line1'] = "Address Line 1 is required.";
    }

    if (empty($city)) {
        $errors['city'] = "City is required.";
    }

    if (empty($state)) {
        $errors['state'] = "State is required.";
    }

    if (empty($pincode)) {
        $errors['pincode'] = "Pincode is required.";
    } elseif (!preg_match("/^\d{6}$/", $pincode)) {
        $errors['pincode'] = "Invalid pincode format. Must be 6 digits.";
    }

    return $errors;
}


?>


<?php include_once '../../../includes/header.php' ?>
<?php include_once '../../../includes/nav.php' ?>
<div class="container py-5">
    <div class="row">
        <?php $active = "address";
        include_once '../../../includes/user_sidebar.php' ?>
        <div class="col-md-9">
            <div class="profile-content bg-white p-4 rounded shadow-sm">
                <h3 class="mb-4">Update Address</h3>
                <form method="POST" novalidate>

                    <div class="mb-3">
                        <label for="addressLine1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="addressLine1" placeholder="House No, Street" name="address_line1" value="<?= $address_info["address_line1"] ?? '' ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['address_line1'] ?? "" ?></p>
                    </div>

                    <div class="mb-3">
                        <label for="addressLine2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="addressLine2" placeholder="Apartment, Suite, etc." name="address_line2" value="<?= $address_info["address_line2"] ?? '' ?>">

                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" placeholder="Enter city" name="city" value="<?= $address_info["city"] ?? '' ?>" required>
                            <p class="text-danger fw-semibold"><?= $errors['city'] ?? "" ?></p>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" placeholder="Enter state" name="state" value="<?= $address_info["state"] ?? '' ?>" required>
                            <p class="text-danger fw-semibold"><?= $errors['state'] ?? "" ?></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pincode" class="form-label">PIN Code</label>
                        <input type="text" class="form-control" id="pincode" placeholder="Enter PIN code" name="pin_code" value="<?= $address_info["pin_code"] ?? '' ?>" required>
                        <p class="text-danger fw-semibold"><?= $errors['pincode'] ?? "" ?></p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Address</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../../includes/footer.php' ?>