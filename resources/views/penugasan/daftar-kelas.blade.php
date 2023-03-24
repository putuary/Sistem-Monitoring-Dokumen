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
          <h3 class="block-title">Atur Pengingat dan Pengumpulan</h3>
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
                    <option value="1">2020/2021 Genap</option>
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
                <th class="text-center">Kode Kelas</th>
                <th class="text-center" >Nama Kelas</th>
                <th class="text-center" >Dosen Pengampu</th>
                <th class="text-center"  style="width: 15%;">Progres</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($kelas as $item)
              <tr>
                <td class="text-center fs-sm">{{ $item->kode_kelas }}</td>
                <td class="text-center fs-sm">{{ $item->nama_kelas }}</td>
                <td class="fs-sm">
                  <ul>
                    @foreach ($item->dosen_kelas as $dosen)
                    <li>{{ $dosen->nama }}</li>
                    @endforeach
                  </ul>
                </td>
                <td>tes</td>
                <td class="text-center">
                  <form action="/manajemen-pengguna/delete" method="POST">
                    @csrf
                  <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="" data-bs-toggle="tooltip" title="Edit">
                    <i class="fa fa-fw fa-pencil-alt"></i>
                  </a>
                    <input type="hidden" name="id_pengguna" value="">
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
      let jsfiles = 1;
      console.log(jsfiles);
      //modal
      function edit_pengguna(id) {
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
        $(".button-tambah-pengguna").on("click", function () {
          $("#modal-tambah-pengguna").modal("show");
        });

        $(".modal-edit").attr("id", "modal-edit");
      });
    </script>
@endsection