@extends('layouts.user-base')

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

     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <!-- Page Content -->
     <!-- pop up success upload -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="content">
      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Riwayat Pengumpulan</h3>
          <div class="block-options">
            <div class="dropdown">
              <button type="button" class="btn-block-option" id="dropdown-ecom-filters" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filters <i class="fa fa-angle-down ms-1"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-ecom-filters">
                <a class="dropdown-item d-flex align-items-center justify-content-between" href="?filter=terkumpul{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Terkumpul
                  <span class="badge bg-black-50 rounded-pill">{{ $terkumpul }}</span>
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between" href="?filter=tepat_waktu{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Tepat Waktu
                  <span class="badge bg-warning rounded-pill">{{ $tepat_waktu }}</span>
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between" href="?filter=terlambat{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Terlambat
                  <span class="badge bg-info rounded-pill">{{ $terlambat }}</span>
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between" href="?filter=belum_terkumpul{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Kosong
                  <span class="badge bg-danger rounded-pill">{{ $belum_terkumpul }}</span>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
              <form action="/riwayat-pengumpulan">
                <div class="mb-4 d-flex">
                  @if(request('filter'))
                  <input type="hidden" name="filter" value="{{ request('filter') }}">
                  @endif
                  <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                  <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                  <select class="js-select2 form-select" id="one-ecom-product-category" name="tahun_ajaran" style="width: 100%;" data-placeholder="Choose one..">
                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                    @foreach ($tahun_ajaran as $item)
                    <option value={{ $item->id_tahun_ajaran }} @selected(($dokumen[0]->dokumen_ditugaskan->id_tahun_ajaran ?? null) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="input-group-text">
                    <i class="fa fa-fw fa-search"></i>
                  </button>
                </div>
              </form>
              </div>
            </div>
          </div>
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Dokumen</th>
                <th class="text-center" >Kelas</th>
                <th class="text-center" >Dosen</th>
                <th class="text-center" >Waktu Pengumpulan</th>
                <th class="text-center" >Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen }}</td>
                @if($item->dokumen_ditugaskan->dokumen_perkuliahan->dikumpulkan_per == 1)
                <td class="fs-sm">{{ $item->kelas->matkul->nama_matkul.' '.$item->kelas->nama_kelas }}</td>
                <td class="fs-sm">
                  <ul>
                    @foreach ($item->kelas->dosen_kelas as $dosen)
                    <li>{{ $dosen->nama }}</li>
                    @endforeach
                  </ul>
                </td>
                @else
                  @php
                      $dokumen_matkul=showProfilDokumen($item->id_dokumen_kelas)
                  @endphp
                 
                <td class="fs-sm">{{ $dokumen_matkul->nama_matkul }}</td>
                <td class="fs-sm">
                  <ul>
                    @foreach ($dokumen_matkul->dosen as $dosen)
                      <li>{{ $dosen }}</li>
                    @endforeach
                  </ul>
                </td>
                @endif
                <td class="text-center">
                  {{ showWaktu($item->waktu_pengumpulan) }}
                </td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->waktu_pengumpulan ? backgroundStatus($item->dokumen_ditugaskan->tenggat_waktu, $item->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->waktu_pengumpulan ? statusPengumpulan($item->dokumen_ditugaskan->tenggat_waktu, $item->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
                </td>
              </tr>
              @endforeach
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
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons-jszip/jszip.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/buttons.print.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/buttons.html5.min.js") }}></script>

     <!-- Page JS Code -->
     <script src={{  URL::asset("assets/js/pages/be_tables_datatables.min.js") }}></script>

     <!-- Page JS Plugins -->
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      One.helpersOnLoad([
        "jq-select2",
      ]);
    </script>

     <script>
      let jsfiles = 1;
      // console.log(jsfiles);
      //modal
      function editPengingat(id) {
        $('.modal-edit').modal("show");
        $('#id_dokumen_ditugaskan').val(jsfiles[id].id_dokumen_ditugaskan);
        $('#tenggat_waktu').val(jsfiles[id].tenggat_waktu);
      }

      function aturPengumpulan(id) {
        // post data using ajax
        $.ajax({
          url: '/atur-pengingat-pengumpulan/edit_pengumpulan',
          type: 'POST',
          data: {
            id_dokumen_ditugaskan: jsfiles[id].id_dokumen_ditugaskan,
            _token: '{{ csrf_token() }}'
          },
          success: function (status) {
            console.log(status);
          }
        });
      }


    </script>
@endsection