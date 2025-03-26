<?php
include_once '../../init.php';
include_once DB_ROOT . 'database.php';

$products_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;
$search_term = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';


$products = [];
$total_products = count($products);
$total_pages = ceil($total_products / $products_per_page);

if ($search_term) {
    $products = $connection->search(
        "products",
        ["name", "description"],
        $search_term,
        $products_per_page,
        $offset
    );
} else if ($category) {
    $products = $connection->inner_join("products", "categories", "products.category_id = categories.id", "products.*, categories.name AS category_name");
    $products = array_filter($products, function ($product) {
        global $category;
        return strtolower($product["category_name"]) == strtolower($category);
    });
} else {
    $products = $connection->paginate(
        "products",
        $products_per_page,
        $offset
    );
    $total_products = $connection->count("products");
    $total_pages = ceil($total_products / $products_per_page);
}

?>

<?php include_once "../../includes/header.php" ?>
<?php include_once "../../includes/nav.php" ?>

<div class="container mt-5">
    <h2 class="mb-4 section-title">Our Products</h2>

    <div class="row">

        <?php foreach ($products as $product):
            $reviews = $connection->find("reviews", ["product_id" => $product["id"]]);
        ?>
            <div class="product-card col-md-3 mb-4">
                <div class="position-relative">
                    <img src="<?= ASSETS_PATH . 'images/' . $product["image"] ?>" class="product-img w-100" alt="Product 4">
                </div>
                <div class="card-body">
                    <h5 class="product-title"><?= $product["name"] ?></h5>
                    <div class="product-rating">
                        <?= displayStars(getAverageRating($reviews)) ?>
                        <span class="text-muted ms-2">(<?= getAverageRating($reviews) ?>)</span>
                    </div>
                    <p class="product-price">₹<?= $product["price"] ?></p>
                    <a href="<?= ROOT . 'shop/view?id=' . $product['id'] ?>" class="btn btn-primary w-100">View Detail</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($category)) : ?>


            <div class="d-flex justify-content-center py-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php
                        $base_url = "?page=";
                        $query_params = [];

                        if (!empty($search_term)) {
                            $query_params['query'] = urlencode($search_term);
                        }

                        if (!empty($_GET['category'])) {
                            $query_params['category'] = urlencode($_GET['category']);
                        }

                        $query_string = !empty($query_params) ? '&' . http_build_query($query_params) : '';
                        ?>

                        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                            <?php if ($current_page > 1): ?>
                                <a class="page-link page-arrow" href="<?= $base_url . ($current_page - 1) . $query_string ?>">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-link page-arrow disabled">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </span>
                            <?php endif; ?>
                        </li>

                        <li class="page-item " aria-current="page">
                            <span class="page-link active"><?= $current_page ?></span>
                        </li>

                        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                            <?php if ($current_page < $total_pages): ?>
                                <a class="page-link page-arrow" href="<?= $base_url . ($current_page + 1) . $query_string ?>">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-link page-arrow disabled">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>
            </div>

        <?php endif; ?>

    </div>

</div>
<?php include_once "../../includes/footer.php" ?>