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
    <div class="content">
          <!-- Quick Overview -->
           <div class="row">
            <div class="col-6 col-lg-3">
              <a
                class="btn block block-rounded block-link-shadow text-center button-tambah-pengguna"
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
                    Tambah Pengguna
                  </p>
                </div>
              </a>
            </div>
          </div>
          <!-- END Quick Overview -->
          <!-- Modal -->
          <div
            class="modal fade"
            id="modal-tambah-pengguna"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout" role="document">
              <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                  <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Tambah Pengguna</h3>
                    <button
                      type="button"
                      class="btn btn-alt-danger"
                      data-bs-dismiss="modal"
                      aria-label="Close">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </div>
                  <form  action="/manajemen-pengguna/tambah"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="example-text-input">Nama</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Masukkan Nama"
                                name="nama"
                                required />
                            <label for="example-text-input">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Masukkan Email"
                                name="email"
                                required />
                            <label for="example-text-input">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Masukkan Password"
                                name="password"
                                required />
                            <label for="example-text-input">Peran</label>
                            <select
                                class="js-select2 form-select"
                                id="one-ecom-product-category"
                                name="role"
                                required >
                                <option value="">Pilih Peran</option>
                                <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                <option value="kaprodi">Koordinator Prodi</option>
                                <option value="gkmp">Gugus Kendali Mutu Prodi</option>
                                <option value="dosen">Dosen Pengampu</option>
                                <option value="admin">Administrator Prodi</option>
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
          <!-- End Modal -->

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Data Pengguna</h3>
        </div>
        <div class="block-content block-content-full">
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Nama</th>
                <th class="text-center" >Email</th>
                <th class="text-center"  style="width: 15%;">Peran</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="text-center fs-sm">{{ $item->nama }}</td>
                <td class="text-center fw-semibold fs-sm">{{ $item->email }}</td>
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill <?php if($item->role=="kaprodi") {echo "bg-success-light text-success"; } else if ($item->role=="gkmp") {echo "bg-warning-light text-warning"; } else if($item->role=="dosen") {echo "bg-danger-light text-danger"; } else if($item->role=="admin") {echo "bg-primary-light text-primary"; } ?>">{{ NamaPeran($item->role) }}</span>
                </td>
                <td class="text-center">
                  <form action="/manajemen-pengguna/delete" method="POST">
                    @csrf
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="edit_pengguna({{ $key }})" data-bs-toggle="tooltip" title="Edit">
                    <i class="fa fa-fw fa-pencil-alt"></i>
                  </a>
                    <input type="hidden" name="id_pengguna" value="{{ $item->id }}">
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
                                type="email"
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

     <script>
      let jsfiles = <?php echo json_encode($data) ?>;
      console.log(jsfiles);
      //modal
      function edit_pengguna(id) {
        $('.modal-edit').modal({backdrop: 'static', keyboard: false});
        $('.modal-edit').modal("show");
        $('#id_pengguna').val(jsfiles[id].id);
        $('#nama').val(jsfiles[id].nama);
        $('#email').val(jsfiles[id].email);
        
        if(jsfiles[id].role === 'kaprodi'){
          $('#kaprodi').attr('selected', 'selected');
        }else if(jsfiles[id].role === 'gkmp'){
          $('#gkmp').attr('selected', 'selected');
        }else if(jsfiles[id].role === 'dosen'){
          $('#dosen').attr('selected', 'selected');
        }else if(jsfiles[id].role === 'admin'){
          $('#admin').attr('selected', 'selected');
        }
      }

      $(document).ready(function () {
        $('#modal-tambah-pengguna').modal({backdrop: 'static', keyboard: false});
        $(".button-tambah-pengguna").on("click", function () {
          $("#modal-tambah-pengguna").modal("show");
        });

        $(".modal-edit").attr("id", "modal-edit");
      });
    </script>
@endsection