<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Perbaikan Kendaraan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.5;
        }
        h2, h4 {
            margin: 0;
            padding-bottom: 4px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #000;
            vertical-align: top;
        }
        .no-border td {
            border: none;
            padding: 2px 4px;
        }
        .section {
            margin-top: 20px;
        }
        .label-col {
            width: 35%;
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .img-wrapper {
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .img-wrapper img {
            width: 100%;
            max-height: 350px;
            object-fit: contain;
            border: 1px solid #000;
        }
        .header-company {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .company-info {
            text-align: right;
        }
        .company-info h3 {
            margin: 0;
        }
        .company-info small {
            color: #555;
        }
    </style>
</head>
<body>

    {{-- Header Perusahaan --}}
    <div class="header-company">
        @if($company->image && file_exists(public_path('storage/' . $company->image)))
            <img src="{{ public_path('storage/' . $company->image) }}" class="logo" alt="Logo">
        @endif
        <div class="company-info">
            <h3>{{ $company->name }}</h3>
            @if($company->web)
                <small>Website: {{ $company->web }}</small><br>
            @endif
            <small>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</small>
        </div>
    </div>

    <h2>INVOICE PERBAIKAN KENDARAAN</h2>
    <p style="text-align: center; margin-top: -8px; font-size: 13px;">
        No. Dokumen: INV/PK/{{ now()->format('Ym') }}/{{ $vehicleRepairReal->vehicleRepId }}
    </p>
    <p style="text-align: center; margin-top: -4px; font-size: 12px;">
        Bukti Pengeluaran untuk Perbaikan Unit: {{ $vehicleRepairReal->vehicleRepair->vehicle->name }}
    </p>


    <div class="section">
        <h4>1. Informasi Pengajuan</h4>
        <table>
            <tbody>
                <tr>
                    <td class="label-col">Tanggal Pengajuan</td>
                    <td>{{ \Carbon\Carbon::parse($vehicleRepairReal->vehicleRepair->submission_date)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="label-col">Kendaraan</td>
                    <td>{{ $vehicleRepairReal->vehicleRepair->vehicle->name }} - {{ $vehicleRepairReal->vehicleRepair->vehicle->plate_number }}</td>
                </tr>
                <tr>
                    <td class="label-col">Diajukan Oleh</td>
                    <td>{{ $vehicleRepairReal->vehicleRepair->user?->admin->name ?? $vehicleRepairReal->vehicleRepair->user?->supervisor?->name ?? $vehicleRepairReal->vehicleRepair->user?->employee?->name ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="section">
        <h4>2. Detail Nota Perbaikan</h4>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th>Deskripsi</th>
                    <th style="width: 35%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Tanggal Selesai Perbaikan</td>
                    <td>{{ \Carbon\Carbon::parse($vehicleRepairReal->completeDate ?? now())->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Catatan</td>
                    <td>{{ $vehicleRepairReal->notes ?? '-' }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Biaya Perbaikan:</strong></td>
                    <td><strong>Rp {{ number_format($vehicleRepairReal->paymentAmount->first()->amount ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>


    @if($vehicleRepairReal->photo->count())
        <div class="section">
            <h4>3. Bukti Pembayaran</h4>
            @foreach ($vehicleRepairReal->photo as $photo)
                <div class="img-wrapper">
                    <img src="{{ public_path('storage/' . $photo->path) }}" alt="Bukti Pembayaran">
                </div>
            @endforeach
        </div>
    @endif

</body>
</html>
