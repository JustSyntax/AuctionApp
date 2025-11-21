<!DOCTYPE html>
<html>
<head>
    <title>Laporan Lelang - Drive Auction</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
        }
        
        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #333; /* Garis ganda biar keren */
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a1a1a;
        }
        .header h3 {
            margin: 5px 0 0;
            font-size: 14px;
            font-weight: normal;
            color: #555;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
            font-style: italic;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
        }
        
        /* UTILS */
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        /* TOTAL ROW */
        .total-row td {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #333;
        }

        /* INFO SECTION */
        .info-section {
            margin-bottom: 15px;
            font-size: 11px;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>DRIVE AUCTION</h1>
        <h3>Laporan Resmi Hasil Lelang</h3>
        <p>
            Jalan Merdeka No. 123, Jakarta, Indonesia | www.driveauction.example.com
        </p>
    </div>

    {{-- INFO FILTER --}}
    <div class="info-section">
        <strong>Periode Cetak:</strong> {{ date('d F Y, H:i') }} <br>
        @if(request('date_start') || request('date_end'))
            <strong>Filter Tanggal:</strong> 
            {{ request('date_start') ? date('d/m/Y', strtotime(request('date_start'))) : 'Awal' }} 
            s/d 
            {{ request('date_end') ? date('d/m/Y', strtotime(request('date_end'))) : 'Sekarang' }}
            <br>
        @endif
        @if(request('id_petugas'))
            <strong>Filter Petugas ID:</strong> {{ request('id_petugas') }}
        @endif
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 7%;">ID</th>
                <th style="width: 15%;">Petugas</th>
                <th style="width: 10%;">Tgl Lelang</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 15%;">Harga Awal</th>
                <th style="width: 15%;">Pemenang</th>
                <th style="width: 15%;">Harga Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->id_lelang }}</td>
                <td>{{ $item->petugas->nama_petugas }}</td>
                <td class="text-center">{{ $item->tgl_lelang->format('d/m/Y') }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td class="text-end">Rp {{ number_format($item->barang->harga_awal, 0, ',', '.') }}</td>
                <td>
                    @if($item->pemenang)
                        {{ $item->pemenang->masyarakat->nama_lengkap }}
                    @else
                        <i style="color: #888;">-</i>
                    @endif
                </td>
                <td class="text-end">Rp {{ number_format($item->harga_akhir, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        
        {{-- FOOTER TOTAL --}}
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-end">TOTAL PENDAPATAN LELANG</td>
                <td class="text-end">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER HALAMAN --}}
    <div class="footer">
        <p>Dicetak otomatis oleh sistem Drive Auction pada {{ date('d/m/Y H:i:s') }}</p>
        <p>User Pencetak: {{ session('name') }} ({{ ucfirst(session('role')) }})</p>
    </div>

</body>
</html>