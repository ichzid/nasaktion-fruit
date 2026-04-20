<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - <?= $transaction->invoice_no ?></title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 15px; font-family: monospace; font-size: 12px; color: #000; background: #fff; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .my-2 { margin: 10px 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-3 { margin-bottom: 15px; }
        .border-dashed { border-top: 1px dashed #000; margin: 10px 0; }
        .flex { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; }
        .text-sm { font-size: 11px; }
        .text-xs { font-size: 10px; }
        .print-btn { display: block; width: 100%; padding: 10px; background: #4f46e5; color: white; border: none; cursor: pointer; font-weight: bold; margin-bottom: 15px; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <button class="no-print print-btn" onclick="window.print()">Cetak Penuh</button>

    <div class="text-center mb-3">
        <h3 style="margin:0; font-size:16px;">Nasaktion Fruit</h3>
        <p style="margin:2px 0;" class="text-sm">Struk Pembayaran</p>
        <p style="margin:0;" class="text-xs"><?= $transaction->invoice_no ?></p>
        <p style="margin:2px 0;" class="text-xs"><?= date('d M Y H:i', strtotime($transaction->tgl)) ?></p>
    </div>

    <div class="border-dashed"></div>

    <!-- Items -->
    <table class="text-sm">
        <?php foreach($items as $i): ?>
        <tr>
            <td colspan="2"><?= $i->nama_buah ?></td>
        </tr>
        <tr>
            <td style="padding-bottom:3px;">× <?= $i->qty ?></td>
            <td class="text-right" style="padding-bottom:3px;">Rp <?= number_format($i->subtotal, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="border-dashed"></div>

    <!-- Totals -->
    <div class="flex text-sm mb-1">
        <span>Subtotal</span>
        <span>Rp <?= number_format($transaction->subtotal, 0, ',', '.') ?></span>
    </div>

    <?php if($transaction->diskon_amount > 0): ?>
    <div class="flex text-sm mb-1">
        <span>Diskon</span>
        <span>- Rp <?= number_format($transaction->diskon_amount, 0, ',', '.') ?></span>
    </div>
    <?php endif; ?>

    <div class="flex font-bold" style="font-size:14px; margin-top:5px;">
        <span>TOTAL</span>
        <span>Rp <?= number_format($transaction->total, 0, ',', '.') ?></span>
    </div>

    <div class="border-dashed"></div>

    <p class="text-center text-xs" style="margin-top:15px;">Terima kasih telah berbelanja! 🙏</p>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>
