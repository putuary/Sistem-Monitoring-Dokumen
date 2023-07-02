@extends('layouts.user-base')
@section('title', 'Badge Yang Dapat Diperoleh')
@section('style')
     <!-- Stylesheets -->
     <link
     rel="stylesheet"
     href={{ URL::asset("assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css")}} />
   <link
     rel="stylesheet"
     href={{ URL::asset("assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css")}} />
   <link
     rel="stylesheet"
     href={{ URL::asset("assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css")}} />
@endsection

@section('content')
    <!-- Page Content -->

    <div class="content">
      
      <!-- pop up success upload -->
      @if (session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
          <strong>{{ session()->get('success') }}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Indikator Penilaian (Tetap)</h3>
        </div>
        <div class="block-content block-content-full">
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Indikator</th>
                <th class="text-center" >Tipe</th>
                <th class="text-center" >Poin</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center fs-sm">1.</td>
                <td class="fs-sm">Mengumpulkan dokumen tidak melewati tenggat waktu</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">Reward</span>
                </td>
                <td class="text-center fs-sm">{{ '+'.App\Models\Gamifikasi::getPointOntime() }}</td>
              </tr>

              <tr>
                <td class="text-center fs-sm">2.</td>
                <td class="fs-sm">Mengumpulkan dokumen dengan urutan pertama pada jenis dokumen tersebut</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">Reward</span>
                </td>
                <td class="text-center fs-sm">{{ '+'.App\Models\Gamifikasi::getPointBonus1() }}</td>
              </tr>

              <tr>
                <td class="text-center fs-sm">3.</td>
                <td class="fs-sm">Mengumpulkan dokumen dengan urutan kedua pada jenis dokumen tersebut</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">Reward</span>
                </td>
                <td class="text-center fs-sm">{{ '+'.App\Models\Gamifikasi::getPointBonus2() }}</td>
              </tr>

              <tr>
                <td class="text-center fs-sm">4.</td>
                <td class="fs-sm">Mengumpulkan dokumen dengan urutan ketiga pada jenis dokumen tersebut</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">Reward</span>
                </td>
                <td class="text-center fs-sm">{{ '+'.App\Models\Gamifikasi::getPointBonus3() }}</td>
              </tr>

              <tr>
                <td class="text-center fs-sm">5.</td>
                <td class="fs-sm">Mengumpulkan dokumen melewati tenggat waktu</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Punishment</span>
                </td>
                <td class="text-center fs-sm">{{ App\Models\Gamifikasi::getPointTerlambat() }}</td>
              </tr>

              <tr>
                <td class="text-center fs-sm">6.</td>
                <td class="fs-sm">Mengumpulkan dokumen tidak mengikuti ketentuan prodi</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Punishment</span>
                </td>
                <td class="text-center fs-sm">{{ App\Models\Gamifikasi::getPointDokumenSalah() }}</td>
              </tr>

              <tr>
                <td class="text-center fs-sm">7.</td>
                <td class="fs-sm">Tidak mengumpulkan dokumen hingga akhir tahun ajaran</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Punishment</span>
                </td>
                <td class="text-center fs-sm">{{ App\Models\Gamifikasi::getPointDokumenKosong() }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- END All Products -->
    </div>
    <!-- END Page Content -->
@endsection

@section('script')
    <script src={{  URL::asset("assets/js/plugins/datatables/jquery.dataTables.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/dataTables.buttons.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/buttons.html5.min.js") }}></script>

     <!-- Page JS Code -->
     <script src={{  URL::asset("assets/js/pages/be_tables_datatables.min.js") }}></script>
@endsection