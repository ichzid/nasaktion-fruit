<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= html_escape($transaction->invoice_no) ?></title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #1f2937;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        .toolbar {
            max-width: 860px;
            margin: 24px auto 0;
            text-align: right;
        }

        .btn-print {
            display: inline-block;
            padding: 10px 18px;
            border: 0;
            border-radius: 10px;
            background: #15803d;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .invoice {
            max-width: 860px;
            margin: 16px auto 40px;
            padding: 40px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .header,
        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            margin: 0 0 6px;
            color: #15803d;
            font-size: 28px;
            font-weight: 800;
        }

        .muted {
            color: #6b7280;
        }

        .title {
            margin: 0 0 8px;
            font-size: 26px;
            font-weight: 800;
            text-align: right;
        }

        .right {
            text-align: right;
        }

        .meta {
            margin-top: 32px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .card {
            padding: 18px 20px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fafafa;
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 15px;
        }

        .card p {
            margin: 6px 0;
            white-space: pre-line;
        }

        table {
            width: 100%;
            margin-top: 28px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 320px;
            max-width: 100%;
            margin: 28px 0 0 auto;
        }

        .summary-row {
            padding: 8px 0;
        }

        .summary-total {
            margin-top: 4px;
            padding-top: 12px;
            border-top: 2px solid #1f2937;
            font-size: 18px;
            font-weight: 800;
        }

        .footer {
            margin-top: 44px;
            padding-top: 18px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        @media print {
            @page {
                size: A4;
                margin: 14mm;
            }

            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .invoice {
                max-width: none;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }

        @media (max-width: 768px) {
            .invoice {
                padding: 24px;
            }

            .header,
            .meta {
                display: block;
            }

            .right,
            .title {
                text-align: left;
            }

            .card + .card {
                margin-top: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <main class="invoice">
        <div class="header">
            <div>
                <h1 class="brand">Nasaktion Fruit</h1>
                <div class="muted">Fresh Fruit Store</div>
                <div class="muted">Kisaran, Sumatera Utara</div>
            </div>
            <div class="right">
                <h2 class="title">Invoice</h2>
                <div><strong><?= html_escape($transaction->invoice_no) ?></strong></div>
                <div class="muted"><?= date('d/m/Y H:i', strtotime($transaction->tgl)) ?> WIB</div>
            </div>
        </div>

        <div class="meta">
            <div class="card">
                <h2>Tagihan Untuk</h2>
                <p><strong><?= html_escape($transaction->nama ?: '-') ?></strong></p>
                <p>No. HP: <?= html_escape($transaction->no_hp ?: '-') ?></p>
                <p><?= html_escape($transaction->alamat_pengiriman ?: 'Alamat tidak tersedia') ?></p>
            </div>
            <div class="card">
                <h2>Informasi Pesanan</h2>
                <p>Jenis Pesanan: <?= html_escape($transaction->jenis_order ?: '-') ?></p>
                <p>Status: <?= html_escape(ucfirst($transaction->status ?: '-')) ?></p>
                <?php if (!empty($transaction->catatan)): ?>
                <p>Catatan: <?= html_escape($transaction->catatan) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= html_escape($item->nama_buah) ?></td>
                    <td class="text-right"><?= (int) $item->qty ?> <?= html_escape($item->satuan) ?></td>
                    <td class="text-right">Rp <?= number_format($item->harga, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($transaction->subtotal, 0, ',', '.') ?></span>
            </div>
            <div class="summary-row">
                <span>Diskon</span>
                <span>-Rp <?= number_format($transaction->diskon_amount, 0, ',', '.') ?></span>
            </div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span>Rp <?= number_format($transaction->total, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="footer muted">
            Terima kasih telah berbelanja di Nasaktion Fruit.
        </div>
    </main>
</body>
</html>
