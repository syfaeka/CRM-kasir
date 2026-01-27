<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-user me-2"></i> Customer Details</h4>
        <div>
            <a href="/admin/customers/<?= $customer->id ?>/edit" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="/admin/customers" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Customer Info -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x text-primary"></i>
                    </div>
                    <h4 class="mb-1">
                        <?= esc($customer->name) ?>
                    </h4>
                    <p class="text-muted mb-3">
                        <?= esc($customer->phone) ?>
                    </p>

                    <div class="bg-warning bg-opacity-10 rounded p-3 mb-3">
                        <h5 class="mb-0 text-warning">
                            <i class="fas fa-star me-1"></i>
                            <?= $customer->points ?> Points
                        </h5>
                    </div>

                    <?php if ($customer->email): ?>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-envelope me-1"></i>
                            <?= esc($customer->email) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-history text-primary me-2"></i> Transaction History</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($transactions)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-receipt fa-2x mb-2"></i>
                            <p class="mb-0">No transaction history.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-center">Points</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $tx): ?>
                                        <tr>
                                            <td><code><?= esc($tx->invoice_number) ?></code></td>
                                            <td class="text-end fw-bold">Rp
                                                <?= number_format($tx->total_amount, 0, ',', '.') ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">+
                                                    <?= $tx->points_earned ?>
                                                </span>
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