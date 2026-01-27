<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h4>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Today's Sales</h6>
                            <h3 class="mb-0 fw-bold">
                                <?= number_format($totalSalesToday, 0, ',', '.') ?>
                            </h3>
                            <small class="text-muted">IDR</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-receipt fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Today's Transactions</h6>
                            <h3 class="mb-0 fw-bold">
                                <?= $totalTransactionsToday ?>
                            </h3>
                            <small class="text-muted">Orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Low Stock Items</h6>
                            <h3 class="mb-0 fw-bold">
                                <?= $lowStockCount ?>
                            </h3>
                            <small class="text-muted">Products</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Low Stock Alert -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-exclamation-circle text-warning me-2"></i> Low Stock Alert</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($lowStockProducts)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p class="mb-0">All products are well stocked!</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th class="text-end">Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                        <tr>
                                            <td class="fw-bold">
                                                <?= esc($product->name) ?>
                                            </td>
                                            <td><code><?= esc($product->sku) ?></code></td>
                                            <td class="text-end">
                                                <span class="badge bg-<?= $product->stock == 0 ? 'danger' : 'warning' ?>">
                                                    <?= $product->stock ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/admin/products/<?= $product->id ?>/edit"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-history text-primary me-2"></i> Recent Transactions</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentTransactions)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">No transactions yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th class="text-end">Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentTransactions as $tx): ?>
                                        <tr>
                                            <td><code><?= esc($tx->invoice_number) ?></code></td>
                                            <td class="text-end fw-bold">Rp
                                                <?= number_format($tx->total_amount, 0, ',', '.') ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= $tx->created_at ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>