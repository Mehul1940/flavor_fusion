<?php
include_once '../../../../init.php';
include DB_ROOT . 'database.php';

enable_admin_route();

$categories = $connection->findAll("categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = parse_input($_POST['name']);
    $description = parse_input($_POST['description']);
    $price = parse_input($_POST['price']);
    $category = parse_input($_POST['category']);
    $stock = parse_input($_POST['stock']);

    $image = "";
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../../../assets/images/";
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $imageName;
        }
    }

    $result = $connection->save("products", [
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
?>

<?php include_once '../../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "products";
    include_once '../../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">Add New Product</h2>
            </div>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea type="text" name="description" class="form-control" required rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../../../includes/admin-footer.php'; ?>