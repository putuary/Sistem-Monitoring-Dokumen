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

    {{-- @error('kode_matkul')
    <?= "<script type ='text/javascript'>window.onload = function() { errorAlert('Kode Mata Kuliah sudah digunakan');};  </script>"; ?>
    @enderror --}}

    @error('kode_matkul')
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
      <strong>Kode mata kuliah tidak boleh sama</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @enderror
    <div class="content">
      {{-- <embed src="{{ URL::asset('/assets/media/avatars/test.pdf') }}" width=100% height=600 type="text/pdf" > --}}
      {{-- <a href="{{ URL::asset("/assets/media/avatars/test.pdf") }}" target="_blank">Cerita.pdf</a> --}}
      {{-- <object src={{ URL::asset("/assets/media/avatars/test.pdf") }} type="application/pdf" width=100% height=100%> </object> --}}
          <!-- Quick Overview -->
           <div class="row">
            <div class="col-6 col-lg-3">
              <a
                class="btn block block-rounded block-link-shadow text-center button-tambah-matkul"
                id="btn-detail"
                type="button"
                data-toggle="modal"
                data-target="#modal-block-normal">
                <div class="block-content block-content-full">
                  <div class="fs-2 fw-semibold text-success">
                    <i class="fa fa-plus"></i>
                  </div>
                </div>
                <div class="block-content py-2 bg-body-light">
                  <p class="fw-medium fs-sm text-success mb-0">
                    Tambah Mata Kuliah
                  </p>
                </div>
              </a>
            </div>
          </div>
          <!-- END Quick Overview -->
          <!-- Modal -->
          <div
            class="modal fade modal-tambah-matkul"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout" role="document">
              <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                  <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Tambah Mata Kuliah</h3>
                    <button
                      type="button"
                      class="btn btn-alt-danger"
                      data-bs-dismiss="modal"
                      aria-label="Close">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </div>
                  <form  action="/manajemen-data/mata-kuliah/tambah"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="example-text-input">Kode Mata Kuliah</label>
                            <input
                                type="text"
                                class="form-control mb-3 @error('kode_matkul') is-invalid @enderror"
                                placeholder="Masukkan Kode Mata Kuliah"
                                name="kode_matkul"
                                required />
                                @error('kode_matkul')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            <label for="example-text-input">Nama Mata Kuliah</label>
                            <input
                                type="text"
                                class="form-control mb-3"
                                placeholder="Masukkan Nama Mata Kuliah"
                                name="nama_matkul"
                                required />
                            <label for="example-text-input">Bobot SKS</label>
                            <input
                                type="number"
                                class="form-control mb-3"
                                placeholder="Masukkan Bobot SKS"
                                name="bobot_sks"
                                required />
                            <label class="form-label fw-8">Praktikum</label>
                            <div class="space-x-2 mb-3">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline1" name="praktikum" value=1 checked>
                                <label class="form-check-label" for="example-radios-inline1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline2" name="praktikum" value=0>
                                <label class="form-check-label" for="example-radios-inline2">Tidak</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      class="block-content block-content-full text-end border-top">
                      <button
                        type="submit"
                        class="btn btn-alt-primary">
                        <i class="fa fa-check me-1"></i>Simpan
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End Modal -->

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Data Mata Kuliah</h3>
        </div>
        <div class="block-content block-content-full">
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Mata Kuliah</th>
                <th class="text-center" >Kode</th>
                <th class="text-center"  style="width: 15%;">Bobot</th>
                <th class="text-center"  style="width: 15%;">Praktikum</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($matkul as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="text-center fs-sm">{{ $item->nama_matkul }}</td>
                <td class="text-center fw-semibold fs-sm">{{ $item->kode_matkul }}</td>
                <td class="text-center fw-semibold fs-sm">{{ $item->bobot_sks }}</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill @if($item->praktikum==1) bg-success-light text-success @else bg-danger-light text-danger @endif ">{{ isPraktikum($item->praktikum) }}</span>
                </td>
                <td class="text-center">
                  <form action="/manajemen-data/mata-kuliah/delete" method="POST">
                    @csrf
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="editMatkul({{ $key }})" data-bs-toggle="tooltip" title="Edit">
                    <i class="fa fa-fw fa-pencil-alt"></i>
                  </a>
                    <input type="hidden" name="kode_matkul" value="{{ $item->kode_matkul }}">
                    <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Delete">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="modal fade modal-edit" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Matkul</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  id="submit-edit"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="example-text-input">Kode Mata Kuliah</label>
                            <input
                                type="text"
                                class="form-control mb-3 @error('kode_matkul') is-invalid @enderror"
                                placeholder="Masukkan Kode Mata Kuliah"
                                id="kode_matkul"
                                name="kode_matkul"
                                required />
                                @error('kode_matkul')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            <label for="example-text-input">Nama Mata Kuliah</label>
                            <input
                                type="text"
                                class="form-control mb-3"
                                placeholder="Masukkan Nama Mata Kuliah"
                                id="nama_matkul"
                                name="nama_matkul"
                                required />
                            <label for="example-text-input">Bobot SKS</label>
                            <input
                                type="number"
                                class="form-control mb-3"
                                placeholder="Masukkan Bobot SKS"
                                id="bobot_sks"
                                name="bobot_sks"
                                required />
                            <label class="form-label">Praktikum</label>
                            <div class="space-x-2 mb-3">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="praktikum_ya" name="praktikum" value=1>
                                <label class="form-check-label" for="example-radios-inline1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="praktikum_tidak" name="praktikum" value=0>
                                <label class="form-check-label" for="example-radios-inline2">Tidak</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      class="block-content block-content-full text-end border-top">
                      <button
                        type="submit"
                        class="btn btn-alt-primary"
                        data-bs-dismiss="modal">
                        <i class="fa fa-check me-1"></i>Simpan
                      </button>
                    </div>
                  </form>
                  
                </div>
              </div>
            </div>
          </div>
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
    <script src={{ URL::asset("assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js") }}></script>

     <!-- Page JS Code -->
     <script src={{  URL::asset("assets/js/pages/be_tables_datatables.min.js") }}></script>
     <!-- Page JS Helpers (BS Notify Plugin) -->
    <script>One.helpersOnLoad(['jq-notify']);</script>

     <script>
      let jsfiles = <?php echo json_encode($matkul) ?>;
      console.log(jsfiles);

      function errorAlert(message) {
        One.helpers('notify', {type: 'danger', icon: 'fa fa-times me-1', message: message});
      }

      //modal
      function editMatkul(id) {
        $('.modal-edit').modal({backdrop: 'static', keyboard: false});
        $('.modal-edit').modal("show");
        $('#submit-edit').attr('action', '/manajemen-data/mata-kuliah/edit/' + jsfiles[id].kode_matkul);
        $('#kode_matkul').val(jsfiles[id].kode_matkul);
        $('#nama_matkul').val(jsfiles[id].nama_matkul);
        $('#bobot_sks').val(jsfiles[id].bobot_sks);
        if(jsfiles[id].praktikum === 1){
          $('#praktikum_ya').attr('checked', 'checked');
        }else{
          $('#praktikum_tidak').attr('checked', 'checked');
        }
      }

      $(document).ready(function () {
        $('.modal-tambah-matkul').modal({backdrop: 'static', keyboard: false});
        $(".button-tambah-matkul").on("click", function () {
          $(".modal-tambah-matkul").modal("show");
        });
      });
    </script>
@endsection