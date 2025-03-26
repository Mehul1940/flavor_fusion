<?php
include_once '../../../../init.php';
include DB_ROOT . 'database.php';
enable_admin_route();

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$order_id) redirect("");

$order = $connection->findById("orders", $order_id);
if (!$order) redirect("");

$customer = $connection->findById("users", $order["user_id"]);
$order_items = $connection->find("order_items", ["order_id" => $order["id"]]);

$status_classes = [
    "pending" => "bg-secondary",
    "processing" => "bg-info",
    "shipped" => "bg-primary",
    "completed" => "bg-success",
    "cancelled" => "bg-danger"
];

$status_options = ["pending", "processing", "completed", "cancelled"];
$status_badge = $status_classes[$order["status"]] ?? "bg-secondary";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_status = parse_input($_POST["status"]) ?? $order["status"];
    var_dump($new_status);
    $connection->update("orders", $order_id, ["status" => $new_status],);
    $payment = $connection->findOne("payments", ["order_id" => $order_id]);
    $connection->update("payments", $payment["id"], ["status" => "completed"]);
    redirect("admin/orders/view?id=" . $order_id);
}

$delivery_label = $order["delivery_option"] === "delivery"
    ? "Home Delivery <i class='fa-solid fa-truck'></i>"
    : "Pickup <i class='fa-solid fa-map-location-dot'></i>";

?>

<?php include_once '../../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "orders";
    include_once '../../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Order Details</h2>
                <a href="<?= ROOT . 'admin/orders' ?>" class="fw-semibold btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to Orders</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h5 class="my-0">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Order ID:</strong> <?= $order['id']; ?></p>
                        <p><strong>Customer Name:</strong> <?= $customer ? $customer['name'] : 'N/A'; ?></p>
                        <p><strong>Order Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                        <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_amount'], 2); ?></p>
                        <p><strong>Status:</strong>
                            <span class="badge <?= $status_badge; ?>"><?= ucfirst($order['status']); ?></span>
                        </p>
                        <p><strong>Delivery:</strong>
                            <span class="badge <?= $order["delivery_option"] === "delivery" ? "bg-info" : "bg-warning" ?>"><?= $delivery_label; ?></span>
                        </p>

                        <?php if ($order["delivery_option"] == 'delivery') :
                            $address = $connection->findOne("addresses", ["user_id" => $order["user_id"]])
                        ?>
                            <strong>Address:</strong><br>
                            <p class="my-0"><?= $address["address_line1"]; ?></p>
                            <p class="my-0 mb-3"><?= $address["city"] . ", " . $address["state"] . " - " . $address["pin_code"]; ?></p>
                        <?php endif; ?>


                        <form method="POST">
                            <div class="mb-3">
                                <label for="status" class="form-label">Change Status:</label>
                                <select name="status" id="status" class="form-select">
                                    <?php foreach ($status_options as $status): ?>
                                        <option value="<?= $status; ?>" <?= $order["status"] === $status ? "selected" : ""; ?>>
                                            <?= ucfirst($status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 border rounded shadow-sm p-3">
                <h5 class="my-2">Order Items</h5>
                <?php if (count($order_items) > 0): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item):
                                $product = $connection->findById("products", $item["product_id"]);
                            ?>
                                <tr>
                                    <td><?= $product ? $product['name'] : 'N/A'; ?></td>
                                    <td><?= $item['quantity']; ?></td>
                                    <td>₹<?= number_format($item['price'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No items found in this order.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Print Receipt -->
        <div class="mt-4 text-center">
            <button id="print-btn" class="fw-semibold btn btn-outline-primary"><i class="fas fa-print"></i> Print Receipt</button>
        </div>

        <div id="receipt-content" class="d-none">
            <h2 class="text-center">Order Receipt</h2>
            <p><strong>Order ID:</strong> <?= $order['id']; ?></p>
            <p><strong>Customer:</strong> <?= $customer ? $customer['name'] : 'N/A'; ?></p>
            <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
            <p><strong>Total:</strong> ₹<?= number_format($order['total_amount'], 2); ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['status']); ?></p>
            <hr>
            <h5>Items</h5>
            <ul>
                <?php foreach ($order_items as $item):
                    $product = $connection->findById("products", $item["product_id"]);
                ?>
                    <li><?= $product ? $product['name'] : 'N/A'; ?> - ₹<?= number_format($item['price'], 2); ?> (x<?= $item['quantity']; ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById("print-btn").addEventListener("click", function() {
            var printWindow = window.open('', '_blank');
            var content = document.getElementById("receipt-content").innerHTML;
            printWindow.document.write('<html><head><title>Order Receipt</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body class="container mt-4">');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script>

    <?php include_once '../../../../includes/admin-footer.php'; ?>