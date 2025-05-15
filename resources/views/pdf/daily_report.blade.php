<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 20px;
            text-align: center;
            width: 400px;
            max-width: 400px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        .qr-code {
            margin: 30px 0;
        }

        .qr-code img {
            width: 200px;
            height: 200px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>STO PERIODE {{ strtoupper($report->issued_date->format('F Y')) }}</h2>

    <div class="qr-code">
        <img src="{{ $qrCodeBase64 }}" alt="QR Code">
        <p>{{ $report->inventory->id }}</p>
    </div>

    <table>
        <tbody>
            <tr><td><b>NUMBER</b></td><td>:</td><td>{{ $report->id }}</td></tr>
            <tr><td><b>PLANT LOKASI</b></td><td>:</td><td>{{ $report->inventory->part->plant->name ?? '-' }}</td></tr>
            <tr><td><b>STO PERIODE</b></td><td>:</td><td>{{ $report->created_at->format('F Y') }}</td></tr>
            <tr><td><b>DateTime</b></td><td>:</td><td>{{ $report->created_at->format('d/m/Y H:i:s') }}</td></tr>
            <tr><td><b>INV ID</b></td><td>:</td><td>{{ $report->inventory->part->Inv_id ?? '-' }}</td></tr>
            <tr><td><b>PART NAME</b></td><td>:</td><td>{{ $report->inventory->part->Part_name ?? '-' }}</td></tr>
            <tr><td><b>PART NO</b></td><td>:</td><td>{{ $report->inventory->part->Part_number ?? '-' }}</td></tr>
            <tr><td><b>MASTER TYPE</b></td><td>:</td><td>{{ $report->inventory->part->category->name ?? '-' }}</td></tr>
            <tr><td><b>STATUS</b></td><td>:</td><td>{{ $report->status }}</td></tr>
            <tr><td><b>DETAIL LOKASI</b></td><td>:</td><td>{{ $report->inventory->part->area->nama_area ?? '-' }}</td></tr>
            <tr><td><b>PIC</b></td><td>:</td><td>{{ $report->user->username ?? '-' }}</td></tr>

            <tr>
                <td class="text-center"><b>STOCK PLAN</b></td>
                <td></td>
                <td class="text-center"><b>STO ACTUAL</b></td>
            </tr>
            <tr>
                <td><h1>{{ $report->inventory->plan_stock }}</h1></td>
                <td></td>
                <td><h1>{{ $report->inventory->act_stock }}</h1></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
