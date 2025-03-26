<?php
include_once '../../init.php';
include_once DB_ROOT . 'database.php';

enable_admin_route();


$payments = $connection->find("payments", ["status" => "completed"]);

$total_payment = array_sum(array_map(function ($payment) {
    return $payment["amount"];
}, $payments));


$product_count = $connection->count("products");
$orders_count = $connection->count("orders");
$customer_count = $connection->count("users", ["role" => "customer"]);

?>

<?php include_once '../../includes/header.php' ?>
<div class="d-flex">

    <?php $active = "index";
    include_once '../../includes/admin-sidebar.php' ?>



    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">Welcome back, Admin!</h2>
                <p class="text-muted">Here's what's happening with your website today.</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="dashboard-card bg-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Revenue</h6>
                                <div class="stats-number">₹<?= $total_payment ?></div>
                            </div>
                            <div class="card-icon text-primary">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="dashboard-card bg-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Orders</h6>
                                <div class="stats-number"><?= $orders_count ?></div>
                            </div>
                            <div class="card-icon text-warning">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="dashboard-card bg-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Customers</h6>
                                <div class="stats-number"><?= $customer_count ?></div>
                            </div>
                            <div class="card-icon text-info">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="dashboard-card bg-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Products</h6>
                                <div class="stats-number"><?= $product_count ?></div>
                            </div>
                            <div class="card-icon text-success">
                                <i class="fas fa-box-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/admin-footer.php' ?>