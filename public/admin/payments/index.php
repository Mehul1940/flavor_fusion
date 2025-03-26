<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';
enable_admin_route();

$payments = $connection->findAll("payments");
?>

<?php include_once '../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "payments";
    include_once '../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Payments</h2>
                <button id="print-btn" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" id="printable">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Amount (₹)</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($payments) > 0): ?>
                                <?php foreach ($payments as $payment):
                                    $customer = $connection->findById("users", $payment["user_id"]);
                                    $status_classes = [
                                        "completed" => "bg-success",
                                        "pending" => "bg-warning",
                                        "failed" => "bg-danger"
                                    ];
                                    $status_badge = $status_classes[$payment['status']] ?? "bg-secondary";
                                ?>
                                    <tr>
                                        <td><?= $payment['id']; ?></td>
                                        <td><?= $customer['name'] ?? 'N/A'; ?></td>
                                        <td>₹<?= number_format($payment['amount'], 2); ?></td>
                                        <td><span class="badge bg-info text-dark"> <?= $payment['payment_method']; ?> </span></td>
                                        <td><span class="badge <?= $status_badge; ?>"> <?= $payment['status']; ?> </span></td>
                                        <td><?= date('d M Y, h:i A', strtotime($payment['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No payments found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("print-btn").addEventListener("click", function() {
        var printWindow = window.open('', '_blank');
        var content = document.getElementById("printable").innerHTML;
        printWindow.document.write('<html><head><title>Payment Report</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
        printWindow.document.write('</head><body class="container mt-4">');
        printWindow.document.write('<h2 class="text-center">Payment Report</h2>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
</script>

<?php include_once '../../../includes/admin-footer.php'; ?>