<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<title>Laporan Pengumpulan Dokumen</title>
	<style>
		.table-utama, span {
			width: 100%;
			border-collapse: collapse;
			font-size: 10px;
			page-break-inside: auto; /* Setelah tabel dicetak, laman baru akan dimulai */
		}

		th, tbody tr td {
			padding: 5px;
			border: 1px solid #000;
			text-align: center;
			word-wrap: break-word; /* Memaksa teks yang panjang agar pindah ke baris baru */
			max-width: 100px; /* Membatasi lebar maksimum sel */
		}

		th {
			background-color: #ccc;
		}

		tbody tr:nth-child(even) {
			background-color: #f2f2f2;
		}

    .ceklis {
      color: green;
    }

    .silang {
      color: red;
    }

    h3 {
      text-align: center;
    }

    .text-start {
      text-align: left;
    }

    .text-center {
      text-align: center;
    }

    .text-end {
      text-align: right;
    }

    .text-success {
      color: green;
    }

    .text-danger {
      color: red;
    }

    .text-primary {
      color: blue;
    }

    .text-warning {
      color: orange;
    }

    .text-info {
      color: teal;
    }

    .table-success {
      background-color: green;
    }

    .table-danger {
      background-color: red;
    }

    .table-primary {
      background-color: blue;
    }

    .table-warning {
      background-color: yellow;
    }

    .table-info {
      background-color: grey;
    }

    .foot1 {
      width: 400px;
    }
    .foot2 {
      width: 100px;
    }

    pre {
      display: inline;
      font-family: inherit;
      font-size: inherit;
      margin: 0;
      padding: 0;
      white-space: pre-wrap;
    }


	</style>

</head>
<body>
	<h3>
    Laporan Pengumpulan Dokumen Perkuliahan <br />
    TA : {{ $tahun_ajaran->tahun_ajaran }}
  </h3>
  <p class="text-center">Dicetak : {{ \Carbon\Carbon::now()->locale('id')->isoFormat('LLLL') }}</p>
	<table class="table-utama">
		<thead>
			<tr>
				<th>Mata Kuliah</th>
        <th>Kelas</th>
        @foreach ($dokumen as $item)
        <th>{{ $item->nama_dokumen }}</th>    
        @endforeach
			</tr>
		</thead>
		<tbody>
      @foreach ($report->kelas as $item)
      <tr class="data">
        <th>{{ $item->nama_matkul }}</th>
        <th>{{ $item->nama_kelas }}</th>
        @foreach ($item->dokumen as $dokumen)
        <td class="@if($dokumen->status == 0) {{ 'text-danger' }} @elseif($dokumen->status == 1) {{ $dokumen->is_late == 1 ? 'text-warning' : 'text-success' }} @endif" style="font-size: 25px;">@if($dokumen->status == 0) &#x2717; @elseif($dokumen->status == 1) &#x2713; @elseif($dokumen->status == 2) <span style="font-size: 10px;">TD</span> @endif</td>
        {{-- <td><span class="ceklis"> (Hijau)</span></i></td> --}}
        @endforeach
      </tr>
      @endforeach
		</tbody>
	</table>

  <table>
    <tfoot>
      <tr class="table-success">
        <td class="text-start foot1"><strong>Total Terkumpul:</strong></td>
        <td class="text-end foot2">{{ $report->total_dikumpul }}</td>
      </tr>
      <tr class="table-danger">
        <td class="text-start foot1"><strong>Total Belum Dikumpul:</strong></td>
        <td class="text-end foot2">{{ $report->total_belum_dikumpul }}</td>
      </tr>
      <tr class="table-primary">
        <td class="text-start foot1"><strong>Total Tepat Waktu:</strong></td>
        <td class="text-end foot2"><strong>{{ $report->total_tepat_waktu }}</strong></td>
      </tr>
      <tr class="table-warning">
        <td class="text-start foot1"><strong>Total Terlambat:</strong></td>
        <td class="text-end foot2"><strong>{{ $report->total_terlambat }}</strong></td>
      </tr>
      <tr class="table-info">
        <td class="text-start foot1"><strong>Total Ditugaskan:</strong></td>
        <td class="text-end foot2"><strong>{{ $report->total_ditugaskan}}</strong></td>
      </tr>
    </tfoot>
  </table>
  
</body>
</html>
