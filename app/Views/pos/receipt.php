<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?= $transaction->invoice_number ?></title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            body {
                margin: 0;
                padding: 10px;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 300px;
            /* Viewport for screen */
            margin: 0 auto;
            padding: 10px;
            background: #fff;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .border-bottom {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .border-top {
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            vertical-align: top;
        }

        .qty {
            width: 30px;
        }

        .price {
            text-align: right;
            white-space: nowrap;
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #333;
            color: #fff;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>

<body>
    <div class="text-center mb-2">
        <h3 style="margin: 0;">CRM KASIR</h3>
        <div>Jl. Jendral Sudirman No. 123</div>
        <div>Jakarta Selatan</div>
    </div>

    <div class="border-bottom">
        <div>Date: <?= $transaction->created_at->format('d/m/Y H:i') ?></div>
        <div>Inv: <?= $transaction->invoice_number ?></div>
        <div>Cashier: <?= esc($cashier->name ?? '-') ?></div>
        <?php if ($customer): ?>
            <div>Cust: <?= esc($customer->name) ?></div>
        <?php endif; ?>
    </div>

    <table class="mb-2">
        <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="3"><?= esc($item['name']) ?></td>
            </tr>
            <tr>
                <td class="qty"><?= $item['quantity'] ?>x</td>
                <td class="text-end">@<?= number_format($item['unit_price'], 0, ',', '.') ?></td>
                <td class="price"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="border-top">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="price"><?= number_format($transaction->subtotal, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Tax (11%)</td>
                <td class="price"><?= number_format($transaction->tax, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="fw-bold" style="font-size: 14px;">TOTAL</td>
                <td class="price fw-bold" style="font-size: 14px;">
                    <?= number_format($transaction->total_amount, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="border-top mb-2">
        <table>
            <tr>
                <td>Payment</td>
                <td class="price"><?= strtoupper($transaction->payment_method) ?></td>
            </tr>
            <?php if ($transaction->payment_method === 'cash'): ?>
                <tr>
                    <td>Cash</td>
                    <td class="price"><?= number_format($transaction->cash_received, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Change</td>
                    <td class="price"><?= number_format($transaction->change_amount, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($transaction->points_earned > 0): ?>
                <tr>
                    <td>Points</td>
                    <td class="price">+<?= $transaction->points_earned ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        <div>Thank You</div>
        <div>Please Come Again</div>
    </div>

    <button onclick="window.print()" class="btn-print no-print">PRINT RECEIPT</button>

    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        }
    </script>
</body>

</html>