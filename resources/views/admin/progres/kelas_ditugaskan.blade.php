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

      @if (session()->has('failed'))
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>{{ session()->get('failed') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Dokumen {{ $kelas->matkul->nama_matkul.' '.$kelas->nama_kelas }} </h3>
        </div>
        <div class="block-content block-content-full">
          <form class="block-content" action="/progres-pengumpulan/kelas" method="POST">
            @csrf
            <input type="hidden" name="id_tahun_ajaran" value="{{ $dokumen[0]->id_tahun_ajaran }}">
            <input type="hidden" name="nama_matkul" value="{{ $kelas->matkul->nama_matkul }}">
            <input type="hidden" name="nama_kelas" value="{{ $kelas->nama_kelas }}">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-4">
                <div class="mb-4 text-center">
                  <button type="submit" class="btn btn-alt-info">
                    <i class="fa fa-fw fa-download me-1"></i> Unduh Semua Dokumen
                  </button>      
                </div>
              </div>
            </div>
          </form>
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Nama Dokumen</th>
                <th class="text-center" >Waktu Pengumpulan</th>
                <th class="text-center" >Status Pengumpulan</th>
                <th class="text-center" style="width: %;">Aksi</th>
              </tr>
            </thead>
            <tbody>
             
              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->nama_dokumen }}</td>
                <td class="fs-sm text-center">
                  @if ($item->dikumpulkan_per==0)
                  {{ showWaktu($item->dokumen_matkul[0]->waktu_pengumpulan) }}
                  @else
                  {{ showWaktu($item->dokumen_kelas[0]->waktu_pengumpulan) }}
                  @endif
                </td>
                @if ($item->dikumpulkan_per==0)
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->dokumen_matkul[0]->waktu_pengumpulan ? backgroundStatus($item->tenggat_waktu, $item->dokumen_matkul[0]->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->dokumen_matkul[0]->waktu_pengumpulan ? statusPengumpulan($item->tenggat_waktu, $item->dokumen_matkul[0]->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
                </td>
                <td class="text-center">
                  <form action="/progres-pengumpulan/kelas/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" method="POST">
                    @csrf
                    @method('DELETE')
                    @if (isset($item->dokumen_matkul[0]->file_dokumen))
                      <a href="/progres-pengumpulan/kelas/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" @if($item->dikumpul==0) target="_blank" @endif>
                        <i class="fa fa-fw fa-eye"></i>
                      </a>
                      <a href="/progres-pengumpulan/kelas/unduh/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Unduh Dokumen">
                        <i class="fa fa-fw fa-download"></i>
                      </a>
                      <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    @endif
                @else
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->dokumen_kelas[0]->waktu_pengumpulan ? backgroundStatus($item->tenggat_waktu, $item->dokumen_kelas[0]->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->dokumen_kelas[0]->waktu_pengumpulan ? statusPengumpulan($item->tenggat_waktu, $item->dokumen_kelas[0]->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
                </td>
                <td class="text-center">
                  <form action="/progres-pengumpulan/kelas/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" method="POST">
                    @csrf
                    @method('DELETE')
                    @if (isset($item->dokumen_kelas[0]->file_dokumen))
                    <a href="/progres-pengumpulan/kelas/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" @if($item->dikumpul==0) target="_blank" @endif>
                      <i class="fa fa-fw fa-eye"></i>
                    </a>
                    <a href="/progres-pengumpulan/kelas/unduh/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="" data-bs-toggle="tooltip" title="Unduh Dokumen">
                      <i class="fa fa-fw fa-download"></i>
                    </a>
                      <input type="hidden" name="id_pengguna" value="">
                      <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    @endif
                @endif
                  
                  
                  </form>
                </td>
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
        $('.title').html('Unggah Dokumen '+jsfiles[id].nama_dokumen);
        if(jsfiles[id].dikumpulkan_per === 0) {
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
        $(".alert").delay(2000).fadeOut("slow");
        $(".button-tambah-pengguna").on("click", function () {
          $("#modal-tambah-pengguna").modal("show");
        });

        $(".modal-edit").attr("id", "modal-edit");
      });
    </script>
@endsection