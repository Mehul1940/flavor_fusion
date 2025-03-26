<?php
include_once "../../init.php";
include DB_ROOT . 'database.php';

enable_protected_route();

$auth_user = get_auth_user();

$address = $connection->findOne("addresses", ["user_id" => $auth_user["id"]]);
$cart_items = $connection->find("cart", ["user_id" => $auth_user["id"]]);

$subtotal = 0;

foreach ($cart_items as $item) {
    $product = $connection->findOne("products", ["id" => $item["product_id"]]);
    if (!$product) continue;
    $subtotal += $product["price"] * $item["quantity"];
}

$delivery_charge = $subtotal < 500 ? 40 : 0;
$total = $subtotal + $delivery_charge;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $delivery_option = $_POST["delivery_option"];
    $payment_method = "cash";


    if ($delivery_option === "delivery" && empty($address)) {
        show_alert("Please enter your address first");
    }

    if (!empty($address)) {

        $order_data = [
            "user_id" => $auth_user["id"],
            "total_amount" => $total,
            "delivery_option" => $delivery_option,
        ];

        $new_order_id = $connection->save("orders", $order_data);

        if ($new_order_id) {
            foreach ($cart_items as $item) {
                $product = $connection->findById("products", $item["product_id"]);
                $order_item_data = [
                    "order_id" => $new_order_id,
                    "product_id" => $item["product_id"],
                    "quantity" => $item["quantity"],
                    "price" => $product["price"],
                    "subtotal" => $item["quantity"] * $product["price"]
                ];


                $connection->save("order_items", $order_item_data);
                $connection->delete("cart", $item["id"]);
            }

            // create payment

            $connection->save("payments", [
                "user_id" => $auth_user["id"],
                "order_id" => $new_order_id,
                "amount" => $total,
                "payment_method" => "cash",

            ]);

            redirect("profile/orders");
        }
    }
}

?>

<?php include_once '../../includes/header.php' ?>
<?php include_once '../../includes/nav.php' ?>
<div class="container py-5">
    <h2 class="mb-4 section-title">Checkout</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="p-4 border rounded">

                <div id="shippingDetails">
                    <h4>Billing & Shipping Details</h4>
                    <form method="POST" id="checkoutForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter your name" value="<?= $auth_user["name"] ?? "" ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" placeholder="Enter your phone" value="<?= $auth_user["phone"] ?? "" ?>">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="Enter your email" value="<?= $auth_user["email"] ?? "" ?>">
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Choose a option</label>

                            <div class="d-flex gap-4">
                                <div>
                                    <input type="radio" class="form-check-input" name="delivery_option" id="homeDelivery" value="delivery" checked>
                                    <label class="form-check-label ms-2" for="homeDelivery">Home Delivery</label>
                                </div>
                                <div>
                                    <input type="radio" class="form-check-input" name="delivery_option" id="storePickup" value="pickup">
                                    <label class="form-check-label ms-2" for="storePickup">Store Pickup</label>
                                </div>
                            </div>
                        </div>


                        <div class="mt-3" id="homeAddress">
                            <label class="form-label">Shipping Address</label>
                            <?php if (empty($address)): ?>
                                <a href="<?= ROOT . 'profile/address' ?>" class="text-primary">Add address</a>
                            <?php else: ?>
                                <p class="my-0"><?= $address["address_line1"]; ?></p>
                                <p class="my-0 mb-3"><?= $address["city"] . ", " . $address["state"] . " - " . $address["pin_code"]; ?></p>
                            <?php endif; ?>


                        </div>

                        <div class="mt-3 d-none" id="pickupAddress">
                            <label class="form-label">Pickup Address</label>
                            <span class="d-block">
                                Ambalal Complex Lower Level, <br />
                                Gulab Tower Road, <br>
                                Shameshawar Park III, <br>
                                Ahmedabad
                            </span>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-light rounded">
                <h4>Order Summary</h4>
                <div class="d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong>₹<?= $subtotal ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Shipping:</span>
                    <strong id="shippingCost"><?= $delivery_charge == 0 ? "Free" : "₹$delivery_charge" ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span><strong>Total:</strong></span>
                    <strong id="totalPrice">₹<?= $total ?></strong>
                </div>

                <h5 class="mt-4">Payment Method</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="cod" checked>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>
                <button class="btn btn-primary w-100 mt-4" id="submit">Place Order</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelector("#submit").addEventListener("click", () => {
        document.querySelector("#checkoutForm").submit();
    })


    document.querySelectorAll("input[name='delivery_option']").forEach(input => {
        input.addEventListener("change", e => {
            const homeAddress = document.querySelector("#homeAddress");
            const pickupAddress = document.querySelector("#pickupAddress");

            if (e.target.value === "delivery") {
                homeAddress.classList.remove("d-none");
                pickupAddress.classList.add("d-none");
            } else {
                homeAddress.classList.add("d-none");
                pickupAddress.classList.remove("d-none");
            }
        });
    });
</script>


<?php include_once '../../includes/footer.php' ?>