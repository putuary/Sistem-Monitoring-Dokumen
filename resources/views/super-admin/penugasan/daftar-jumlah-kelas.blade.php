@extends('layouts.user-base')
@section('title', 'Daftar Jumlah Kelas')
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

      @if (session()->has('failed'))
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>{{ session()->get('failed') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Daftar Jumlah Kelas</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
                <form action="/penugasan/daftar-jumlah-kelas">
                  <div class="mb-4 d-flex">
                    <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                    <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                    <select class="js-select2 form-select" id="one-ecom-product-category" name="tahun_ajaran" style="width: 100%;" data-placeholder="Choose one..">
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
                <th class="text-center" >Nama Mata Kuliah</th>
                <th class="text-center" >Kode Mata Kuliah</th>
                <th class="text-center"  style="width: 15%;">Bobot SKS</th>
                <th class="text-center"  style="width: 15%;">Jumlah Kelas</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($matkul as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->nama_matkul }}</td>
                <td class="text-center fw-semibold fs-sm">{{ $item->kode_matkul }}</td>
                <td class="text-center">{{ $item->bobot_sks.' SKS' }}</td>
                <td class="text-center">{{ $item->banyak_kelas.' Kelas' }}</td>
              </tr>
              @endforeach
              
            </tbody>
          </table>
          <div class="modal fade modal-edit" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Pengguna</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  action="/manajemen-pengguna/edit"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input type="hidden" name="id" id="id_pengguna">
                            <label for="example-text-input">Nama</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Masukkan Nama"
                                id="nama"
                                name="nama"
                                required />
                            <label for="example-text-input">Email</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Masukkan Email"
                                id="email"
                                name="email"
                                required />
                            <label for="example-text-input">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Kosongkan Password Jika Tidak Diubah"
                                name="password"
                                />
                            <label for="example-text-input">Peran</label>
                            <select
                                class="js-select2 form-select"
                                name="role"
                                required >
                                <option value="">Pilih Peran</option>
                                <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                <option id="kaprodi" value="kaprodi">Koordinator Prodi</option>
                                <option id="gkmp" value="gkmp">Gugus Kendali Mutu Prodi</option>
                                <option id="dosen" value="dosen">Dosen Pengampu</option>
                                <option id="admin" value="admin">Administrator Prodi</option>
                            </select>    
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
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      One.helpersOnLoad([
        "jq-select2",
      ]);
    </script>

    <script>
      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection