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
              <button type="button" class="btn-block-option" id="dropdown-ecom-filters" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Filter">
                <i class="fa fa-filter"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-ecom-filters">
                <a class="dropdown-item d-flex align-items-center justify-content-between {{ (request('filter') == null || request('filter') == 'terkumpul') ? 'active' : ''  }}" href="?filter=terkumpul{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Terkumpul
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('filter') == 'tepat_waktu' ? 'active' : ''  }}" href="?filter=tepat_waktu{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Tepat Waktu
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('filter') == 'terlambat' ? 'active' : ''  }}" href="?filter=terlambat{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Terlambat
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('filter') == 'belum_terkumpul' ? 'active' : ''  }}" href="?filter=belum_terkumpul{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Belum Dikumpulkan
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('filter') == 'terlewat' ? 'active' : ''  }}" href="?filter=terlewat{{ request('tahun_ajaran') ? '&tahun_ajaran='.request('tahun_ajaran') : '' }}">
                  Terlewat
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
                    <option value={{ $item->id_tahun_ajaran }} @selected((request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }}</option>
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
                <th class="text-center" >Matkul/Kelas</th>
                <th class="text-center" >Dosen</th>
                <th class="text-center" >Waktu Pengumpulan</th>
                <th class="text-center" >Status</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->nama_dokumen }}</td>
                <td class="fs-sm">{{ $item->matkul_kelas }}</td>
                <td class="fs-sm">
                  <ul>
                    @foreach ($item->dosen as $dosen)
                    <li>{{ $dosen }}</li>
                    @endforeach
                  </ul>
                </td>
                <td class="text-center fs-sm">
                  {{ showWaktu($item->waktu_pengumpulan) }}
                </td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->waktu_pengumpulan ? backgroundStatus($item->tenggat_waktu, $item->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->waktu_pengumpulan ? statusPengumpulan($item->tenggat_waktu, $item->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
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