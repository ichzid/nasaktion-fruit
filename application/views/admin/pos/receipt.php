<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - <?= html_escape($transaction->invoice_no) ?></title>
    <style>
        :root {
            --paper-width: 80mm;
            --ink: #111;
            --muted: #555;
        }

        * { box-sizing: border-box; }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #eef0f3;
            color: var(--ink);
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            line-height: 1.35;
        }

        .toolbar {
            width: var(--paper-width);
            margin: 18px auto 10px;
            display: flex;
            gap: 8px;
            font-family: Arial, sans-serif;
        }

        .toolbar button {
            flex: 1;
            border: 0;
            border-radius: 7px;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-print { background: #15803d; color: #fff; }
        .btn-close { background: #fff; color: #333; border: 1px solid #d1d5db !important; }

        .receipt {
            width: var(--paper-width);
            min-height: 120mm;
            margin: 0 auto 24px;
            padding: 6mm 5mm 8mm;
            background: #fff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .14);
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .muted { color: var(--muted); }
        .nowrap { white-space: nowrap; }

        .brand {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 19px;
            font-weight: 900;
            letter-spacing: -.4px;
            text-transform: uppercase;
        }

        .tagline {
            margin: 2px 0 0;
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .store-info {
            margin: 8px 0 0;
            font-size: 9px;
            line-height: 1.45;
        }

        .separator {
            margin: 10px 0;
            overflow: hidden;
            white-space: nowrap;
            letter-spacing: .3px;
        }

        .separator::before { content: "------------------------------------------"; }
        .separator.double::before { content: "=========================================="; }

        .meta { width: 100%; border-collapse: collapse; }
        .meta td { padding: 1px 0; vertical-align: top; }
        .meta td:first-child { width: 24mm; color: var(--muted); }

        .items { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items td { padding: 1px 0; vertical-align: top; }
        .items .name { padding-top: 4px; font-weight: 700; word-break: break-word; }
        .items .detail { width: 60%; color: var(--muted); padding-bottom: 3px; }
        .items .amount { width: 40%; text-align: right; padding-bottom: 3px; white-space: nowrap; }

        .summary { width: 100%; border-collapse: collapse; }
        .summary td { padding: 2px 0; }
        .summary td:last-child { text-align: right; white-space: nowrap; }
        .summary .grand-total td {
            padding-top: 5px;
            font-size: 14px;
            font-weight: 900;
        }

        .payment-badge {
            display: inline-block;
            margin-top: 5px;
            padding: 2px 7px;
            border: 1px solid #111;
            border-radius: 2px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .footer-message { margin: 0; font-weight: 700; }
        .footer-note { margin: 5px 0 0; font-size: 9px; color: var(--muted); }
        .receipt-code { margin: 10px 0 0; font-size: 9px; letter-spacing: 1px; }

        @media print {
            html, body { width: 80mm; background: #fff; }
            .no-print { display: none !important; }
            .receipt {
                width: 80mm;
                min-height: 0;
                margin: 0;
                padding: 4mm 4mm 6mm;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <button type="button" class="btn-close" onclick="window.close()">Tutup</button>
        <button type="button" class="btn-print" onclick="window.print()">Cetak Struk</button>
    </div>

    <main class="receipt">
        <header class="center">
            <h1 class="brand">Nasaktion Fruit</h1>
            <p class="tagline">Fresh Fruit Store</p>
            <p class="store-info">
                Kisaran, Sumatera Utara<br>
                Terima kasih telah berbelanja
            </p>
        </header>

        <div class="separator double"></div>

        <table class="meta">
            <tr>
                <td>No. Struk</td>
                <td>: <span class="bold"><?= html_escape($transaction->invoice_no) ?></span></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?= date('d/m/Y H:i:s', strtotime($transaction->tgl)) ?> WIB</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: <?= html_escape(!empty($transaction->nama_lengkap) ? $transaction->nama_lengkap : '-') ?></td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>: <?= html_escape(!empty($transaction->nama) ? $transaction->nama : 'Umum') ?></td>
            </tr>
        </table>

        <div class="separator"></div>

        <table class="items">
            <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="2" class="name"><?= html_escape($item->nama_buah) ?></td>
            </tr>
            <tr>
                <td class="detail">
                    <?= number_format($item->qty, 0, ',', '.') ?> <?= html_escape(!empty($item->satuan) ? $item->satuan : 'pcs') ?>
                    × <?= number_format($item->harga, 0, ',', '.') ?>
                </td>
                <td class="amount">Rp<?= number_format($item->subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="separator"></div>

        <table class="summary">
            <tr>
                <td>Subtotal</td>
                <td>Rp<?= number_format($transaction->subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php if ($transaction->diskon_amount > 0): ?>
            <tr>
                <td>Diskon</td>
                <td>-Rp<?= number_format($transaction->diskon_amount, 0, ',', '.') ?></td>
            </tr>
            <?php endif; ?>
            <tr class="grand-total">
                <td>TOTAL</td>
                <td>Rp<?= number_format($transaction->total, 0, ',', '.') ?></td>
            </tr>
        </table>

        <div class="center">
            <span class="payment-badge">
                <?= strtolower($transaction->jenis_order) === 'offline' ? 'Pembayaran Kasir' : 'Pembayaran Online' ?>
            </span>
        </div>

        <div class="separator double"></div>

        <footer class="center">
            <p class="footer-message">TERIMA KASIH</p>
            <p class="footer-note">
                Barang yang sudah dibeli tidak dapat<br>
                ditukar atau dikembalikan.
            </p>
            <p class="receipt-code"><?= html_escape($transaction->invoice_no) ?></p>
        </footer>
    </main>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 400);
        });
    </script>
</body>
</html>
