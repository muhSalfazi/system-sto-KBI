    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                /* kecilkan font */
                text-align: center;
                width: 100%;
                margin: 0 auto;
            }

            th,
            td {
                border: 1px solid black;
                padding: 4px;
                text-align: left;
            }

            .qr-code img {
                width: 100px;
                height: 100px;
            }
        </style>
    </head>

    <body>

        <h2>
            STO PERIODE
            {{ $report->created_at ? strtoupper($report->created_at->format('F Y')) : '-' }}
        </h2>


        <div class="qr-code">
            <img src="{{ $qrCodeBase64 }}" alt="QR Code">
            <p>{{ $report->inventory->part->Inv_id }}</p>
        </div>

        <table>
            <tbody>
                <tr>
                    <td><b>NUMBER</b></td>
                    <td>:</td>
                    <td>{{ $report->id }}</td>
                </tr>
                <tr>
                    <td><b>PLANT LOKASI</b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->plant->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>STO PERIODE</b></td>
                    <td>:</td>
                    <td>{{ $report->created_at->format('F Y') }}</td>
                </tr>
                <tr>
                    <td><b>DateTime</b></td>
                    <td>:</td>
                    <td>{{ $report->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td><b>INV ID</b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->Inv_id ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>PART NAME</b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->Part_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>PART NO</b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->Part_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>CUSTOMER</b></b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->customer->username ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>MASTER TYPE</b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->category->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>STATUS</b></td>
                    <td>:</td>
                    <td>{{ $report->status }}</td>
                </tr>
                <tr>
                    <td><b>DETAIL LOKASI</b></td>
                    <td>:</td>
                    <td>{{ $report->inventory->part->area->nama_area ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>PIC</b></td>
                    <td>:</td>
                    <td>{{ $report->user->username ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="text-center"><b>STOCK PLAN</b></td>
                    <td></td>
                    <td class="text-center"><b>STO ACTUAL</b></td>
                </tr>
                <tr>
                    <td>
                        <h1>{{ $report->inventory->plan_stock }}</h1>
                    </td>
                    <td></td>
                    <td>
                        <h1>{{ $report->inventory->act_stock }}</h1>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>

    </html>
