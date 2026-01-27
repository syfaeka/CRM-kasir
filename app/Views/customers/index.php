<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Customer Management</h5>
                    <p class="text-muted small mb-0">View customer loyalty data</p>
                </div>
                <a href="/admin/customers/create" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i> Add New Customer
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">Customer Name</th>
                            <th class="py-3">Contact</th>
                            <th class="text-center py-3">Loyalty Points</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">No customers found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            <?= esc($customer->name) ?>
                                        </div>
                                        <small class="text-muted">Member since
                                            <?= date('M Y', strtotime($customer->created_at)) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="mb-1"><i class="fas fa-phone-alt me-2 text-muted small"></i>
                                                <?= esc($customer->phone) ?>
                                            </span>
                                            <?php if ($customer->email): ?>
                                                <span class="text-muted small"><i class="fas fa-envelope me-2 small"></i>
                                                    <?= esc($customer->email) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="d-inline-flex align-items-center bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill fw-bold">
                                            <i class="fas fa-star me-2"></i>
                                            <?= number_format($customer->points) ?>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="/admin/customers/<?= $customer->id ?>"
                                                class="btn btn-light btn-sm text-info" data-bs-toggle="tooltip"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/customers/<?= $customer->id ?>/edit"
                                                class="btn btn-light btn-sm text-primary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="/admin/customers/<?= $customer->id ?>/delete" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-light btn-sm text-danger"
                                                    data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>