<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';
enable_admin_route();

$customers = $connection->find("users", ["role" => "customer"]);

?>

<?php include_once '../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "customers";
    include_once '../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Customers</h2>
                <button id="print-btn" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" id="printable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($customers)): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <tr class="text-center">
                                        <td><?= htmlspecialchars($customer['id']); ?></td>
                                        <td><?= htmlspecialchars($customer['name']); ?></td>
                                        <td><?= htmlspecialchars($customer['email']); ?></td>
                                        <td><?= htmlspecialchars($customer['phone']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No customers found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("print-btn").addEventListener("click", function() {
            var printWindow = window.open('', '_blank');
            var content = document.getElementById("printable").innerHTML;
            printWindow.document.write('<html><head><title>Customer Report</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body class="container mt-3">');
            printWindow.document.write('<h2 class="text-center">Customer Report</h2>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script>

    <?php include_once '../../../includes/admin-footer.php'; ?>