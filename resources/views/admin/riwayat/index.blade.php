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

     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.css') }}" />
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
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
                <div class="mb-4 d-flex">
                  <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                  <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                  <select class="js-select2 form-select" id="one-ecom-product-category" name="one-ecom-product-category" style="width: 100%;" data-placeholder="Choose one..">
                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                    <option value="1">Semua</option>
                    <option value="2" selected>Video Games</option>
                    <option value="3">Tablets</option>
                    <option value="4">Laptops</option>
                    <option value="5">PC</option>
                    <option value="6">Home Cinema</option>
                    <option value="7">Sound</option>
                    <option value="8">Office</option>
                    <option value="9">Adapters</option>
                  </select>
                  <button class="input-group-text">
                    <i class="fa fa-fw fa-search"></i>
                  </button>                
                </div>
              </div>
            </div>
          </div>
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Kelas</th>
                <th class="text-center" >Dosen</th>
                <th class="text-center" >Waktu Pengumpulan</th>
                <th class="text-center" >Status</th>
              </tr>
            </thead>
            <tbody>
              {{-- @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->dokumen_perkuliahan->nama_dokumen }}</td>
                <td class="fs-sm">{{ showTenggat($item->tenggat_waktu) }}</td>
                <td class="text-center">
                  <div class="d-flex">
                      <div class="form-check form-switch align-items-center">
                        <input class="form-check-input" type="checkbox" onclick="aturPengumpulan({{ $key }})" id="example-switch-default1" name="example-switch-default1" @if($item->pengumpulan == 1) checked @endif>
                      </div>
                  </div>
                </td>
                <td class="text-center">
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="editPengingat({{ $key }})" data-bs-toggle="tooltip" title="Edit">
                    <i class="fa fa-fw fa-pencil-alt"></i>
                  </a>
                </td>
              </tr>
              @endforeach --}}
            </tbody>
          </table>
          <div class="modal fade modal-edit" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Tenggat Waktu</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  action="/atur-pengingat-pengumpulan/edit"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input type="hidden" name="id_dokumen_ditugaskan" id="id_dokumen_ditugaskan">
                            <label for="example-text-input">Tenggat Waktu</label>
                            <input type="text" class="js-flatpickr form-control" id="tenggat_waktu" name="tenggat_waktu" data-enable-time="true" data-time_24hr="true" />
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

     <!-- Page JS Code -->
     <script src={{  URL::asset("assets/js/pages/be_tables_datatables.min.js") }}></script>

     <!-- Page JS Plugins -->
     <script src="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      One.helpersOnLoad([
        "js-flatpickr",
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