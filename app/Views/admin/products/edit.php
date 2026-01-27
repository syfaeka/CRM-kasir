<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Product</h4>
        <a href="/admin/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="/admin/products/<?= $product->id ?>" method="POST">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">SKU *</label>
                        <input type="text" name="sku" class="form-control" value="<?= old('sku', $product->sku) ?>"
                            placeholder="e.g., KOPI-001" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Product Name *</label>
                        <input type="text" name="name" class="form-control" value="<?= old('name', $product->name) ?>"
                            placeholder="e.g., Espresso" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Product description..."><?= old('description', $product->description) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price (IDR) *</label>
                        <input type="number" name="price" class="form-control"
                            value="<?= old('price', $product->price) ?>" placeholder="e.g., 25000" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Stock *</label>
                        <input type="number" name="stock" class="form-control"
                            value="<?= old('stock', $product->stock) ?>" placeholder="e.g., 100" min="0" required>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Product
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>