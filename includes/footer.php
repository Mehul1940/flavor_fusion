<?php
$auth_user = get_auth_user();
?>

</main>


<?php if (!empty($auth_user)) : ?>

    <footer class="bg-brand-secondary text-white py-4">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-md-6 py-3">
                    <h5>About Us</h5>
                    <p>Flavor Fusion is your go-to platform for delicious products, smooth transactions, and excellent service.</p>
                </div>


                <!-- Social Media -->
                <div class="col-md-6 py-3 d-flex justify-content-end">
                    <div>

                        <h5>Follow Us</h5>
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>

            <hr class="bg-light">

            <!-- Bottom Copyright -->
            <div class="text-center mt-3">
                <p class="mb-0">&copy; 2025 Flavor Fusion. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

<?php endif; ?>

<script src="<?php echo ASSETS_PATH . 'js/bootstrap.bundle.min.js' ?>"></script>
<script src="<?php echo ASSETS_PATH . 'js/index.js' ?>"></script>
</body>

</html>