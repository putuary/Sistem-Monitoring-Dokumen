@extends('layouts.user-base')
@section('title', 'Dokumen Ditugaskan')
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
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
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

      @if(isset($tahun_aktif) && (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true))
      <!-- Quick Overview -->
       <div class="row">
        <div class="col-6 col-lg-3">
          <a
            class="btn block block-rounded block-link-shadow text-center button-tambah"
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
        class="modal fade modal-tambah"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
          <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
              <div class="block-header bg-primary-dark">
                <h3 class="block-title">Tambah Dokumen Ditugaskan</h3>
                <button
                  type="button"
                  class="btn btn-alt-danger"
                  data-bs-dismiss="modal"
                  aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
              <form  action="{{ route('dokumen-ditugaskan.store') }}"
              method="POST"
              enctype="multipart/form-data">
               @csrf
                <div class="block-content fs-sm mb-3">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="nama_dokumen">Nama Dokumen</label>
                        <div class="col-lg-12">
                          <div class="mb-2">
                            <select class="js-select2 form-select select2insidemodal" name="id_dokumen" style="width: 100%;" data-placeholder="Pilih Dokumen" required>
                              <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                              @foreach ($dokumen_perkuliahan as $item)
                              <option value="{{ $item->id_dokumen }}">{{ $item->nama_dokumen }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <label for="example-text-input">Tenggat Waktu</label>
                        <input type="datetime-local" class="form-control mb-2" min="{{ date('Y-m-d\TH:i') }}" name="tenggat_waktu" id="tenggat_waktu"/>
                        <label class="form-label fw-8">Dikumpul</label>
                        <div class="space-x-2 mb-2">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="example-radios-inline1" name="dikumpul" value=0 checked>
                            <label class="form-check-label" for="example-radios-inline1">Single Dokumen</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="example-radios-inline2" name="dikumpul" value=1>
                            <label class="form-check-label" for="example-radios-inline2">Multiple Dokumen</label>
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
      <!-- End Modal -->
      @endif

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Data Dokumen Ditugaskan</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
                <form action="/penugasan/dokumen-ditugaskan">
                <div class="mb-4 d-flex">
                  <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                  <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                  <select class="js-select2 form-select" id="one-ecom-product-category" name="tahun_ajaran" style="width: 100%;" data-placeholder="Pilih Tahun Ajaran..">
                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                    @foreach ($tahun_ajaran as $item)
                    <option value="{{ $item->id_tahun_ajaran }}"@selected((request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }}</option>
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
                <th class="text-center" >Tenggat Waktu</th>
                <th class="text-center" >Dikumpul</th>
                <th class="text-center"  style="width: 15%;">Pengumpulan</th>
                @if(isset($tahun_aktif) && (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true))
                <th class="text-center" style="width: 15%;">Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->nama_dokumen }}</td>
                <td class="fs-sm">{{ showWaktu($item->tenggat_waktu) }}</td>
                <td class="fs-sm text-center">{{ dikumpul($item->dikumpul) }}</td>
                <td class="text-center">
                  <div class="ms-5">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" onclick="aturPengumpulan({{ $key }})" id="example-switch-default1" name="example-switch-default1" @if($item->pengumpulan == 1) checked @endif>
                      </div>
                  </div>
                </td>
                @if(isset($tahun_aktif) && (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true))
                <td class="text-center">
                  <form action="{{ route('dokumen-ditugaskan.destroy', $item->id_dokumen_ditugaskan) }}" method="POST">
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="editPengingat({{ $key }})" data-bs-toggle="tooltip" title="Edit">
                      <i class="fa fa-fw fa-pencil-alt"></i>
                    </a>
                    @csrf
                    @method('DELETE')
                      <button class="btn btn-hapus btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Delete">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </form>
                </td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
          @if(isset($tahun_aktif) && (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true))
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
                  
                  <form  id="form-edit"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                   @method('PUT')
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="example-text-input" class="mb-2">Tenggat Waktu</label>
                            <input type="datetime-local" class="form-control" min="{{ date('Y-m-d\TH:i') }}" name="tenggat_waktu" id="tenggat_waktu"/>
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
          @endif
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
     <script src="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
     <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>
     <script src="{{ URL::asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
     <script src="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
    <script>
      One.helpersOnLoad([
        "jq-notify",
        "jq-select2",
        "js-flatpickr",
      ]);
    </script>

     <script>
      let jsfiles = @json($dokumen);
      // console.log(jsfiles);
      //modal
      function editPengingat(id) {
        $('.modal-edit').modal({backdrop: 'static', keyboard: false});
        $('.modal-edit').modal("show");
        $('#form-edit').attr('action', '/penugasan/dokumen-ditugaskan/' + jsfiles[id].id_dokumen_ditugaskan);
        $('#tenggat_waktu').val(jsfiles[id].tenggat_waktu);
      }

      function aturPengumpulan(id) {
        // post data using ajax
        $.ajax({
          url: '/penugasan/dokumen-ditugaskan/edit-pengumpulan',
          type: 'POST',
          data: {
            id_dokumen_ditugaskan: jsfiles[id].id_dokumen_ditugaskan,
            _token: '{{ csrf_token() }}'
          },
          success: function (data) {
            if(data.pengumpulan) {
              One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: data.message});
            } else{
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: data.message});
            }
          }
        });
      }

      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");

        $('.btn-hapus').click(function (e){
           e.preventDefault();
           let form = $(this).parents('form');
           Swal.fire({
            title: 'Apakah anda sudah yakin untuk menghapus dokumen yang sudah ditugaskan ?',
            text: 'Progres pengumpulan pada dokumen ini yang sudah ada akan hilang!',
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'Yakin',
            denyButtonText: `Batal`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                form.submit();
              }
            });
          });

          $('.modal-tambah').modal({backdrop: 'static', keyboard: false});
          $(".button-tambah").on("click", function () {
            $(".modal-tambah").modal("show");
          });

          $(".select2insidemodal").select2({
            dropdownParent: $(".modal-tambah")
          });
          $(".select2-inside-modal-edit").select2({
            dropdownParent: $(".modal-edit")
          });

          $('.modal').on('shown.bs.modal', function () {
            $(".js-flatpickr").flatpickr();
          });
      });


    </script>
@endsection