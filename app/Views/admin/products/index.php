<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-box me-2"></i> Products</h4>
        <a href="/admin/products/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Product
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No products found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><code><?= esc($product->sku) ?></code></td>
                                    <td class="fw-bold">
                                        <?= esc($product->name) ?>
                                    </td>
                                    <td class="text-end">Rp
                                        <?= number_format($product->price, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($product->stock == 0): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php elseif ($product->stock < 5): ?>
                                            <span class="badge bg-warning text-dark">
                                                <?= $product->stock ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <?= $product->stock ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/products/<?= $product->id ?>/edit"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/admin/products/<?= $product->id ?>/delete" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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