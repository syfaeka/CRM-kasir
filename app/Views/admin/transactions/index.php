<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="mb-0 text-gray-800"><i class="fas fa-receipt me-2 text-primary"></i> Transaction History</h4>
        </div>
        <div class="col-md-6">
            <form method="GET" action="/admin/transactions" class="d-flex justify-content-md-end gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i
                            class="fas fa-calendar text-muted"></i></span>
                    <input type="date" class="form-control border-start-0 ps-0" name="start_date"
                        value="<?= esc($start_date ?? '') ?>" placeholder="Start Date" aria-label="Start Date">
                    <span class="input-group-text bg-light border-start-0 border-end-0">to</span>
                    <input type="date" class="form-control border-start-0" name="end_date"
                        value="<?= esc($end_date ?? '') ?>" placeholder="End Date" aria-label="End Date">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <?php if (!empty($start_date) || !empty($end_date)): ?>
                        <a href="/admin/transactions" class="btn btn-outline-secondary" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted small">
                        <tr>
                            <th class="ps-4 py-3 border-0">Invoice #</th>
                            <th class="py-3 border-0">Date</th>
                            <th class="py-3 border-0">Customer</th>
                            <th class="py-3 border-0">Cashier</th>
                            <th class="py-3 border-0">Amount</th>
                            <th class="py-3 border-0 text-center">Status</th>
                            <th class="pe-4 py-3 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="fas fa-search fa-2x text-light-gray"></i></div>
                                    <p class="mb-0">No transactions found for the selected criteria.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td class="ps-4 fw-medium text-primary">
                                        #<?= esc($transaction->invoice_number) ?>
                                    </td>
                                    <td class="text-muted">
                                        <?= date('M d, Y', strtotime($transaction->created_at)) ?><br>
                                        <small><?= date('H:i', strtotime($transaction->created_at)) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($transaction->customer_name): ?>
                                            <div class="fw-medium text-dark"><?= esc($transaction->customer_name) ?></div>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">Walk-in Customer</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle text-center me-2"
                                                style="width: 24px; height: 24px; line-height: 24px;">
                                                <small><?= strtoupper(substr($transaction->cashier ?? 'U', 0, 1)) ?></small>
                                            </div>
                                            <span class="small"><?= esc($transaction->cashier ?? 'Unknown') ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        Rp <?= number_format($transaction->total_amount, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($transaction->payment_method === 'cash'): ?>
                                            <span class="badge bg-soft-success text-success rounded-pill px-3">Cash</span>
                                        <?php elseif ($transaction->payment_method === 'card'): ?>
                                            <span class="badge bg-soft-primary text-primary rounded-pill px-3">Card</span>
                                        <?php else: ?>
                                            <span
                                                class="badge bg-soft-info text-info rounded-pill px-3"><?= ucfirst($transaction->payment_method ?? 'N/A') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="/admin/transactions/<?= $transaction->id ?>"
                                            class="btn btn-sm btn-light text-primary border" data-bs-toggle="tooltip"
                                            title="View Detail">
                                            View Detail <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($transactions)): ?>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-end">
                    <?= $pager->links('default', 'bootstrap') ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Custom utility classes for badge layout */
    .bg-soft-success {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.1);
    }

    .avatar-sm {
        font-size: 0.75rem;
        font-weight: bold;
    }
</style>
<?= $this->endSection() ?>