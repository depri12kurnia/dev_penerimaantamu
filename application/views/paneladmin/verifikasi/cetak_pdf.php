<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Reservasi Kunjungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 3px;
            vertical-align: top;
        }

        .label-col {
            width: 30%;
            font-weight: bold;
        }

        .separator {
            width: 2%;
            text-align: center;
        }

        .value-col {
            width: 68%;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="<?php echo base_url(); ?>public/settings/logo/logo.png" alt="Logo" width="280">
        <h2>TANDA BUKTI PERSETUJUAN KUNJUNGAN</h2>
        <p>Nomor Tiket: <strong><?= $row->no_ticket; ?></strong></p>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <span class="status-badge">✔ APPROVED</span>
    </div>

    <p>Berdasarkan permohonan kunjungan yang telah diajukan, dengan ini kami sampaikan bahwa permohonan dari:</p>

    <table>
        <tr>
            <td class="label-col">Nama Pemohon</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->nama_pemohon; ?></td>
        </tr>
        <tr>
            <td class="label-col">Asal Tamu</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->asal_tamu; ?></td>
        </tr>
        <tr>
            <td class="label-col">Nama Instansi</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->nama_instansi; ?></td>
        </tr>
        <tr>
            <td class="label-col">Nama Pimpinan</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->nama_pimpinan; ?></td>
        </tr>
        <tr>
            <td class="label-col">Jumlah Peserta</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->jumlah_peserta; ?> Orang</td>
        </tr>
        <tr>
            <td class="label-col">Topik Kunjungan</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->topik; ?></td>
        </tr>
    </table>

    <p>Telah <strong>DISETUJUI</strong> untuk melaksanakan kunjungan pada:</p>

    <table>
        <tr>
            <td class="label-col">Tanggal Berkunjung</td>
            <td class="separator">:</td>
            <td class="value-col"><?= date('d-m-Y', strtotime($row->tanggal_berkunjung)); ?></td>
        </tr>
        <tr>
            <td class="label-col">Jam Kunjungan</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->jam_kunjungan; ?> WIB</td>
        </tr>
        <tr>
            <td class="label-col">Lokasi Tujuan</td>
            <td class="separator">:</td>
            <td class="value-col"><?= $row->lokasi; ?></td>
        </tr>
    </table>

    <p style="margin-top: 30px;">Dokumen ini merupakan tanda bukti resmi persetujuan kunjungan. Harap dicetak sebagai bukti kunjungan dari pemohon.</p>

    <div class="footer">
        <p>Disetujui pada tanggal: <?= date('d F Y', strtotime($row->updated_at)); ?></p>
        <br><br><br>
        <p><strong>Humas Poltekkes Jakarta III</strong></p>
    </div>

</body>

</html>