@extends('layouts.user-base')
@section('title', 'Daftar Kelas')
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

     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">

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

      <!-- pop up error upload -->
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
            class="btn block block-rounded block-link-shadow text-center"
            id="btn-tambah-kelas"
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
                Tambah Kelas
              </p>
            </div>
          </a>
        </div>
      </div>
      <!-- END Quick Overview -->
      <!-- Modal -->
      <div
        class="modal fade"
        id="modal-tambah-kelas"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
          <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
              <div class="block-header bg-primary-dark">
                <h3 class="block-title">Tambah Kelas</h3>
                <button
                  type="button"
                  class="btn btn-alt-danger"
                  data-bs-dismiss="modal"
                  aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
              <form  action="{{ route('daftar-kelas.store') }}"
              method="POST"
              enctype="multipart/form-data">
               @csrf
                <div class="block-content fs-sm mb-3">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="nama_mata_kuliah">Nama Matkul</label>
                        <div class="col-lg-12">
                          <div class="mb-2">
                            <select class="js-select2 form-select select2insidemodal" name="kode_matkul" style="width: 100%;" data-placeholder="Pilih Mata Kuliah" required>
                              <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                              @foreach ($matkul as $item)
                              <option value="{{ $item->kode_matkul }}">{{ $item->nama_matkul }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <label for="text-input">Nama Kelas (Contoh. RA)</label>
                        <input
                            type="text"
                            id="text-input"
                            class="form-control mb-2"
                            placeholder="Masukkan Kelas (Contoh. RA)"
                            name="nama_kelas"
                            required />
                        <label for="dosen_pengampu">Dosen Pengampu</label>
                        <div class="col-lg-12">
                          <div class="mb-2">
                            <select class="js-select2 form-select select2insidemodal" name="id_dosen[]" data-placeholder="Pilih Dosen" style="width: 100%;"  multiple required>
                              <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                              @foreach ($dosen as $item)
                              <option value="{{ $item->id }}">{{ $item->nama }}</option>
                              @endforeach
                            </select>
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
                    class="btn btn-alt-primary btn-submit"
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
          <h3 class="block-title">Daftar Kelas</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
                <form action="/penugasan/daftar-kelas">
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
                <th class="text-center" >Kelas</th>
                <th class="text-center" >Mata Kuliah</th>
                <th class="text-center" >Dosen Pengampu</th>
                @if(isset($tahun_aktif) && (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true))
                <th class="text-center" style="width: 15%;">Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach ($kelas as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="text-center fs-sm">{{ $item->nama_kelas }}</td>
                <td class="fs-sm">{{ $item->matkul->nama_matkul }}</td>
                <td class="fs-sm">
                  <ul>
                    @foreach ($item->dosen_kelas as $dosen_pengampu)
                    <li>{{ $dosen_pengampu->nama }}</li>
                    @endforeach
                  </ul>
                </td>
                @if(isset($tahun_aktif) && (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true))
                <td class="text-center">
                  <form action="{{ route('daftar-kelas.destroy', $item->kode_kelas) }}" method="POST">
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="editKelas({{ $key }})" data-bs-toggle="tooltip" title="Edit">
                      <i class="fa fa-fw fa-pencil-alt"></i>
                    </a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-hapus btn-sm btn-alt-danger bg-danger-light"  data-bs-toggle="tooltip" title="Delete">
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
                    <h3 class="block-title">Edit Kelas</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  id="form-edit"
                  method="POST"
                  enctype="multipart/form-data">
                  @method('PUT')
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label class="form-label">Ubah Nama Kelas</label>
                            <div class="space-x-2 mb-2">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ubah-nama-kelas" id="ubah-nama-kelas1" value=1>
                                <label class="form-check-label" for="example-radios-inline1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ubah-nama-kelas" id="ubah-nama-kelas2" value=0 checked>
                                <label class="form-check-label" for="example-radios-inline2">Tidak</label>
                              </div>
                            </div>
                            <div class="mb-2" id="ubah-nama-kelas"></div>
                            <label class="form-label">Ubah Dosen</label>
                            <div class="space-x-2 mb-2">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ubah-dosen" id="ubah-dosen1" value=1>
                                <label class="form-check-label" for="example-radios-inline1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ubah-dosen" id="ubah-dosen2" value=0 checked>
                                <label class="form-check-label" for="example-radios-inline2">Tidak</label>
                              </div>
                            </div>
                            <div class="mb-2" id="ubah-dosen"></div>
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
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>
    <script src="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      One.helpersOnLoad([
        "jq-select2",
      ]);
    </script>

     <script>
      let jsfiles = @json($kelas);
      
      //modal
      function editKelas(id) {
        $('.modal-edit').modal({backdrop: 'static', keyboard: false});
        $('.modal-edit').modal("show");
        $('#form-edit').attr('action', '/penugasan/daftar-kelas/'+jsfiles[id].kode_kelas);
      }

      $(document).ready(function () {
          $(".alert").delay(2000).fadeOut("slow");

          $("#ubah-nama-kelas1").click(function () {
            $("#ubah-nama-kelas").html(
              `<label for="text-input">Nama Kelas (Contoh. RA)</label>
              <input
                  type="text"
                  class="form-control mb-2"
                  placeholder="Masukkan Kelas (Contoh. RA)"
                  name="nama_kelas"
                  required />`
            );
          });
          $("#ubah-nama-kelas2").click(function () {
            $("#ubah-nama-kelas").html('');
          });

          $("#ubah-dosen1").click(function () {
            $("#ubah-dosen").html(
              `<label for="dosen_pengampu">Dosen Pengampu</label>
                <div class="col-lg-12">
                  <div class="mb-2">
                    <select class="js-select2 form-select select2-inside-modal-edit" name="id_dosen[]" data-placeholder="Pilih Dosen" style="width: 100%;"  multiple required>
                      <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                      @foreach ($dosen as $item)
                      <option value="{{ $item->id }}">{{ $item->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>`
               );
              One.helpersOnLoad([
                  "jq-select2",
                ]);
                $(".select2-inside-modal-edit").select2({
                  dropdownParent: $(".modal-edit")
                });
            });
          $("#ubah-dosen2").click(function () {
            $("#ubah-dosen").html('');
          });

          $('.btn-hapus').click(function (e){
            e.preventDefault();
            let form = $(this).parents('form');
            Swal.fire({
              title: 'Apakah anda sudah yakin untuk menghapus kelas ?',
              text: 'Progres pengumpulan yang sudah ada akan hilang!',
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


            $(".select2insidemodal").select2({
              dropdownParent: $("#modal-tambah-kelas")
            });
            
            $('#modal-tambah-kelas').modal({backdrop: 'static', keyboard: false});
            $("#btn-tambah-kelas").on("click", function () {
              $("#modal-tambah-kelas").modal("show");
            });
        });
         
    </script>
@endsection