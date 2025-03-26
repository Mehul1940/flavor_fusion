<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';
enable_admin_route();

$orders = $connection->findAll("orders");

?>

<?php include_once '../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "orders";
    include_once '../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Orders</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" id="printable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Delivery</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $index => $order) : ?>
                                    <?php
                                    $customer = $connection->findById("users", $order["user_id"]);

                                    $status_classes = [
                                        "pending" => "bg-secondary",
                                        "processing" => "bg-info",
                                        "completed" => "bg-success",
                                        "cancelled" => "bg-danger"
                                    ];
                                    $status_badge = $status_classes[$order["status"]] ?? "bg-secondary";

                                    $delivery_label = $order["delivery_option"] === "delivery"
                                        ? "Home Delivery&nbsp; <i class='fa-solid fa-truck'></i>"
                                        : "Pickup&nbsp; <i class='fa-solid fa-map-location-dot'></i>";
                                    ?>
                                    <tr class="text-center">
                                        <td><?= $order['id']; ?></td>
                                        <td><?= $customer ? $customer['name'] : 'N/A'; ?></td>
                                        <td><?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></td>
                                        <td>₹<?= number_format($order['total_amount'], 2); ?></td>
                                        <td><span class="badge <?= $status_badge; ?>"><?= ucfirst($order['status']); ?></span></td>
                                        <td><span class="badge <?= $order["delivery_option"] === "delivery" ? "bg-info" : "bg-warning" ?>"><?= $delivery_label; ?></span></td>
                                        <td>
                                            <a href="view?id=<?= $order['id']; ?>" class="fw-semibold btn btn-sm btn-outline-primary">
                                                View <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../../../includes/admin-footer.php'; ?>