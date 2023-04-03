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
    <script>
    </script>
    <div class="content">
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
                    <option value="{{ $item->id_tahun_ajaran }}" @selected(($dokumen[0]->tahun_ajaran->id_tahun_ajaran ?? null) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }}</option>
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
                <th class="text-center" style="width: %;">Aksi</th>
              </tr>
            </thead>
            <tbody>
             
              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->dokumen_perkuliahan->nama_dokumen }}</td>
                @if ($item->dokumen_perkuliahan->dikumpulkan_per==0)
                <td class="fs-sm">{{ $item->dokumen_matkul->matkul->nama_matkul }}</td>
                <td class="fs-sm">
                  @php
                      $dosen = dosenKelas($item->dokumen_matkul->kelas_dokumen_matkul);
                  @endphp
                  <ul>
                  @foreach ($dosen as $nama_dosen)
                      <li>{{ $nama_dosen }}</li>
                  @endforeach
                  </ul>
                </td>
                @else
                <td class="fs-sm">{{ $item->dokumen_kelas->kelas->matkul->nama_matkul.' '.$item->dokumen_kelas->kelas->nama_kelas }}</td>
                <td>
                  <ul>
                    @foreach ($item->dokumen_kelas->kelas->dosen_kelas as $dosen)
                        <li>{{ $dosen->nama }}</li>
                    @endforeach
                    </ul>
                </td>
                @endif
                {{-- <td class="text-center">
                  <form action="/manajemen-pengguna/delete" method="POST">
                    @csrf
                  @if ($item->dokumen_perkuliahan->dikumpulkan_per==0)
                    @if (isset($item->dokumen_perkuliahan->template) && !isset($item->dokumen_matkul[0]->file_dokumen))
                      <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" href='/kelas-diampu/download/{{ $item->dokumen_perkuliahan->id_dokumen }}' data-bs-toggle="tooltip" title="Template Dokumen">
                        <i class="fa fa-file-lines fa-fw"></i>
                      </a>
                    @endif
                    @if (isset($item->dokumen_matkul[0]->file_dokumen))
                    <a href="{{ asset('/storage'.pathDokumen($item->tahun_ajaran->tahun_ajaran, ismatkul($item->dokumen_perkuliahan->dikumpulkan_per), $item->dokumen_matkul[0]->matkul->nama_matkul ).'/'.$item->dokumen_matkul[0]->file_dokumen ) }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                      <i class="fa fa-fw fa-eye"></i>
                    </a>
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="" data-bs-toggle="tooltip" title="Unduh Dokumen">
                      <i class="fa fa-fw fa-download"></i>
                    </a>
                      <input type="hidden" name="id_pengguna" value="">
                      <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    @else
                      @if ($item->pengumpulan != 0)
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="uploadDokumen({{ $key }})" data-bs-toggle="tooltip" title="Unggah Dokumen">
                      <i class="fa fa-fw fa-upload"></i>
                    </a>
                      @endif
                    @endif
                  @else
                    @if (isset($item->dokumen_perkuliahan->template) && !isset($item->dokumen_kelas[0]->file_dokumen))
                    <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" href='/kelas-diampu/download-template/{{ $item->dokumen_perkuliahan->id_dokumen }}' data-bs-toggle="tooltip" title="Template Dokumen">
                      <i class="fa fa-file-lines fa-fw"></i>
                    </a>
                    @endif
                    @if (isset($item->dokumen_kelas[0]->file_dokumen))
                    <a href="{{ asset('/storage/'.pathDokumen($item->tahun_ajaran->tahun_ajaran, ismatkul($item->dokumen_perkuliahan->dikumpulkan_per), $item->dokumen_kelas[0]->kelas->matkul->nama_matkul, $item->dokumen_kelas[0]->kelas->nama_kelas).'/'.$item->dokumen_kelas[0]->file_dokumen ) }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                      <i class="fa fa-fw fa-eye"></i>
                    </a>
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="" data-bs-toggle="tooltip" title="Unduh Dokumen">
                      <i class="fa fa-fw fa-download"></i>
                    </a>
                      <input type="hidden" name="id_pengguna" value="">
                      <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    @else
                      @if ($item->pengumpulan != 0)
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="uploadDokumen({{ $key }})" data-bs-toggle="tooltip" title="Unggah Dokumen">
                      <i class="fa fa-fw fa-upload"></i>
                    </a>
                      @endif
                    @endif
                  @endif
                  
                  
                  </form>
                </td> --}}
              </tr>
              @endforeach
              
            </tbody>
          </table>
          <div class="modal fade modal-upload" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title title"></h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  action="/kelas-diampu/upload"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input type="hidden" name="id_dokumen_matkul" id="id_dokumen_matkul">
                            <input type="hidden" name="id_dokumen_kelas" id="id_dokumen_kelas">
                            <label for="example-text-input">File Dokumen</label>
                            <input
                                type="file"
                                class="form-control @error('file_dokumen') is-invalid @enderror"
                                placeholder="Masukkan File Dokumen"
                                id="file_dokumen"
                                name="file_dokumen"
                                required />
                          </div>
                          @error('file_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
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

      let jsfiles = {{ Js::from($dokumen) }};
      console.log(jsfiles);
      //modal
      function uploadDokumen(id) {
        $('.modal-upload').modal("show");
        $('.title').html('Unggah Dokumen '+jsfiles[id].dokumen_perkuliahan.nama_dokumen);
        if(jsfiles[id].dokumen_perkuliahan.dikumpulkan_per === 0) {
          $('#id_dokumen_matkul').val(jsfiles[id].dokumen_matkul[0].id_dokumen_matkul);
        } else {
          $('#id_dokumen_kelas').val(jsfiles[id].dokumen_kelas[0].id_dokumen_kelas); 
        }
      }

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