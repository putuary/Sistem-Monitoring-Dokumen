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
          <h3 class="block-title">Dokumen Perkuliahan</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
                <form action="/dokumen-perkuliahan">
                <div class="mb-4 d-flex">
                  <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                  <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                  <select class="js-select2 form-select" name="tahun_ajaran" style="width: 100%;" data-placeholder="Choose one..">
                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                    @foreach ($tahun_ajaran as $item)
                    <option value="{{ $item->id_tahun_ajaran }}" @selected((request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }}</option>
                    @endforeach
                  </select>
                  <button class="input-group-text">
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
                <th class="text-center" >Nama Dokumen</th>
                <th class="text-center" >Mata Kuliah/Kelas</th>
                <th class="text-center" >Dosen</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
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
                  @foreach ($item->dosen as $nama_dosen)
                      <li>{{ $nama_dosen }}</li>
                  @endforeach
                  </ul>
                </td>
                <td class="text-center">
                  @if($item->dikumpul==0)
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" href="/dokumen-perkuliahan/show/{{ $item->id_dokumen }}" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                    <i class="fa fa-fw fa-eye"></i>
                  </a>
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" href="/dokumen-perkuliahan/download/{{ $item->id_dokumen }}" data-bs-toggle="tooltip" title="Unduh Dokumen">
                    <i class="fa fa-fw fa-download"></i>
                  </a>
                  @else
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" href="/dokumen-perkuliahan/show-multiple/{{ $item->id_dokumen }}" data-bs-toggle="tooltip" title="Lihat Dokumen">
                    <i class="fa fa-fw fa-eye"></i>
                  </a>
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" href="/dokumen-perkuliahan/download/{{ $item->id_dokumen }}" data-bs-toggle="tooltip" title="Unduh Dokumen">
                    <i class="fa fa-fw fa-download"></i>
                  </a>
                  @endif
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

      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection