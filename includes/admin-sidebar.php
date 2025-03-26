<div id="sidebar-wrapper" class="bg-brand-secondary text-white p-3">
    <div class="text-center mb-4">
        <h3 class="my-0 py-2"><span class="text-primary">Flavor</span><span class="text-blue">Fusion</span></h3>
    </div>
    <hr class="text-light">
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin' ?>" class="nav-link text-white <?= $active === "index" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin/products' ?>" class="nav-link text-white <?= $active === "products" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-box-open me-2"></i> Manage Products
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin/categories' ?>" class="nav-link text-white <?= $active === "categories" ? "bg-brand-primary" :  "" ?>">
                <i class="fa-solid fa-list me-2"></i> Manage Categories
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin/staff' ?>" class="nav-link text-white <?= $active === "staff" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-user-tie me-2"></i> Manage Staff
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin/orders' ?>" class="nav-link text-white <?= $active === "orders" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-shopping-cart me-2"></i> Manage Orders
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin/customers' ?>" class="nav-link text-white <?= $active === "customers" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-users me-2"></i> View Customers
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="<?= ROOT . 'admin/payments' ?>" class="nav-link text-white <?= $active === "payments" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-credit-card me-2"></i> Payment & Billing
            </a>
        </li>
    </ul>

    <hr class="text-light">

    <ul class="nav flex-column mt-auto">
        <li class="nav-item">
            <a href="<?= ROOT . 'admin/settings' ?>" class="nav-link text-white <?= $active === "settings" ? "bg-brand-primary" :  "" ?>">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= ROOT . 'admin/logout' ?>" class="nav-link text-white">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

<div id="page-content-wrapper">
    <nav class="navbar navbar-light bg-white shadow-sm px-4 py-3">
        <div class="d-flex align-items-center">
            <button id="menu-toggle" class="btn btn-outline-secondary me-3">
                <i class="fas fa-bars"></i>
            </button>
            <span class="fw-bold fs-5">Dashboard</span>
        </div>
    </nav>