<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pengumuman</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        table {
            width: 100%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        th {
            background-color: #2392EC;
            color: white;
            text-align: center;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2392EC;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #4CAF50;
        }

        h3 {
            margin-top: 20px;
            margin-bottom: 10px;
            text-align:center;
        }

        /* Media Queries */
        @media screen and (max-width: 600px) {
            table {
                margin: 10px auto;
            }
            th, td {
                padding: 10px;
            }
            a {
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div style="background-color: white; text-align: center;">
        <img src="http://if.itera.ac.id/wp-content/uploads/2021/11/cropped-Log%E3%81%8A.png" alt="Logo">
    </div>
    <div style="background-color: white; padding: 30px;">
        <p>Hi <strong>{{ $nama }}</strong>,</p>
        <p class="text-justify">Saat ini anda memiliki beberapa dokumen yang pengumpulannya
            @if(count($before)!=0 && count($after)!=0)
              <strong>mendekati</strong> dan <strong>telah melewati</strong>
            @elseif(count($before)!=0)
              <strong>mendekati</strong>
            @elseif(count($after)!=0)
              <strong>telah melewati</strong>
            @endif
            tenggat waktu. Harap segera mengumpulkan dokumen agar anda
            @if(count($before)!=0 && count($after)!=0)
              <strong>tidak mendapatkan</strong> atau <strong>mengurangi</strong>
            @elseif(count($before)!=0)
              <strong>tidak mendapatkan</strong>
            @elseif(count($after)!=0)
              <strong>mendapatkan pengurangan</strong>
            @endif
            hukuman.
          </p>
        @if(count($before)!=0)
        <h3>Dokumen Mendekati Tenggat Waktu</h3>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kelas</th>
                    <th>Dokumen</th>
                    <th>Tenggat Waktu</th>
                    <th>Waktu Tersisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($before as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item["nama_kelas"] }}</td>
                    <td>{{ $item["nama_dokumen"] }}</td>
                    <td>{{ $item["tenggat_waktu"] }}</td>
                    <td class="text-center">{{ $item["waktu_tersisa"]. " hari"}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @if(count($after)!=0)
        <h3>Dokumen Melewati Tenggat Waktu</h3>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kelas</th>
                    <th>Dokumen</th>
                    <th>Tenggat Waktu</th>
                    <th>Waktu Terlewat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($after as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item["nama_kelas"] }}</td>
                    <td>{{ $item["nama_dokumen"] }}</td>
                    <td>{{ $item["tenggat_waktu"] }}</td>
                    <td class="text-center">{{ $item["waktu_terlewat"]. " hari" }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <p class="text-justify" style="margin-top: 15px;">Silakan klik tombol di bawah ini untuk melakukan pengumpulan dokumen </p>
        <a href="{{ url("/kelas-diampu") }}">Lihat Pengumpulan</a>
        <p style="margin-top: 15px;">Koordinator Prodi Teknik Informatika</p>
        <br>
        <h4><strong>Andika Setiawan, S.Kom., M.Cs.</strong></h4>
    </div>
</body>
</html>
