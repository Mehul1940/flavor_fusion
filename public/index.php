<?php
include_once "../init.php";

include_once DB_ROOT . 'database.php';

$products = $connection->paginate(
    "products",
    4,
    1
);

$categories = $connection->findAll('categories');

?>

<?php include_once '../includes/header.php' ?>
<?php include_once '../includes/nav.php' ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Discover Premium Snacks</h1>
        <p class="hero-subtitle">Handpicked delicious treats delivered to your doorstep</p>
        <a href="<?= ROOT . 'shop' ?>" class="btn btn-primary me-2">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
</section>


<!-- Features -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5>Free Delivery</h5>
                    <p class="mb-0">On orders over ₹500</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h5>Easy Returns</h5>
                    <p class="mb-0">Easy return policy</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>Secure Payment</h5>
                    <p class="mb-0">100% secure checkout</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5>24/7 Support</h5>
                    <p class="mb-0">Dedicated support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Browse Categories</h2>
        <div class="row">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-3 col-6">
                    <a href="<?= ROOT . 'shop?category=' . strtolower($category["name"]) ?>" class="d-block category-card link-underline link-underline-opacity-0 text-secondary">
                        <img src="<?= ASSETS_PATH . 'images/' . $category["image"] ?>" class="category-img w-100" alt="Chocolates">
                        <h5 class="category-title"><?= $category["name"] ?></h5>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>


<!-- Featured Products -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="scroll-container">
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
            </div>
        </div>
    </div>
</section>




<?php include_once '../includes/footer.php' ?>