<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';

$categories = $connection->findAll("categories");
?>

<?php include_once '../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "categories";
    include_once '../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Category Listings</h2>
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
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr class="text-center">
                                        <td><?= $category['id']; ?></td>
                                        <td><?= htmlspecialchars($category['name']); ?></td>
                                        <td><?= htmlspecialchars($category['description']); ?></td>
                                        <td>
                                            <img src="<?= ASSETS_PATH . 'images/' . $category['image']; ?>" alt="Category Image" class="img-thumbnail" width="100">
                                        </td>
                                        <td>
                                            <a href="view?id=<?= $category['id']; ?>" class="fw-semibold btn btn-sm btn-outline-primary">
                                                View <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No categories found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../../../includes/admin-footer.php'; ?>
</div>