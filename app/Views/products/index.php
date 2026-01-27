<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Product Management</h5>
                    <p class="text-muted small mb-0">Manage your inventory items</p>
                </div>
                <a href="/admin/products/create" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">Product Name</th>
                            <th class="py-3">SKU</th>
                            <th class="text-end py-3">Price</th>
                            <th class="text-center py-3">Stock Status</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">No products found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            <?= esc($product->name) ?>
                                        </div>
                                        <?php if ($product->description): ?>
                                            <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                                <?= esc($product->description) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">
                                            <?= esc($product->sku) ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp
                                        <?= number_format($product->price, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($product->stock == 0): ?>
                                            <span class="badge bg-danger-subtle text-danger px-3 rounded-pill">Out of Stock</span>
                                        <?php elseif ($product->stock < 10): ?>
                                            <span class="badge bg-warning-subtle text-warning px-3 rounded-pill">Low:
                                                <?= $product->stock ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success px-3 rounded-pill">
                                                <?= $product->stock ?> In Stock
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="/admin/products/<?= $product->id ?>/edit"
                                                class="btn btn-light btn-sm text-primary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="/admin/products/<?= $product->id ?>/delete" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');">
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