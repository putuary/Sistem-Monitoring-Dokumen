<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<title>Laporan Pengumpulan Dokumen</title>
	<style>
		table, span {
			width: 100%;
			border-collapse: collapse;
			font-size: 10px;
			page-break-inside: auto; /* Setelah tabel dicetak, laman baru akan dimulai */
		}

		th, td {
			padding: 5px;
			border: 1px solid #000;
			text-align: center;
			word-wrap: break-word; /* Memaksa teks yang panjang agar pindah ke baris baru */
			max-width: 100px; /* Membatasi lebar maksimum sel */
		}

		th {
			background-color: #ccc;
		}

		tr:nth-child(even) {
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

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .label {
      width: 45%;
      font-weight: bold;
    }

    .value {
      width: 55%;
    }


    .report-row {
      display: flex;
    }

    .report-col {
      flex: 3;
    }

    .report-col-label {
      text-align: left;
    }

    .report-col-value {
      text-align: left;
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
  <p style="text-align: center">Dicetak : {{ \Carbon\Carbon::now()->locale('id')->isoFormat('LLLL') }}</p>
	<table>
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
      <tr>
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

  <div class="row" style="margin-top: 20px;">
    <div class="label"><span class="text-success">Total Terkumpul</span></div>
    <div class="value"><span class="text-success">{{ $report->total_dikumpul }}</span></div>
  </div>
  <div class="row">
    <div class="label"><span class="text-danger">Total Belum Dikumpul</span></div>
    <div class="value"><span class="text-danger">{{ $report->total_belum_dikumpul }}</span></div>
  </div>
  <div class="row">
    <div class="label"><span class="text-primary">Total Tepat Waktu</span></div>
    <div class="value"><span class="text-primary">{{ $report->total_tepat_waktu }}</span></div>
  </div>
  <div class="row">
    <div class="label"><span class="text-warning">Total Terlambat</span></div>
    <div class="value"><span class="text-warning">{{ $report->total_terlambat }}</span></div>
  </div>
  <div class="row">
    <div class="label"><span class="text-info">Total Ditugaskan</span></div>
    <div class="value"><span class="text-info">{{ $report->total_ditugaskan}}</span></div>
  </div>
</body>
</html>
