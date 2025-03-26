<?php
include_once '../../../init.php';
include DB_ROOT . 'database.php';

enable_admin_route();

$staffMembers = $connection->find("users", ["role" => "staff"]);
?>

<?php include_once '../../../includes/header.php'; ?>
<div class="d-flex">
    <?php
    $active = "staff";
    include_once '../../../includes/admin-sidebar.php';
    ?>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="section-title">Staff Members</h2>
                <a href="new" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($staffMembers) > 0): ?>
                                <?php foreach ($staffMembers as $staff): ?>
                                    <tr class="text-center">
                                        <td><?= $staff['id']; ?></td>
                                        <td><?= $staff['name']; ?></td>
                                        <td><?= $staff['email']; ?></td>
                                        <td><?= $staff['phone']; ?></td>
                                        <td>
                                            <a href="view?id=<?= $staff['id']; ?>" class="fw-semibold btn btn-sm btn-outline-primary">
                                                View <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No staff members found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../../includes/admin-footer.php'; ?>