@extends('layouts.user-base')
@section('title', 'Dokumen ' . $nama_kelas)
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
    
    <div class="content">

      @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Dokumen {{ $nama_kelas }} </h3>
        </div>
        <div class="block-content block-content-full">
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Nama Dokumen</th>
                <th class="text-center" >Tenggat Waktu</th>
                <th class="text-center" >Status Pengumpulan</th>
                <th class="text-center" style="width: %;">Aksi</th>
              </tr>
            </thead>
            <tbody>
             
            @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->dokumen_perkuliahan->nama_dokumen }}</td>
                <td class="fs-sm">{{ showWaktu($item->tenggat_waktu) }}</td>

                @if ($item->dikumpulkan_per==0)
                  <!-- Status Pengumpulan dokumen matkul -->
                  <td class="text-center">
                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ ($item->dokumen_matkul[0]->note ==null) ? backgroundStatus($item->tenggat_waktu, $item->dokumen_matkul[0]->waktu_pengumpulan) : 'bg-danger-light text-danger' }}">{{ ($item->dokumen_matkul[0]->note ==null) ? statusPengumpulan($item->tenggat_waktu, $item->dokumen_matkul[0]->waktu_pengumpulan) : 'Dokumen ditolak' }}</span>
                  </td>
                  <td class="text-center">
                    <!-- Form Delete Dokumen Matkul -->
                    <form action="/kelas-diampu/dokumen/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" method="POST">
                      @csrf
                      @method('delete')
                      <!-- Button Download Template -->
                    @if (isset($item->dokumen_perkuliahan->template) && !isset($item->dokumen_matkul[0]->file_dokumen))
                      <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" href='/kelas-diampu/unduh-template/{{ $item->dokumen_perkuliahan->id_dokumen }}' data-bs-toggle="tooltip" title="Template Dokumen">
                        <i class="fa fa-file-lines fa-fw"></i>
                      </a>
                    @endif

                    @if ($item->dokumen_matkul[0]->note !=null)
                      <!-- Button Show Note -->
                      <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" onclick="showNote({{ $key }})" data-bs-toggle="tooltip" title="Lihat Catatan">
                        <i class="si si-note"></i>
                      </a>
                    @endif

                    @if ($item->pengumpulan == 1 && $item->dikumpul==1)
                      <!-- Button Upload dokumen Matkul Multiple -->
                      <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" onclick="uploadDokumenMultiple({{ $key }})" data-bs-toggle="tooltip" title="Unggah Dokumen">
                        <i class="fa fa-fw fa-upload"></i>
                      </a>
                    @endif
                    @if (isset($item->dokumen_matkul[0]->file_dokumen))
                        <!-- Button Show dokumen Matkul -->
                        <a href="/kelas-diampu/dokumen/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" @if($item->dikumpul==0) target="_blank" @endif>
                          <i class="fa fa-fw fa-eye"></i>
                        </a>
                        <!-- Button Unduh Dokumen matkul-->
                        <a href="/kelas-diampu/dokumen/unduh/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Unduh Dokumen">
                          <i class="fa fa-fw fa-download"></i>
                        </a>
                        <!-- Button delete dokumen Matkul -->
                        <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                          <i class="fa fa-fw fa-times"></i>
                        </button>
                    @else
                      @if ($item->pengumpulan == 1 && $item->dikumpul==0)
                        <!-- Button Upload dokumen Matkul single -->
                        <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" onclick="uploadDokumen({{ $key }})" data-bs-toggle="tooltip" title="Unggah Dokumen">
                          <i class="fa fa-fw fa-upload"></i>
                        </a>
                      @endif
                    @endif
                    </form>
                  </td>
                @else
                  <!-- Status Pengumpulan dokumen kelas -->
                  <td class="text-center">
                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ ($item->dokumen_kelas[0]->note ==null) ? backgroundStatus($item->tenggat_waktu, $item->dokumen_kelas[0]->waktu_pengumpulan) : 'bg-danger-light text-danger' }} ">{{ ($item->dokumen_kelas[0]->note ==null) ? statusPengumpulan($item->tenggat_waktu, $item->dokumen_kelas[0]->waktu_pengumpulan) : 'Dokumen ditolak' }}</span>
                  </td>
                  <td class="text-center">
                    <!-- Form Delete dokumen kelas -->
                    <form action="/kelas-diampu/dokumen/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" method="POST">
                      @csrf
                      @method('delete')
                      @if (isset($item->dokumen_perkuliahan->template) && !isset($item->dokumen_kelas[0]->file_dokumen))
                        <!-- Button Download Template -->
                        <a class="btn btn-sm btn-alt-warning bg-success-light" href='/kelas-diampu/unduh-template/{{ $item->dokumen_perkuliahan->id_dokumen }}' data-bs-toggle="tooltip" title="Template Dokumen">
                          <i class="fa fa-file-lines fa-fw"></i>
                        </a>
                      @endif

                      @if ($item->dokumen_kelas[0]->note !=null)
                        <!-- Button Show Note -->
                        <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" onclick="showNote({{ $key }})" data-bs-toggle="tooltip" title="Lihat Catatan">
                          <i class="fa-fw si si-note"></i>
                        </a>
                      @endif

                      @if ($item->pengumpulan != 0 && $item->dikumpul==1)
                        <!-- Button Upload dokumen kelas Multiple -->
                        <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" onclick="uploadDokumenMultiple({{ $key }})" data-bs-toggle="tooltip" title="Unggah Dokumen">
                          <i class="fa fa-fw fa-upload"></i>
                        </a>
                      @endif
                      @if (isset($item->dokumen_kelas[0]->file_dokumen))
                        <!-- Button Show dokumen kelas -->
                        <a href="/kelas-diampu/dokumen/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" @if($item->dikumpul==0) target="_blank" @endif>
                          <i class="fa fa-fw fa-eye"></i>
                        </a>
                        <!-- Button download dokumen kelas -->
                        <a type="button" href="/kelas-diampu/dokumen/unduh/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Unduh Dokumen">
                          <i class="fa fa-fw fa-download"></i>
                        </a>
                        <!-- Button delete dokumen kelas -->
                        <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                          <i class="fa fa-fw fa-times"></i>
                        </button>
                      @else
                        @if ($item->pengumpulan != 0 && $item->dikumpul==0)
                          <!-- Button Upload dokumen kelas single -->
                          <a type="button" class="btn btn-sm btn-alt-warning bg-success-light" onclick="uploadDokumen({{ $key }})" data-bs-toggle="tooltip" title="Unggah Dokumen">
                            <i class="fa fa-fw fa-upload"></i>
                          </a>
                        @endif
                      @endif
                    </form>
                  </td>
                @endif
              </tr>
              @endforeach
              
            </tbody>
          </table>

          <!-- Modal Note -->
          <div class="modal fade modal-note" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
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
                  <div class="block-content fs-sm mb-3">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="example-text-input">Catatan Dokumen</label>
                          <textarea class="form-control" name="note" id="note" placeholder="Catatan" disabled></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

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
                            <input type="hidden" name="id_dokumen" id="id_dokumen">
                            <label for="example-text-input">File Dokumen (Single Dokumen .pdf max: 10MB)</label>
                            <input
                                type="file"
                                class="form-control @error('file_dokumen') is-invalid @enderror"
                                placeholder="Masukkan File Dokumen"
                                id="file_dokumen"
                                name="file_dokumen"
                                required />
                              @error('file_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
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

          <div class="modal fade modal-upload-multiple" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
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
                                  
                  <form  action="/kelas-diampu/multiple-upload"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="previews"></div>
                            <input type="hidden" name="id_dokumen" id="id_dokumen_multiple">
                            <label for="example-text-input">File Dokumen <br> (Multiple Dokumen .pdf max: 10MB note: ikuti perintah penamaan file)</label>
                            <input
                                type="file"
                                class="form-control @error('file_dokumen') is-invalid @enderror"
                                placeholder="Masukkan File Dokumen"
                                id="file_dokumen"
                                name="file_dokumen[]"
                                required multiple />
                                @error('file_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
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
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/buttons.html5.min.js") }}></script>

     <!-- Page JS Code -->
     <script src={{  URL::asset("assets/js/pages/be_tables_datatables.min.js") }}></script>

     <script>

      let jsfiles = {{ Js::from($dokumen) }};
      console.log(jsfiles);
      //modal

      function showNote($id) {
        $('.modal-note').modal({
          backdrop: 'static',
          keyboard: false
        });
        $('.modal-note').modal("show");
        $('.title').html('Catatan Dokumen '+jsfiles[$id].nama_dokumen);
        if(jsfiles[$id].dikumpulkan_per === 0) {
          $('#note').html(jsfiles[$id].dokumen_matkul[0].note.isi_catatan);
        } else {
          $('#note').html(jsfiles[$id].dokumen_kelas[0].note.isi_catatan); 
        }
      }

      function uploadDokumen(id) {
        $('.modal-upload').modal({
          backdrop: 'static',
          keyboard: false
        });
        $('.modal-upload').modal("show");
        $('.title').html('Unggah Dokumen '+jsfiles[id].dokumen_perkuliahan.nama_dokumen);
        if(jsfiles[id].dikumpulkan_per === 0) {
          $('#id_dokumen').val(jsfiles[id].dokumen_matkul[0].id_dokumen_matkul);
        } else {
          $('#id_dokumen').val(jsfiles[id].dokumen_kelas[0].id_dokumen_kelas); 
        }
      }

      function uploadDokumenMultiple(id) {
        $('.modal-upload-multiple').modal({
          backdrop: 'static',
          keyboard: false
        });
        $('.modal-upload-multiple').modal("show");
        $('.title').html('Unggah Dokumen '+jsfiles[id].dokumen_perkuliahan.nama_dokumen);
        if(jsfiles[id].dikumpulkan_per === 0) {
          $('#id_dokumen_multiple').val(jsfiles[id].dokumen_matkul[0].id_dokumen_matkul);
        } else {
          $('#id_dokumen_multiple').val(jsfiles[id].dokumen_kelas[0].id_dokumen_kelas); 
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