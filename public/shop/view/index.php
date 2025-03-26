<?php
include_once '../../../init.php';
include_once DB_ROOT . 'database.php';

$product_id = $_GET["id"];
if (empty($product_id)) redirect("");
$product = $connection->findById("products", $product_id);
$category = $connection->findById("categories", $product["category_id"]);

if (empty($product)) redirect("");

$reviews = $connection->find("reviews", ["product_id" => $product_id]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    enable_protected_route();

    $auth_user = get_auth_user();
    $quantity = parse_input($_POST["quantity"]);

    $error = false;

    if ($quantity > 5) {
        show_alert("Quantity cannot be more than 5.");
        $error = true;
    }

    if (!$error) {
        $cart_item = $connection->findOne("cart", ["product_id" => $product_id, "user_id" => $auth_user["id"]]);

        $new_item = [
            "product_id" => $product_id,
            "user_id" => $auth_user["id"],
            "quantity" => $quantity
        ];

        if (empty($cart_item)) {
            $connection->save("cart", $new_item);
            show_alert("Added to cart");
        } else {
            show_alert("Item is already in the cart");
        }
    }
}

?>

<?php include_once "../../../includes/header.php" ?>
<?php include_once "../../../includes/nav.php" ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= ASSETS_PATH . 'images/' . $product["image"] ?>" class="img-fluid rounded" alt="Product Image">
        </div>

        <div class="col-md-6">
            <h2 class="mb-3"><?= $product["name"] ?></h2>
            <h4 class="text-primary">₹<?= $product["price"] ?></h4>
            <p class="text-muted">Category: <?= $category["name"] ?></p>
            <p><strong>Description:</strong> <?= $product["description"] ?></p>

            <?php if ($product["stock"] > 0) : ?>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <form method="POST" class="d-flex gap-2">
                        <input type="number" id="quantity" class="form-control w-25" value="1" min="1" name="quantity" max="5">
                        <button class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            <?php else: ?>
                <h2>Product is out of stock</h2>
            <?php endif ?>
        </div>
    </div>

    <div class="my-5">
        <h3 class="mb-4">Customer Reviews</h3>

        <?php if (!empty($reviews)): ?>
            <div class="list-group">
                <?php foreach ($reviews as $review):
                    $user = $connection->findById("users", $review["user_id"]);
                ?>
                    <div class="list-group-item shadow-sm p-3 mb-3 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><?= displayStars($review["rating"]) ?></span>
                        </div>
                        <p class="my-1 fs-5 fw-semibold"><?= htmlspecialchars($user["name"]) ?></p>
                        <p class="mb-1"><?= htmlspecialchars($review["review_text"]) ?></p>
                        <small class="text-muted"><?= date("F j, Y", strtotime($review["created_at"])) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-light shadow-sm" role="alert">
                No reviews yet. Be the first to review this product!
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include_once "../../../includes/footer.php" ?>