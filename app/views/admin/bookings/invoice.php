<!DOCTYPE html>
<html>
<head>
    <title>Invoice <?= $data['booking']->booking_code ?></title>
    <style>
        body { font-family: sans-serif; color: #333; padding: 40px; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; pb-20px; margin-bottom: 20px;}
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .total { text-align: right; margin-top: 20px; font-size: 20px; font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Sekarang</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>BILLE BILLIARDS</h1>
                <p>Citra Raya, Tangerang</p>
            </div>
            <div style="text-align: right;">
                <h2>INVOICE</h2>
                <p>#<?= $data['booking']->booking_code ?></p>
                <p><?= date('d/m/Y H:i', strtotime($data['booking']->created_at)) ?></p>
            </div>
        </div>

        <p><strong>Pelanggan:</strong> <?= $data['booking']->customer_name ?></p>
        <p><strong>Status:</strong> LUNAS</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jadwal</th>
                    <th>Durasi</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Meja #<?= $data['booking']->table_number ?></td>
                    <td><?= date('H:i', strtotime($data['booking']->start_time)) ?> - <?= date('H:i', strtotime($data['booking']->end_time)) ?></td>
                    <td><?= $data['booking']->duration ?> Jam</td>
                    <td>Rp <?= number_format($data['booking']->total_price, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            TOTAL: Rp <?= number_format($data['booking']->total_price, 0, ',', '.') ?>
        </div>

        <div style="margin-top: 50px; text-align: center; color: #888; font-size: 12px;">
            Terima kasih telah bermain di Bille Billiards!
        </div>
    </div>
</body>
</html>