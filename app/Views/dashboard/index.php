<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Welcome Back,
                <?= esc(session()->get('user_name')) ?>!
            </h3>
            <p class="text-muted">Here's what's happening in your business today.</p>
        </div>
        <div class="text-muted small">
            <i class="fas fa-calendar-alt me-1"></i>
            <?= date('l, d F Y') ?>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <!-- Sales Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-money-bill-wave fa-5x text-primary"></i>
                    </div>
                    <h6 class="text-uppercase text-muted fw-bold mb-2 small">Total Sales Today</h6>
                    <h2 class="display-6 fw-bold text-dark mb-0">
                        Rp
                        <?= number_format($totalSalesToday, 0, ',', '.') ?>
                    </h2>
                    <small class="text-success fw-bold">
                        <i class="fas fa-arrow-up"></i> Revenue
                    </small>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-shopping-bag fa-5x text-info"></i>
                    </div>
                    <h6 class="text-uppercase text-muted fw-bold mb-2 small">Total Transactions</h6>
                    <h2 class="display-6 fw-bold text-dark mb-0">
                        <?= number_format($totalTransactionsToday) ?>
                    </h2>
                    <small class="text-info fw-bold">
                        Orders Processed
                    </small>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-exclamation-circle fa-5x text-warning"></i>
                    </div>
                    <h6 class="text-uppercase text-muted fw-bold mb-2 small">Low Stock Alert</h6>
                    <h2 class="display-6 fw-bold text-dark mb-0">
                        <?= $lowStockCount ?>
                    </h2>
                    <small class="<?= $lowStockCount > 0 ? 'text-danger' : 'text-success' ?> fw-bold">
                        <?= $lowStockCount > 0 ? 'Items need attention' : 'Inventory healthy' ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row g-4">
        <!-- Low Stock Table -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-boxes me-2 text-warning"></i> Low Stock Products (<
                            5)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-3">Product Name</th>
                                <th>SKU</th>
                                <th class="text-end pe-3">Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lowStockProducts)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">
                                        No low stock items found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($lowStockProducts as $p): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark">
                                            <?= esc($p->name) ?>
                                        </td>
                                        <td class="small text-muted">
                                            <?= esc($p->sku) ?>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 rounded-pill">
                                                <?= $p->stock ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-bolt me-2 text-primary"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="/pos"
                                class="btn btn-outline-primary w-100 py-3 border-2 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="fas fa-cash-register fa-2x"></i>
                                <span class="fw-bold">Open POS</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/admin/products/create"
                                class="btn btn-outline-success w-100 py-3 border-2 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="fas fa-plus-circle fa-2x"></i>
                                <span class="fw-bold">Add Product</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/admin/customers/create"
                                class="btn btn-outline-info w-100 py-3 border-2 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="fas fa-user-plus fa-2x"></i>
                                <span class="fw-bold">Add Customer</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/admin/products"
                                class="btn btn-outline-secondary w-100 py-3 border-2 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
                                <i class="fas fa-boxes fa-2x"></i>
                                <span class="fw-bold">Inventory</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>