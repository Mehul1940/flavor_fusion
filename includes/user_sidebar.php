<div class="col-md-3">
    <div class="profile-sidebar p-3 bg-light rounded">
        <div class="text-center">
            <img src="<?= ASSETS_PATH . 'images/default-user.png' ?>" alt="User Avatar" class="rounded-circle mb-3 border" width="80">
            <h4 class="fw-bold"><?= $user["name"] ?></h4>
            <p class="text-muted"><?= $user["email"] ?></p>
        </div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= ROOT . 'profile' ?>" class="user-links nav-link <?= $active === 'profile' ?  'text-primary' : 'text-secondary' ?>"><i class="fas fa-user me-2"></i> Update Profile</a>
            </li>
            <li class="nav-item">
                <a href="<?= ROOT . 'profile/address' ?>" class="user-links nav-link <?= $active === 'address' ?  'text-primary' : 'text-secondary' ?>"><i class="fas fa-map-marker-alt me-2"></i> Add Address</a>
            </li>
            <li class="nav-item">
                <a href="<?= ROOT . 'profile/orders' ?>" class="user-links nav-link <?= $active === 'orders' ?  'text-primary' : 'text-secondary' ?>"><i class="fas fa-box me-2"></i> View Orders</a>
            </li>
            <li class="nav-item">
                <a href="<?= ROOT . 'profile/change-password' ?>" class="user-links nav-link <?= $active === 'change-password' ?  'text-primary' : 'text-secondary' ?>"><i class="fas fa-key me-2"></i> Change Password</a>
            </li>
            <li class="nav-item">
                <a href="<?= ROOT . 'profile/logout' ?>" class="user-links nav-link text-secondary"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </li>
        </ul>
    </div>
</div>