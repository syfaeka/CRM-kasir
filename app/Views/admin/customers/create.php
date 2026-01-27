<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Add New Customer</h4>
        <a href="/admin/customers" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="/admin/customers" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Name *</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= old('name') ?>" placeholder="e.g., John Doe" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" 
                               value="<?= old('phone') ?>" placeholder="e.g., 08123456789" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= old('email') ?>" placeholder="e.g., john@email.com">
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Customer
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
