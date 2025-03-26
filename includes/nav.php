<?php


$user = get_auth_user();

?>




<!-- TOP BAR -->

<div class="bg-brand-secondary text-white py-3 d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <span><i class="fas fa-phone-alt me-2"></i> Customer Support: +911234567890</span>
            </div>
            <div class="col-md-6 text-md-end">
                <span><i class="fas fa-truck me-2"></i> Free shipping on orders over ₹500</span>
            </div>
        </div>
    </div>
</div>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= ROOT ?>">
            <h3 class="my-0"><span class="text-primary">Flavor</span><span class="text-blue">Fusion</span></h3>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="search-form d-flex ms-4" action="<?= ROOT . 'shop' ?>">
                <input class="form-control search-input" type="search" placeholder="Search for snacks..." name="query" aria-label="Search">
                <button class="btn search-button" type="submit"><i class="fas fa-search text-white"></i></button>
            </form>

            <?php if ($user): ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="<?= ROOT . 'cart' ?>"><i class="fa-solid fa-cart-shopping me-1"></i> Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT . 'profile' ?>"><i class="fas fa-user me-1"></i> Account</a>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT . 'register' ?>"><i class="fa-solid fa-user-plus me-1"></i> Register</a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="<?= ROOT . 'login' ?>"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i></i> Login</a>
                    </li>
                </ul>
            <?php endif; ?>


        </div>
    </div>
</nav>

<main>