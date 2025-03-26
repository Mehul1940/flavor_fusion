<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';
enable_admin_route();


$products = $connection->findAll("products");
?>

<?php include_once '../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "products";
    include_once '../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Product Listings</h2>
                <a href="new" id="print-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add new</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" id="printable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price (₹)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product):
                                    $category = $connection->findById("categories", $product["category_id"]);
                                ?>
                                    <tr class="text-center">
                                        <td><?= $product['id']; ?></td>
                                        <td>
                                            <img src="<?= ASSETS_PATH . 'images/' . $product['image']; ?>"
                                                alt="Product Image"
                                                class="img-thumbnail"
                                                style="width: 100px; height: 70px; object-fit: cover;">
                                        </td>
                                        <td><?= htmlspecialchars($product['name']); ?></td>
                                        <td><?= htmlspecialchars($category['name']); ?></td>
                                        <td><?= htmlspecialchars($product['stock']); ?></td>
                                        <td>₹<?= number_format($product['price'], 2); ?></td>
                                        <td>
                                            <a href="view?id=<?= $product['id']; ?>" class="fw-semibold btn btn-sm btn-outline-primary">
                                                View <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../../../includes/admin-footer.php'; ?>