<?php
include_once '../../../../init.php';
include DB_ROOT . 'database.php';
enable_admin_route();

$categories = $connection->findAll("categories");
$product = null;

if (isset($_GET['id'])) {
    $productId = parse_input($_GET['id']);
    $product = $connection->findById("products", $productId);

    if (!$product) {
        redirect("admin/products");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $name = parse_input($_POST['name']);
    $description = parse_input($_POST['description']);
    $price = parse_input($_POST['price']);
    $category = parse_input($_POST['category']);
    $stock = parse_input($_POST['stock']);

    $image = $product['image'];
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../../../assets/images/";
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $imageName;
        }
    }

    $result = $connection->update("products", $product["id"], [
        "name" => $name,
        "description" => $description,
        "price" => $price,
        "category_id" => $category,
        "stock" => $stock,
        "image" => $image
    ]);

    if ($result) {
        redirect("admin/products");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $connection->delete("products",  $productId);
    redirect("admin/products");
}
?>

<?php include_once '../../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "products";
    include_once '../../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between">
                <h2 class="section-title"><?= $product ? "Edit Product" : "Product Not Found"; ?></h2>
                <?php if ($product) : ?>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <button type="submit" name="delete" class="fw-semibold btn btn-danger">Delete Product</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($product) : ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required rows="3"><?= $product['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['id']; ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?= $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>
                        <img src="<?= ASSETS_PATH . 'images/' . $product['image']; ?>" alt="Product Image" width="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload New Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update" class="btn btn-primary">Update Product</button>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <p class="text-danger">Product not found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../../../../includes/admin-footer.php'; ?>