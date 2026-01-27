<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row h-100">
    <!-- LEFT PANEL: Product Grid -->
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white p-3">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0 bg-light"
                        placeholder="Search products (Name or SKU)..." autocomplete="off">
                </div>
            </div>
            <div class="card-body bg-light overflow-auto" style="height: calc(100vh - 180px);">
                <div id="productLoader" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- Product Grid Container -->
                <div class="row g-3" id="productGrid">
                    <!-- Products will be loaded here via JS -->
                </div>
                <div id="noProducts" class="text-center py-5 d-none">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No products found.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: Cart -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Current Order</h5>
            </div>
            <div class="card-body d-flex flex-column p-0">
                <!-- Customer Select -->
                <div class="p-3 border-bottom bg-white">
                    <label class="form-label small text-muted fw-bold text-uppercase">Customer</label>
                    <select id="customerSelect" class="form-select" required>
                        <option value="" selected disabled>-- Select Customer --</option>
                        <option value="general">General Customer (No Points)</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer->id ?>">
                                <?= esc($customer->name) ?> (
                                <?= esc($customer->phone) ?>) -
                                <?= $customer->points ?> pts
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cart Items List -->
                <div class="flex-grow-1 overflow-auto p-3" style="background-color: #f8f9fa;">
                    <div id="cartItems">
                        <!-- Cart items will be rendered here -->
                        <div class="text-center py-5 text-muted empty-cart-msg">
                            <i class="fas fa-basket-shopping fa-3x mb-3 opacity-50"></i>
                            <p>Cart is empty</p>
                        </div>
                    </div>
                </div>

                <!-- Footer: Totals & Checkout -->
                <div class="p-3 bg-white border-top shadow-lg">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold" id="cartSubtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h4 fw-bold mb-0">Total</span>
                        <span class="h4 fw-bold text-primary mb-0" id="cartTotal">Rp 0</span>
                    </div>
                    <button id="btnCheckout" class="btn btn-primary w-100 btn-lg py-3 fw-bold rounded-3" disabled>
                        <i class="fas fa-paper-plane me-2"></i> PAY & CHECKOUT
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // State
    let cart = [];
    const userId = 2; // Hardcoded logged-in cashier ID (Budi)

    $(document).ready(function () {
        loadProducts();

        // Search Product Logic
        let searchTimeout;
        $('#searchInput').on('keyup', function () {
            clearTimeout(searchTimeout);
            const keyword = $(this).val();
            searchTimeout = setTimeout(() => {
                loadProducts(keyword);
            }, 300);
        });

        // Checkout Button Logic
        $('#btnCheckout').on('click', function () {
            processCheckout();
        });

        // Customer Select Logic
        $('#customerSelect').on('change', function () {
            checkCheckoutButton();
        });
    });

    // Loading Products
    function loadProducts(keyword = '') {
        const url = `/api/pos/products?q=${keyword}`;

        $('#productLoader').removeClass('d-none');
        $('#productGrid').addClass('d-none');
        $('#noProducts').addClass('d-none');

        $.get(url, function (response) {
            $('#productLoader').addClass('d-none');
            $('#productGrid').removeClass('d-none');
            const products = response.data;
            let html = '';

            if (products.length === 0) {
                $('#noProducts').removeClass('d-none');
            } else {
                products.forEach(p => {
                    // Check if stock is low
                    let stockBadge = '';
                    if (p.stock === 0) {
                        stockBadge = '<span class="badge bg-danger position-absolute top-0 end-0 m-2">Out of Stock</span>';
                    } else if (p.stock < 10) {
                        stockBadge = `<span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Low Stock: ${p.stock}</span>`;
                    } else {
                        stockBadge = `<span class="badge bg-success position-absolute top-0 end-0 m-2">Stock: ${p.stock}</span>`;
                    }

                    // Card HTML
                    const disabled = p.stock === 0 ? 'disabled' : '';
                    const btnClass = p.stock === 0 ? 'btn-secondary' : 'btn-outline-primary';
                    const btnText = p.stock === 0 ? 'Sold Out' : '<i class="fas fa-plus"></i> Add';

                    html += `
                        <div class="col-6 col-lg-4 col-xl-3">
                            <div class="card h-100 shadow-sm border-0 product-card">
                                <div class="position-relative bg-light text-center p-4 text-muted border-bottom" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-mug-hot fa-3x opacity-25"></i>
                                    ${stockBadge}
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="card-title fw-bold text-truncate mb-1" title="${p.name}">${p.name}</h6>
                                    <small class="text-muted text-uppercase mb-2" style="font-size: 0.75rem;">${p.sku}</small>
                                    <h6 class="text-primary fw-bold mb-3">${formatRupiah(p.price)}</h6>
                                    <button class="btn ${btnClass} btn-sm mt-auto w-100 fw-bold" 
                                        onclick='addToCart(${JSON.stringify(p)})' ${disabled}>
                                        ${btnText}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            $('#productGrid').html(html);
        }).fail(function () {
            $('#productLoader').addClass('d-none');
            Swal.fire('Error', 'Failed to load products', 'error');
        });
    }

    // Cart Logic
    function addToCart(product) {
        const existingItem = cart.find(item => item.product_id === product.id);

        if (existingItem) {
            if (existingItem.quantity >= product.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit Reached',
                    text: `Only ${product.stock} items available.`,
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }
            existingItem.quantity++;
        } else {
            cart.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
                max_stock: product.stock
            });
        }
        renderCart();
    }

    function renderCart() {
        const container = $('#cartItems');
        let html = '';
        let total = 0;

        if (cart.length === 0) {
            container.html(`
                <div class="text-center py-5 text-muted empty-cart-msg">
                    <i class="fas fa-basket-shopping fa-3x mb-3 opacity-50"></i>
                    <p>Cart is empty</p>
                </div>
            `);
            $('#btnCheckout').prop('disabled', true);
            $('#cartTotal').text('Rp 0');
            $('#cartSubtotal').text('Rp 0');
            return;
        }

        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            html += `
                <div class="card mb-2 border-0 shadow-sm">
                    <div class="card-body p-2 d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 text-truncate fw-bold">${item.name}</h6>
                            <small class="text-muted text-nowrap">
                                ${formatRupiah(item.price)} x 
                            </small>
                        </div>
                        <div class="d-flex align-items-center mx-2">
                             <input type="number" class="form-control form-control-sm text-center fw-bold border-secondary" 
                                value="${item.quantity}" min="1" max="${item.max_stock}"
                                style="width: 50px;" onchange="updateQuantity(${index}, this.value)">
                        </div>
                        <div class="fw-bold text-primary me-2 text-end" style="min-width: 80px;">
                            ${formatRupiah(subtotal)}
                        </div>
                        <button class="btn btn-link text-danger p-0 ms-1" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.html(html);
        $('#cartSubtotal').text(formatRupiah(total));
        $('#cartTotal').text(formatRupiah(total));

        checkCheckoutButton();
    }

    function updateQuantity(index, newQty) {
        newQty = parseInt(newQty);
        const item = cart[index];

        if (newQty <= 0) {
            removeFromCart(index);
            return;
        }

        if (newQty > item.max_stock) {
            Swal.fire('Stock Warning', `Only ${item.max_stock} available.`, 'warning');
            renderCart(); // reset UI
            return;
        }

        cart[index].quantity = newQty;
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function checkCheckoutButton() {
        const customerSelected = $('#customerSelect').val() !== null;
        const cartNotEmpty = cart.length > 0;

        if (cartNotEmpty && customerSelected) {
            $('#btnCheckout').prop('disabled', false);
        } else {
            $('#btnCheckout').prop('disabled', true);
        }
    }

    // Checkout Process
    function processCheckout() {
        const customerId = $('#customerSelect').val();

        // Payload Construction
        const payload = {
            user_id: userId,
            customer_id: customerId === 'general' ? null : customerId,
            items: cart.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity
            }))
        };

        // UI Feedback
        const btn = $('#btnCheckout');
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Processing...');

        $.ajax({
            url: '/api/pos/checkout',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function (response) {
                // Success Modal
                let pointsInfo = '';
                if (response.data.transaction.points_earned > 0) {
                    pointsInfo = `<br><span class="text-success fw-bold">+${response.data.transaction.points_earned} Points Earned!</span>`;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful!',
                    html: `
                        <h3 class="fw-bold">${formatRupiah(response.data.transaction.total_amount)}</h3>
                        <p class="text-muted mb-0">Invoice: ${response.data.transaction.invoice_number}</p>
                        ${pointsInfo}
                    `,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    // Reset Logic
                    cart = [];
                    renderCart();
                    $('#customerSelect').val(''); // Reset customer select
                    checkCheckoutButton();
                    loadProducts(); // Refresh stock

                    // Could print receipt here
                });
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.messages?.error || xhr.responseJSON?.message || 'Transaction failed';
                Swal.fire({
                    icon: 'error',
                    title: 'Checkout Failed',
                    text: msg
                });
            },
            complete: function () {
                btn.html(originalText);
                checkCheckoutButton();
            }
        });
    }

    // Helper
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
</script>
<?= $this->endSection() ?>