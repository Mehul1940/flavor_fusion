<?php
include_once "../../../init.php";
include_once DB_ROOT . 'database.php';
enable_protected_route();

$auth_user = get_auth_user();

$orders = $connection->find("orders", ["user_id" => $auth_user["id"]]);

?>

<?php include_once '../../../includes/header.php'; ?>
<?php include_once '../../../includes/nav.php'; ?>

<div class="container py-5">
    <div class="row">
        <?php $active = "orders";
        include_once '../../../includes/user_sidebar.php'; ?>

        <div class="col-md-9">
            <div class="profile-content bg-white p-4 rounded shadow-sm">
                <h2 class="mb-4">My Orders</h2>

                <?php if (empty($orders)) : ?>
                    <p class="text-muted">No orders found.</p>
                <?php else : ?>
                    <div class="accordion" id="ordersAccordion">
                        <?php foreach ($orders as $index => $order) : ?>
                            <?php
                            $order_items = $connection->find("order_items", ["order_id" => $order["id"]]);

                            $status_classes = [
                                "pending" => "bg-secondary",
                                "processing" => "bg-info",
                                "completed" => "bg-success",
                                "cancelled" => "bg-danger"
                            ];
                            $status_badge = $status_classes[$order["status"]] ?? "bg-secondary";

                            $delivery_label = $order["delivery_option"] === "delivery" ? "Home Delivery&nbsp; <i class='fa-solid fa-truck'></i>" : "Pickup&nbsp; <i class='fa-solid fa-map-location-dot'></i>";
                            ?>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $index; ?>">
                                    <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#order<?= $index; ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false'; ?>" aria-controls="order<?= $index; ?>">
                                        Order #<?= $order["id"]; ?> - ₹<?= $order["total_amount"]; ?>
                                        <span class="badge ms-2 <?= $order["delivery_option"] === "delivery" ? "bg-info" : "bg-warning" ?>"><?= $delivery_label; ?></span>
                                        <span class="badge <?= $status_badge; ?> ms-3"><?= ucfirst($order["status"]); ?></span>
                                    </button>
                                </h2>
                                <div id="order<?= $index; ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : ''; ?>" aria-labelledby="heading<?= $index; ?>" data-bs-parent="#ordersAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Placed on:</strong> <?= date("F j, Y", strtotime($order["created_at"])); ?></p>

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

                                        <div class="d-flex justify-content-between mt-3">
                                            <a href="view?id=<?= $order["id"]; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../../includes/footer.php'; ?>