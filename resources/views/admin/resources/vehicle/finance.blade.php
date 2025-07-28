<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt;
    }
    th, td {
        border: 1px solid #333;
        padding: 6px;
        text-align: left;
    }
    h3, h5 {
        margin-bottom: 5px;
    }
</style>

<h3>Laporan Keuangan Kendaraan</h3>
<p><strong>Nama:</strong> {{ $vehicle->name }}<br>
<strong>Plat Nomor:</strong> {{ $vehicle->plate_number }}</p>

<h5>Total Pemasukan: Rp {{ number_format($totalIncome, 0, ',', '.') }}</h5>
<h5>Total Pengeluaran: Rp {{ number_format($totalExpense, 0, ',', '.') }}</h5>

<br>
<h5>Detail Pemasukan (Sewa)</h5>
<table>
    <thead>
        <tr>
            <th>Penyewa</th>
            <th>Periode</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rentCars as $rent)
            @foreach($rent->paymentAmount->where('type', 1)->where('status', 1) as $pay)
                <tr>
                    <td>{{ $rent->renter_name }}</td>
                    <td>{{ $rent->startDate }} s/d {{ $rent->endDate }}</td>
                    <td>Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<br>
<h5>Detail Pengeluaran (Perbaikan)</h5>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Catatan</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($repairs as $repair)
            @foreach($repair->paymentAmount->where('type', 2)->where('status', 1) as $pay)
                <tr>
                    <td>{{ $repair->completeDate }}</td>
                    <td>{{ $repair->notes }}</td>
                    <td>Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
