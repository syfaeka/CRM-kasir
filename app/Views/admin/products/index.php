<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-box me-2"></i> Products</h4>
        <a href="/admin/products/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Product
        </a>
    </div>

    <!-- Alert for JS responses -->
    <div id="alert-container"></div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
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
                                <tr id="row-<?= $product->id ?>">
                                    <td><code><?= esc($product->sku) ?></code></td>
                                    <td class="fw-bold">
                                        <?= esc($product->name) ?>
                                    </td>
                                    <td class="text-end">Rp
                                        <?= number_format($product->price, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <?php if ($product->stock == 0): ?>
                                                <span class="badge bg-danger me-2 stock-badge">Out of Stock</span>
                                            <?php elseif ($product->stock < 5): ?>
                                                <span
                                                    class="badge bg-warning text-dark me-2 stock-badge"><?= $product->stock ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success me-2 stock-badge"><?= $product->stock ?></span>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-xs btn-outline-success rounded-circle"
                                                onclick="openRestockModal(<?= $product->id ?>, '<?= esc($product->name) ?>')"
                                                title="Add Stock">
                                                <i class="fas fa-plus fa-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/products/<?= $product->id ?>/edit"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/admin/products/<?= $product->id ?>/delete" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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

<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="restockForm">
                <div class="modal-body">
                    <input type="hidden" id="restockProductId" name="product_id">

                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="restockProductName" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="stockQty" class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control" id="stockQty" name="quantity" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="stockNote" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="stockNote" name="note" rows="2"
                            placeholder="e.g. Supplier delivery"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveStock">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"
                            aria-hidden="true"></span>
                        Save Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRestockModal(id, name) {
        document.getElementById('restockProductId').value = id;
        document.getElementById('restockProductName').value = name;
        document.getElementById('stockQty').value = '';
        document.getElementById('stockNote').value = '';

        var modal = new bootstrap.Modal(document.getElementById('restockModal'));
        modal.show();
    }

    document.getElementById('restockForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const productId = document.getElementById('restockProductId').value;
        const btn = document.getElementById('btnSaveStock');
        const spinner = btn.querySelector('.spinner-border');

        // Show loading state
        btn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(this);

        fetch(`/admin/products/${productId}/add-stock`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('restockModal')).hide();

                    // Show success alert
                    showAlert('success', data.message);

                    // Update specific row badge without reload if possible, otherwise reload or just update text
                    // Let's update the badge text basically
                    const row = document.getElementById(`row-${productId}`);
                    if (row) {
                        const badge = row.querySelector('.stock-badge');
                        if (badge) {
                            const newStock = data.data.new_stock;
                            badge.textContent = newStock;

                            // Update color based on stock level
                            badge.className = 'badge me-2 stock-badge'; // reset
                            if (newStock == 0) {
                                badge.classList.add('bg-danger');
                                badge.textContent = 'Out of Stock';
                            } else if (newStock < 5) {
                                badge.classList.add('bg-warning', 'text-dark');
                            } else {
                                badge.classList.add('bg-success');
                            }
                        }
                    }
                } else {
                    showAlert('danger', data.message || 'Error updating stock');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An unexpected error occurred');
            })
            .finally(() => {
                btn.disabled = false;
                spinner.classList.add('d-none');
            });
    });

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.getElementById('alert-container').innerHTML = alertHtml;

        // Auto dismiss after 3s
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        }, 3000);
    }
</script>
<?= $this->endSection() ?>