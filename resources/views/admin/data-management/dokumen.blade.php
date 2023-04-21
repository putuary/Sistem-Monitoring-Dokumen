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

    <div class="content">
      <!-- pop up success upload -->
      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if (session()->has('failed'))
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>{{ session()->get('failed') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif
    
          <!-- Quick Overview -->
           <div class="row">
            <div class="col-6 col-lg-3">
              <a
                class="btn block block-rounded block-link-shadow text-center button-tambah-dokumen"
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
                    Tambah Dokumen
                  </p>
                </div>
              </a>
            </div>
          </div>
          <!-- END Quick Overview -->
          <!-- Modal -->
          <div
            class="modal fade modal-tambah-dokumen"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout" role="document">
              <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                  <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Tambah Dokumen Perkuliahan</h3>
                    <button
                      type="button"
                      class="btn btn-alt-danger"
                      data-bs-dismiss="modal"
                      aria-label="Close">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </div>
                  <form  action="/manajemen-data/dokumen-perkuliahan/tambah"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="example-text-input">Nama Dokumen Perkuliahan</label>
                            <input
                                type="text"
                                class="form-control mb-3"
                                placeholder="Masukkan Nama Dokumen Perkuliahan"
                                name="nama_dokumen"
                                required />
                            <label for="example-text-input">Tenggat Waktu Default</label>
                            <input
                                type="number"
                                class="form-control mb-3"
                                placeholder="Masukkan Tenggat Waktu Default"
                                name="tenggat_waktu_default"
                                required />
                            <label class="form-label">Dikumpulkan Per</label>
                            <div class="space-x-2 mb-3">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline1" name="dikumpulkan_per" value=0 required>
                                <label class="form-check-label" for="example-radios-inline1">Mata Kuliah</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline2" name="dikumpulkan_per" value=1 required>
                                <label class="form-check-label" for="example-radios-inline2">Kelas</label>
                              </div>
                            </div>
                            <label for="example-text-input">Template Dokumen Perkuliahan</label>
                            <input
                                type="file"
                                class="form-control mb-3"
                                name="template" />
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
                <th class="text-center" >Nama Dokumen Perkuliahan</th>
                <th class="text-center" >Tenggat Waktu</th>
                <th class="text-center" >Dikumpulkan Per</th>
                <th class="text-center" >Template</th>
                <th class="text-center" >Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->nama_dokumen }}</td>
                <td class="fs-sm">{{ 'Minggu '.$item->tenggat_waktu_default }}</td>
                <td class="text-center fw-semibold fs-sm">{{ $item->dikumpulkan_per==0 ? "Mata Kuliah" : "Kelas" }}</td>
                <td class="text-center fw-semibold fs-sm"><i class="far fa-fw fa-{{ isset($item->template) ? 'square-check' : 'rectangle-xmark' }}"></i></td>
                <td class="text-center">
                  <form action="/manajemen-data/dokumen-perkuliahan/delete" method="POST">
                    @csrf
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="editDokumen({{ $key }})" data-bs-toggle="tooltip" title="Edit">
                    <i class="fa fa-fw fa-pencil-alt"></i>
                  </a>
                    <input type="hidden" name="id_dokumen" value="{{ $item->id_dokumen }}">
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
                    <h3 class="block-title">Edit Dokumen</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  action="/manajemen-data/dokumen-perkuliahan/edit"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input type="hidden" id="id_dokumen" name="id_dokumen">
                            <label for="example-text-input">Nama Dokumen Perkuliahan</label>
                            <input
                                type="text"
                                class="form-control mb-3"
                                placeholder="Masukkan Nama Dokumen Perkuliahan"
                                id="nama_dokumen"
                                name="nama_dokumen"
                                required />
                            <label for="example-text-input">Tenggat Waktu Default</label>
                            <input
                                type="number"
                                class="form-control mb-3"
                                placeholder="Masukkan Tenggat Waktu Default"
                                id="tenggat_waktu_default"
                                name="tenggat_waktu_default"
                                required />
                            <label class="form-label">Dikumpulkan Per</label>
                            <div class="space-x-2 mb-3">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="mata-kuliah" name="dikumpulkan_per" value=0>
                                <label class="form-check-label" for="example-radios-inline1">Mata Kuliah</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="kelas" name="dikumpulkan_per" value=1>
                                <label class="form-check-label" for="example-radios-inline2">Kelas</label>
                              </div>
                            </div>
                            <label for="example-text-input">Template Dokumen Perkuliahan</label>
                            <input
                                type="file"
                                class="form-control mb-3"
                                name="template"
                                />
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
      let jsfiles = <?php echo json_encode($dokumen) ?>;

      //modal
      function editDokumen(id) {
        $('.modal-edit').modal({backdrop: 'static', keyboard: false});
        $('.modal-edit').modal("show");
        $('#id_dokumen').val(jsfiles[id].id_dokumen);
        $('#nama_dokumen').val(jsfiles[id].nama_dokumen);
        $('#tenggat_waktu_default').val(jsfiles[id].tenggat_waktu_default);
        if(jsfiles[id].dikumpulkan_per === 0){
          $('#mata-kuliah').attr('checked', 'checked');
        }else{
          $('#kelas').attr('checked', 'checked');
        }
      }

      $(document).ready(function () {

        $(".alert").delay(2000).fadeOut("slow");

        $('.modal-tambah-dokumen').modal({backdrop: 'static', keyboard: false});
        $(".button-tambah-dokumen").on("click", function () {
          $(".modal-tambah-dokumen").modal("show");
        });
      });
    </script>
@endsection