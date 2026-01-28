<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-gray-800"><i class="fas fa-file-invoice me-2 text-primary"></i> Invoice Details</h4>
        <a href="/admin/transactions" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- Clean Invoice Header -->
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-primary">INVOICE</h5>
                            <h3 class="mb-0 text-dark">#<?= esc($transaction->invoice_number) ?></h3>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small text-uppercase mb-1">Date Issued</div>
                            <div class="fw-bold"><?= date('F d, Y', strtotime($transaction->created_at)) ?></div>
                            <div class="small text-muted"><?= date('H:i', strtotime($transaction->created_at)) ?></div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Customer & Cashier Info -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="text-muted small text-uppercase mb-2">Billed To</div>
                            <?php if ($customer): ?>
                                <h6 class="mb-1 fw-bold"><?= esc($customer->name) ?></h6>
                                <?php if ($customer->email): ?>
                                    <div class="mb-1 text-muted small">
                                        <i class="fas fa-envelope me-2 w-20"></i><?= esc($customer->email) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($customer->phone): ?>
                                    <div class="mb-1 text-muted small">
                                        <i class="fas fa-phone me-2 w-20"></i><?= esc($customer->phone) ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <h6 class="text-muted fst-italic">Walk-in Customer</h6>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="text-muted small text-uppercase mb-2">Served By</div>
                            <h6 class="mb-1 fw-bold"><?= esc($user->username ?? 'Unknown') ?></h6>
                            <?php if (isset($user->email)): ?>
                                <div class="text-muted small"><?= esc($user->email) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Transaction Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light text-uppercase small text-muted">
                                <tr>
                                    <th class="ps-3" style="width: 40%">Item Description</th>
                                    <th class="text-center" style="width: 15%">Qty</th>
                                    <th class="text-end" style="width: 20%">Price</th>
                                    <th class="text-end pe-3" style="width: 25%">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="border-bottom">
                                <?php foreach ($details as $detail): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">
                                                <?= esc($detail->product->name ?? 'Unknown Product') ?></div>
                                            <div class="small text-muted">SKU: <?= esc($detail->product->sku ?? 'N/A') ?>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $detail->quantity ?></td>
                                        <td class="text-end text-muted">Rp <?= number_format($detail->price, 0, ',', '.') ?>
                                        </td>
                                        <td class="text-end fw-bold text-dark pe-3">
                                            Rp <?= number_format($detail->subtotal, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Summary -->
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <table class="table table-sm table-borderless">
                                <?php if (isset($transaction->subtotal) && $transaction->subtotal > 0): ?>
                                    <tr>
                                        <td class="text-muted">Subtotal</td>
                                        <td class="text-end fw-medium">Rp
                                            <?= number_format($transaction->subtotal, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($transaction->tax) && $transaction->tax > 0): ?>
                                    <tr>
                                        <td class="text-muted">Tax</td>
                                        <td class="text-end fw-medium">Rp
                                            <?= number_format($transaction->tax, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="border-top">
                                    <td class="pt-3 fs-5 fw-bold text-dark">Total</td>
                                    <td class="pt-3 fs-5 fw-bold text-primary text-end">Rp
                                        <?= number_format($transaction->total_amount, 0, ',', '.') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Details Box -->
                    <div class="bg-light rounded p-3 mt-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <span class="text-muted me-2">Payment Method:</span>
                                <span
                                    class="badge bg-dark text-uppercase"><?= ucfirst($transaction->payment_method ?? 'N/A') ?></span>
                            </div>
                            <?php if (isset($transaction->cash_received) && $transaction->cash_received > 0): ?>
                                <div class="col-md-6 text-md-end">
                                    <span class="text-muted me-2">Paid:</span>
                                    <span class="fw-bold me-3">Rp
                                        <?= number_format($transaction->cash_received, 0, ',', '.') ?></span>
                                    <span class="text-muted me-2">Change:</span>
                                    <span class="fw-bold text-success">Rp
                                        <?= number_format($transaction->change_amount, 0, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if ($transaction->points_earned > 0): ?>
                                <div class="text-warning">
                                    <i class="fas fa-star me-1"></i> <strong><?= $transaction->points_earned ?></strong>
                                    Loyalty Points Earned
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="/pos/receipt/<?= $transaction->id ?>" class="btn btn-primary" target="_blank">
                                <i class="fas fa-print me-2"></i> Print Receipt
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 text-muted small">
                © <?= date('Y') ?> CRM Kasir System. All rights reserved.
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>