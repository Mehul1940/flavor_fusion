<?php
include_once '../../../../init.php';
include_once DB_ROOT . 'database.php';

$order_id = $_GET["id"] ?? null;
$auth_user = get_auth_user();

if (empty($order_id)) redirect("");

$order = $connection->findOne("orders", ["id" => $order_id, "user_id" => $auth_user["id"]]);
if (!$order) redirect("");

$order_items = $connection->find("order_items", ["order_id" => $order_id]);

$status_classes = [
    "pending" => "bg-secondary",
    "processing" => "bg-info",
    "shipped" => "bg-warning text-dark",
    "delivered" => "bg-success",
    "cancelled" => "bg-danger"
];

$status_badge = $status_classes[$order["status"]] ?? "bg-secondary";
$show_review_button = $order["status"] === "completed";
$show_cancel_button = !in_array($order["status"], ["completed", "cancelled"]);
$delivery_label = $order["delivery_option"] === "delivery" ? "Home Delivery&nbsp; <i class='fa-solid fa-truck'></i>" : "Pickup&nbsp; <i class='fa-solid fa-map-location-dot'></i>";



if ($_SERVER["REQUEST_METHOD"] === "POST") {


    if (isset($_POST["review"])) {
        $rating = parse_input($_POST["rating"]);
        $content = parse_input($_POST["content"]);
        $product_id = parse_input($_POST["item"]);

        if (empty($rating) || empty($product_id)) {
            show_alert("Please select rating and item");
        } else {

            if ($connection->findOne("reviews", [
                "user_id" => $auth_user["id"],
                "product_id" => $product_id,
            ])) {
                show_alert("Review already exits for this product");
            } else {
                $result = $connection->save("reviews", [
                    "user_id" => $auth_user["id"],
                    "product_id" => $product_id,
                    "rating" => $rating,
                    "review_text" => $content
                ]);

                if ($result) show_alert("Review submitted");
            }
        }
    }



    if (isset($_POST["cancel_order"])) {
        $connection->update("orders", $order["id"], ["status" => "cancelled"]);
        show_alert("Order cancelled");
        redirect("profile/orders/view?id=" . $order["id"]);
    }
}

?>

<?php include_once "../../../../includes/header.php"; ?>
<?php include_once "../../../../includes/nav.php"; ?>

<div class="container py-5">
    <h2 class="mb-4 section-title">Order Details</h2>

    <div class="card p-4 shadow-sm">
        <h5 class="mb-3">Order #<?= $order["id"]; ?></h5>
        <p><strong>Placed on:</strong> <?= date("F j, Y", strtotime($order["created_at"])); ?></p>
        <p><strong>Total Amount:</strong> ₹<?= $order["total_amount"]; ?></p>
        <p><strong>Status:</strong> <span class="badge <?= $status_badge; ?>"><?= ucfirst($order["status"]); ?></span></p>
        <p><strong>Delivery Method:</strong> <span class="badge ms-2 <?= $order["delivery_option"] === "delivery" ? "bg-info" : "bg-warning" ?>"><?= $delivery_label; ?></span></p>

        <h5 class="mb-3">Items</h5>
        <div class="order-items">
            <?php foreach ($order_items as $item) :
                $product = $connection->findOne("products", ["id" => $item["product_id"]]);
            ?>
                <div class="d-flex border-bottom pb-2 mb-2">
                    <img src="<?= ASSETS_PATH . 'images/' . $product["image"] ?>" class="order-img me-3" alt="<?= $product["name"] ?? 'Product'; ?>">
                    <div>
                        <h6><?= $product["name"]; ?></h6>
                        <p class="text-muted mb-1">Qty: <?= $item["quantity"]; ?></p>
                        <p class="text-muted">₹<?= $item["subtotal"]; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <?php if ($show_cancel_button) : ?>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order</button>
            <?php endif; ?>

            <?php if ($show_review_button) : ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#writeReviewModal">Write a Review</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this order?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST">
                    <input type="submit" class="btn btn-danger" id="confirmCancelOrder" name="cancel_order" value="Confirm Cancel" />
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Write Review Modal -->
<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="writeReviewModalLabel">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reviewRating" class="form-label">Rating:</label>
                        <select class="form-select" id="reviewRating" name="rating">
                            <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                            <option value="3">⭐⭐⭐ (3 Stars)</option>
                            <option value="2">⭐⭐ (2 Stars)</option>
                            <option value="1">⭐ (1 Star)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="item" class="form-label">Select Item:</label>
                        <select class="form-select" id="item" name="item">
                            <option>Please select the item</option>
                            <?php foreach ($order_items as $item) :
                                $product = $connection->findOne("products", ["id" => $item["product_id"]]);
                            ?>
                                <option value="<?= $product["id"] ?>"><?= $product["name"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reviewText" class="form-label">Your Review:</label>
                        <textarea class="form-control" id="reviewText" rows="3" name="content"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="hidden" name="order_id" value="<?= $order["id"] ?>" />
                    <input type="submit" class="btn btn-primary" id="submitReview" name="review" value="Submit Review" />
        </form>
    </div>
</div>
</div>
</div>


<?php include_once "../../../../includes/footer.php"; ?>