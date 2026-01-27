<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --pos-bg: #f4f6f9;
        --card-border-radius: 12px;
        --primary-color: #667eea;
        --secondary-color: #764ba2;
    }

    body {
        background-color: var(--pos-bg);
        overflow-x: hidden;
        /* Prevent horizontal scroll */
    }

    /* Product Area */
    .product-area {
        height: calc(100vh - 80px);
        /* Adjust based on navbar height */
        overflow-y: auto;
        padding-right: 10px;
    }

    .category-pills .nav-pills .nav-link {
        color: #6c757d;
        background: white;
        border: 1px solid #e9ecef;
        margin-right: 10px;
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .category-pills .nav-pills .nav-link.active,
    .category-pills .nav-pills .nav-link:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    .product-card {
        border: none;
        border-radius: var(--card-border-radius);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        background: white;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .product-img-wrapper {
        height: 140px;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        font-weight: 600;
        color: var(--secondary-color);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Cart Area */
    .cart-area {
        height: calc(100vh - 10px);
        /* Fill height minus some margin */
        position: sticky;
        top: 10px;
        background: white;
        border-radius: var(--card-border-radius);
        box-shadow: -5px 0 20px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
    }

    .cart-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eee;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .qty-control {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 2px;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: white;
        border-radius: 6px;
        color: var(--primary-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background: var(--primary-color);
        color: white;
    }

    .cart-footer {
        padding: 20px;
        background: #fcfcfc;
        border-top: 1px solid #eee;
        border-radius: 0 0 var(--card-border-radius) var(--card-border-radius);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        font-size: 1.5rem;
        font-weight: 800;
        color: #2c3e50;
    }

    /* Custom Scrollbar for Cart */
    .cart-items::-webkit-scrollbar {
        width: 6px;
    }

    .cart-items::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .cart-items::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    /* Select2 Customization */
    .select2-container .select2-selection--single {
        height: 45px;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        display: flex;
        align-items: center;
        padding-left: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px;
    }
</style>

<div class="container-fluid pe-0">
    <div class="row g-0">
        <!-- Left Side: Product Area -->
        <div class="col-md-8 p-3">
            <div class="d-flex flex-column h-100">
                <!-- Header & Search -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Point of Sale</h4>
                        <p class="text-muted small mb-0 fs-6"><?= date('l, d F Y') ?></p>
                    </div>
                    <div class="w-50 position-relative">
                        <i
                            class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="searchInput"
                            class="form-control form-control-lg ps-5 rounded-pill border-0 shadow-sm"
                            placeholder="Search menu item...">
                    </div>
                </div>

                <!-- Category Pills -->
                <div class="category-pills mb-4 overflow-auto pb-2">
                    <ul class="nav nav-pills flex-nowrap" id="categoryTabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-category="all">
                                <i class="fas fa-th-large me-2"></i> All Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-category="Coffee">
                                <i class="fas fa-mug-hot me-2"></i> Coffee
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-category="Non-Coffee">
                                <i class="fas fa-glass-whiskey me-2"></i> Non-Coffee
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-category="Snack">
                                <i class="fas fa-cookie me-2"></i> Snack
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-category="Food">
                                <i class="fas fa-utensils me-2"></i> Food
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Product Grid -->
                <div class="product-area text-start" id="productGridWrapper">
                    <div class="row g-3" id="productGrid">
                        <!-- Products will be loaded here via JS -->
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Cart Area -->
        <div class="col-md-4 ps-md-0 pe-md-3 py-3">
            <div class="cart-area">
                <!-- Cart Header -->
                <div class="cart-header">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Customer</label>
                        <select id="customerSelect" class="form-select">
                            <option value="">General Customer</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c->id ?>" data-points="<?= $c->points ?>"><?= esc($c->name) ?>
                                    (<?= $c->phone ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div id="customerPointsBadge" class="mt-2 d-none">
                            <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i> <span
                                    id="customerPoints">0</span> Points</span>
                        </div>
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="cart-items" id="cartItems">
                    <!-- Cart items inserted here -->
                    <div class="text-center text-muted py-5 mt-5">
                        <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                        <p>Cart is empty</p>
                    </div>
                </div>

                <!-- Cart Footer -->
                <div class="cart-footer">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="fw-bold" id="cartSubtotal">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (11%)</span>
                        <span class="fw-bold" id="cartTax">Rp 0</span>
                    </div>
                    <div class="total-row mb-4">
                        <span>Total</span>
                        <span class="text-primary" id="cartTotal">Rp 0</span>
                    </div>
                    <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-lg" id="btnPay" disabled>
                        <i class="fas fa-wallet me-2"></i> Process Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-receipt me-2"></i> Payment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left: Summary -->
                    <div class="col-md-5 bg-light p-4 border-end">
                        <h6 class="text-uppercase text-muted fw-bold small mb-3">Order Summary</h6>
                        <div id="paymentSummaryItems" class="mb-4" style="max-height: 200px; overflow-y: auto;">
                            <!-- Items clone -->
                        </div>
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold" id="modalSubtotal">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax (11%)</span>
                                <span class="fw-bold" id="modalTax">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span class="h5 mb-0 fw-bold">Total Pay</span>
                                <span class="h4 mb-0 fw-bold text-primary" id="modalTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Payment Form -->
                    <div class="col-md-7 p-4">
                        <form id="paymentForm">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Payment Method</label>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="payment_method" id="payCash"
                                            value="cash" checked>
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3" for="payCash">
                                            <i class="fas fa-money-bill-wave fa-lg mb-2 d-block"></i>
                                            Cash
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="payment_method" id="payQris"
                                            value="qris">
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3" for="payQris">
                                            <i class="fas fa-qrcode fa-lg mb-2 d-block"></i>
                                            QRIS
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4" id="cashInputGroup">
                                <label class="form-label fw-bold">Cash Received (Rp)</label>
                                <input type="number" class="form-control form-control-lg bg-light border-0"
                                    id="cashReceived" placeholder="0">

                                <div class="mt-2 d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        onclick="setCash(10000)">10k</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        onclick="setCash(20000)">20k</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        onclick="setCash(50000)">50k</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        onclick="setCash(100000)">100k</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        onclick="setCash('exact')">Exact</button>
                                </div>
                            </div>

                            <div class="card bg-success bg-opacity-10 border-success border-opacity-25 mb-4"
                                id="changeGroup">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-success fw-bold">Change (Kembalian)</span>
                                        <span class="h4 mb-0 fw-bold text-success" id="changeAmount">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold fs-5 shadow-lg"
                                id="btnConfirmPay">
                                <i class="fas fa-print me-2"></i> Print Receipt & Finish
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // State management
    const state = {
        products: [],
        cart: [],
        filteredProducts: [],
        currentCategory: 'all',
        searchQuery: '',
        taxRate: 0.11
    };

    // DOM Elements
    const els = {
        productGrid: document.getElementById('productGrid'),
        searchInput: document.getElementById('searchInput'),
        categoryLinks: document.querySelectorAll('#categoryTabs .nav-link'),
        cartItems: document.getElementById('cartItems'),
        cartSubtotal: document.getElementById('cartSubtotal'),
        cartTax: document.getElementById('cartTax'),
        cartTotal: document.getElementById('cartTotal'),
        btnPay: document.getElementById('btnPay'),
        customerSelect: document.getElementById('customerSelect'),
        customerPointsBadge: document.getElementById('customerPointsBadge'),
        customerPoints: document.getElementById('customerPoints'),
        // Modal
        paymentModal: new bootstrap.Modal(document.getElementById('paymentModal')),
        modalSubtotal: document.getElementById('modalSubtotal'),
        modalTax: document.getElementById('modalTax'),
        modalTotal: document.getElementById('modalTotal'),
        cashReceived: document.getElementById('cashReceived'),
        changeAmount: document.getElementById('changeAmount'),
        paymentForm: document.getElementById('paymentForm'),
        cashInputGroup: document.getElementById('cashInputGroup'),
        changeGroup: document.getElementById('changeGroup')
    };

    // Initialization
    async function init() {
        await loadProducts();
        renderProducts();
        updateCartUI();
        setupEventListeners();
    }

    // Load Items
    async function loadProducts() {
        try {
            const response = await fetch('/api/pos/products?limit=100'); // Load ample products
            const result = await response.json();
            if (result.success) {
                state.products = result.data;
                state.filteredProducts = result.data;
            }
        } catch (error) {
            console.error('Failed to load products', error);
            Swal.fire('Error', 'Failed to load products.', 'error');
        }
    }

    // Filtering
    function filterProducts() {
        state.filteredProducts = state.products.filter(p => {
            const matchesCategory = state.currentCategory === 'all' || p.category === state.currentCategory;
            const matchesSearch = p.name.toLowerCase().includes(state.searchQuery.toLowerCase()) ||
                p.sku.toLowerCase().includes(state.searchQuery.toLowerCase());
            return matchesCategory && matchesSearch;
        });
        renderProducts();
    }

    // Render Grid
    function renderProducts() {
        els.productGrid.innerHTML = '';

        if (state.filteredProducts.length === 0) {
            els.productGrid.innerHTML = `
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                    <p>No products found matching your criteria.</p>
                </div>
            `;
            return;
        }

        state.filteredProducts.forEach(product => {
            const inStock = product.stock > 0;
            const card = document.createElement('div');
            card.className = 'col-md-4 col-lg-3 col-xl-3';
            card.innerHTML = `
                <div class="card product-card h-100 ${!inStock ? 'opacity-50' : ''}" onclick="addToCart(${product.id})">
                    <div class="product-img-wrapper position-relative">
                        <img src="https://placehold.co/200x200/png?text=${encodeURIComponent(product.name)}" alt="${product.name}">
                        <div class="product-badge">${product.category}</div>
                        ${!inStock ? '<div class="position-absolute w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center fw-bold text-danger">OUT OF STOCK</div>' : ''}
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold text-truncate mb-1" title="${product.name}">${product.name}</h6>
                        <small class="text-muted d-block text-truncate mb-2">${product.sku}</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">${formatRupiah(product.price)}</span>
                            <small class="text-muted small">${product.stock} left</small>
                        </div>
                    </div>
                </div>
            `;
            if (inStock) {
                els.productGrid.appendChild(card);
            } else {
                // Remove onclick if out of stock
                card.querySelector('.product-card').onclick = null;
                els.productGrid.appendChild(card);
            }
        });
    }

    // Cart Logic
    window.addToCart = function (productId) {
        const product = state.products.find(p => p.id === productId);
        if (!product) return;

        const existingItem = state.cart.find(i => i.id === productId);

        if (existingItem) {
            if (existingItem.quantity < product.stock) {
                existingItem.quantity++;
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Max stock reached',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }
        } else {
            state.cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                sku: product.sku,
                quantity: 1,
                maxStock: product.stock
            });
        }
        updateCartUI();
    };

    window.updateItemQty = function (productId, change) {
        const item = state.cart.find(i => i.id === productId);
        if (!item) return;

        const newQty = item.quantity + change;

        if (newQty > 0 && newQty <= item.maxStock) {
            item.quantity = newQty;
        } else if (newQty > item.maxStock) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Max stock reached',
                showConfirmButton: false,
                timer: 1500
            });
        }

        updateCartUI();
    };

    window.removeFromCart = function (productId) {
        state.cart = state.cart.filter(i => i.id !== productId);
        updateCartUI();
    };

    function updateCartUI() {
        els.cartItems.innerHTML = '';

        if (state.cart.length === 0) {
            els.cartItems.innerHTML = `
                <div class="text-center text-muted py-5 mt-5">
                    <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                    <p>Cart is empty</p>
                </div>
            `;
            els.btnPay.disabled = true;
            updateTotals(0);
            return;
        }

        state.cart.forEach(item => {
            const itemRow = document.createElement('div');
            itemRow.className = 'cart-item';
            itemRow.innerHTML = `
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold text-truncate" style="max-width: 160px;">${item.name}</h6>
                    <small class="text-muted d-block">${formatRupiah(item.price)}</small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="qty-control">
                        <button class="qty-btn" onclick="updateItemQty(${item.id}, -1)"><i class="fas fa-minus small"></i></button>
                        <span class="px-2 fw-bold" style="min-width: 30px; text-align: center;">${item.quantity}</span>
                        <button class="qty-btn" onclick="updateItemQty(${item.id}, 1)"><i class="fas fa-plus small"></i></button>
                    </div>
                    <button class="btn btn-link text-danger p-0" onclick="removeFromCart(${item.id})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            els.cartItems.appendChild(itemRow);
        });

        // Calculate Totals
        const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        updateTotals(subtotal);
        els.btnPay.disabled = false;
    }

    function updateTotals(subtotal) {
        const tax = Math.round(subtotal * state.taxRate);
        const total = subtotal + tax;

        els.cartSubtotal.textContent = formatRupiah(subtotal);
        els.cartTax.textContent = formatRupiah(tax);
        els.cartTotal.textContent = formatRupiah(total);

        // Update Modal Totals too
        els.modalSubtotal.textContent = formatRupiah(subtotal);
        els.modalTax.textContent = formatRupiah(tax);
        els.modalTotal.textContent = formatRupiah(total);
        els.modalTotal.dataset.value = total; // Store numeric value
    }

    // Helper: Currency Format
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    // Events
    function setupEventListeners() {
        // Search
        els.searchInput.addEventListener('input', (e) => {
            state.searchQuery = e.target.value;
            filterProducts();
        });

        // Category Tabs
        els.categoryLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                els.categoryLinks.forEach(l => l.classList.remove('active'));
                e.currentTarget.classList.add('active');
                state.currentCategory = e.currentTarget.dataset.category;
                filterProducts();
            });
        });

        // Customer Selection
        els.customerSelect.addEventListener('change', (e) => {
            const option = e.target.selectedOptions[0];
            if (option.value) {
                els.customerPoints.textContent = option.dataset.points;
                els.customerPointsBadge.classList.remove('d-none');
            } else {
                els.customerPointsBadge.classList.add('d-none');
            }
        });

        // Pay Button
        els.btnPay.addEventListener('click', () => {
            openPaymentModal();
        });

        // Payment Method Toggle
        const paymentMethods = document.getElementsByName('payment_method');
        paymentMethods.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if (e.target.value === 'cash') {
                    els.cashInputGroup.classList.remove('d-none');
                    els.changeGroup.classList.remove('d-none');
                    els.cashReceived.focus();
                } else {
                    els.cashInputGroup.classList.add('d-none');
                    els.changeGroup.classList.add('d-none');
                }
            });
        });

        // Cash Input Calculation
        els.cashReceived.addEventListener('input', calculateChange);

        // Confirm Payment
        els.paymentForm.addEventListener('submit', handleCheckout);
    }

    function openPaymentModal() {
        // Render Order Summary in Modal
        const container = document.getElementById('paymentSummaryItems');
        container.innerHTML = '';
        state.cart.forEach(item => {
            const row = document.createElement('div');
            row.className = 'd-flex justify-content-between mb-2 small';
            row.innerHTML = `
                <span>${item.quantity}x ${item.name}</span>
                <span class="fw-bold">${formatRupiah(item.price * item.quantity)}</span>
            `;
            container.appendChild(row);
        });

        // Reset Form
        els.cashReceived.value = '';
        document.getElementById('changeAmount').textContent = 'Rp 0';
        document.getElementById('payCash').checked = true;
        els.cashInputGroup.classList.remove('d-none');
        els.changeGroup.classList.remove('d-none');

        els.paymentModal.show();
        setTimeout(() => els.cashReceived.focus(), 500);
    }

    window.setCash = function (amount) {
        if (amount === 'exact') {
            const total = parseFloat(els.modalTotal.dataset.value);
            els.cashReceived.value = total;
        } else {
            els.cashReceived.value = amount;
        }
        calculateChange();
    };

    function calculateChange() {
        const total = parseFloat(els.modalTotal.dataset.value);
        const cash = parseFloat(els.cashReceived.value) || 0;
        const change = cash - total;

        if (change >= 0) {
            document.getElementById('changeAmount').textContent = formatRupiah(change);
            document.getElementById('changeAmount').classList.remove('text-danger');
            document.getElementById('changeAmount').classList.add('text-success');
            els.btnConfirmPay.disabled = false;
        } else {
            document.getElementById('changeAmount').textContent = formatRupiah(change);
            document.getElementById('changeAmount').classList.add('text-danger');
            document.getElementById('changeAmount').classList.remove('text-success');
            els.btnConfirmPay.disabled = true; // Disable if insufficient cash
        }
    }

    async function handleCheckout(e) {
        e.preventDefault();

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const total = parseFloat(els.modalTotal.dataset.value);
        const cash = parseFloat(els.cashReceived.value) || 0;
        const change = cash - total;

        if (paymentMethod === 'cash' && cash < total) {
            Swal.fire('Error', 'Insufficient cash received.', 'error');
            return;
        }

        els.btnConfirmPay.disabled = true;
        els.btnConfirmPay.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';

        const payload = {
            user_id: <?= session()->get('user_id') ?? 1 ?>,
            customer_id: els.customerSelect.value || null,
            items: state.cart.map(i => ({ product_id: i.id, quantity: i.quantity })),
            payment_method: paymentMethod,
            cash_received: paymentMethod === 'cash' ? cash : total, // For QRIS, assume exact payment
            change_amount: paymentMethod === 'cash' ? change : 0
        };

        try {
            const response = await fetch('/api/pos/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.ok) {
                els.paymentModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful!',
                    text: `Invoice: ${result.data.transaction.invoice_number}`,
                    showConfirmButton: true,
                    confirmButtonText: 'New Order'
                }).then(() => {
                    // Reset
                    state.cart = [];
                    updateCartUI();
                    loadProducts(); // Refresh stock
                    els.customerSelect.value = '';
                    els.customerPointsBadge.classList.add('d-none');
                });
            } else {
                throw new Error(result.message || JSON.stringify(result.messages));
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Checkout Failed', error.message, 'error');
        } finally {
            els.btnConfirmPay.disabled = false;
            els.btnConfirmPay.innerHTML = '<i class="fas fa-print me-2"></i> Print Receipt & Finish';
        }
    }

    // Start
    init();
</script>
<?= $this->endSection() ?>