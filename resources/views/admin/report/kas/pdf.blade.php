
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas Kendaraan</title>
<style>
    body {
        font-family: sans-serif;
        font-size: 10pt;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #333;
        padding: 6px;
        text-align: center;      
        vertical-align: middle;   
    }
    th {
        background-color: #eee;
    }
    td small {
        display: block;
        margin-top: 4px;
        font-size: 8pt;
    }
    .td-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

</style>

</head>
<body>
    {{-- <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 2px solid #000; padding-bottom: 10px;">
        
        <img src="{{ public_path($company->image) }}" alt="Logo" style="height: 60px; width: auto; position: relative;">
        <div style="position: relative; flex-direction: column; justify-content: center; text-align: right; line-height: 1.2; margin-top:-100px">
            <div style="font-size: 14pt; font-weight: bold; margin: 0;">{{ $company->name }}</div>
            <div style="font-size: 10pt; margin: 0;">{{ $company->web }}</div>
        </div>
</div> --}}


<h3 style="text-align:center;">Laporan Keuangan Kendaraan Rental</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Sumber</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th style="text-align:right;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kas as $k)
            @php
                $source = class_basename($k->payable_type);
                $sourceLabel = match ($source) {
                    'VehicleRepairRealiz' => 'Perbaikan Kendaraan',
                    'RentCar' => 'Sewa Kendaraan',
                    default => 'Lainnya',
                };
            @endphp
            <tr>
                <td style="text-align:center;">
                    <div class="td-wrapper">
                        {{ $loop->iteration }}
                    </div>
                </td>
                <td>
                    <div class="td-wrapper">
                        {{ $sourceLabel }}<br>
                        @if ($k->payable && in_array($source, ['VehicleRepairRealiz', 'RentCar']) && $k->payable->vehicle)
                            <small>Kendaraan: {{ $k->payable->vehicle->name ?? '-' }}</small>
                        @endif
                    </div>
                </td>
                <td data-label="Tanggal">
                    <div class="td-wrapper">
                        @if ($k->payable && method_exists($k->payable, 'getReportDateAttribute'))
                            {{ \Carbon\Carbon::parse($k->payable->report_date)->format('d-m-Y') }}
                        @else
                            -
                        @endif
                    </div>
                </td>
                <td style="text-align:center;">
                    <div class="td-wrapper">
                        {{ $k->type == 1 ? 'Masuk' : 'Keluar' }}
                    </div>
                </td>
                <td style="text-align:right;">
                    <div class="td-wrapper">
                    Rp{{ number_format($k->amount, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" style="text-align:right;">Total Pemasukan</th>
            <th style="text-align:right;">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align:right;">Total Pengeluaran</th>
            <th style="text-align:right;">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align:right;">Total Keseluruhan</th>
            <th style="text-align:right;">Rp{{ number_format($totalSemua, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

</body>
</html>
