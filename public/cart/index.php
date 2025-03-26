<?php
include_once "../../init.php";
include DB_ROOT . 'database.php';

enable_protected_route();

$auth_user = get_auth_user();

$cart_items = $connection->find("cart", ["user_id" => $auth_user["id"]]);

$subtotal = 0;
$delivery_charge = 0;

foreach ($cart_items as $item) {
    $product = $connection->findOne("products", ["id" => $item["product_id"]]);
    if (!$product) continue;
    $total_price = $product["price"] * $item["quantity"];
    $subtotal += $total_price;
}

if ($subtotal < 500) $delivery_charge = 40;

$total = $subtotal + $delivery_charge;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cart_id = parse_input($_POST["cart_id"]);
    $quantity = parse_input($_POST["quantity"]);

    echo $quantity;

    if ($quantity == 0) {
        $connection->delete("cart", $cart_id);
    } else if ($quantity > 5) {
        show_alert("You can't add more than 5 items");
    } else {
        $connection->update("cart", $cart_id, ["quantity" => $quantity]);
    }
    redirect("cart");
}

?>

<?php include_once '../../includes/header.php' ?>
<?php include_once '../../includes/nav.php' ?>

<?php if (empty($cart_items)): ?>
    <div class="container py-5">
        <h3 class="text-center">No items in the cart</h3>
    </div>
<?php else: ?>

    <div class="container py-5">
        <h2 class="mb-4 section-title">Your Shopping Cart</h2>

        <div class="row">
            <div class="col-md-8">
                <div class="cart-items">
                    <?php foreach ($cart_items as $item) :
                        $product = $connection->findOne("products", ["id" => $item["product_id"]]);
                    ?>
                        <div class="d-flex align-items-center border-bottom pb-3 my-3">
                            <img src="<?= ASSETS_PATH . 'images/' . $product["image"] ?>" class="cart-img me-3" alt="Chocolate Truffles">
                            <div class="flex-grow-1">
                                <h6><?= $product["name"] ?></h6>
                                <p class="text-muted mb-1">₹<?= $total_price = $product["price"] * $item["quantity"]; ?></p>

                            </div>
                            <div class="d-flex align-items-center">
                                <form method="POST">
                                    <input type="hidden" name="cart_id" value="<?= $item["id"] ?>">
                                    <input type="hidden" name="quantity" value="<?= $item["quantity"] - 1 ?>">
                                    <button class="btn btn-outline-secondary quantity-btn"><i class="fa-solid fa-minus"></i></button>
                                </form>
                                <input type="text" class="form-control quantity-input mx-2" value="<?= $item["quantity"] ?>" disabled>
                                <form method="POST">
                                    <input type="hidden" name="cart_id" value="<?= $item["id"] ?>">
                                    <input type="hidden" name="quantity" value="<?= $item["quantity"] + 1 ?>">
                                    <button class="btn btn-outline-secondary quantity-btn"><i class="fa-solid fa-plus"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 bg-light rounded">
                    <h5>Order Summary</h5>
                    <p class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>₹<?= $subtotal ?></strong>
                    </p>
                    <p class="d-flex justify-content-between">
                        <span>Shipping:</span>
                        <strong><?= $delivery_charge === 0 ? "Free" : "₹$delivery_charge" ?></strong>
                    </p>
                    <hr>
                    <p class="d-flex justify-content-between">
                        <span><strong>Total:</strong></span>
                        <strong>₹<?= $total ?></strong>
                    </p>
                    <a class="btn btn-primary w-100" href="<?= ROOT . 'checkout' ?>">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php include_once '../../includes/footer.php' ?>