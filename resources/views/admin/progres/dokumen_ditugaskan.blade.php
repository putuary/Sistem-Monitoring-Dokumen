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
          <h3 class="block-title">Dokumen {{ $dokumen->nama_dokumen ?? 'Undefine' }} </h3>
        </div>
        <div class="block-content block-content-full">
          <form class="block-content" action="/progres-pengumpulan/dokumen" method="POST">
            @csrf
            <input type="hidden" name="nama_dokumen" value="{{ $dokumen->nama_dokumen }}">
            <input type="hidden" name="id_tahun_ajaran" value="{{ $dokumen->id_tahun_ajaran }}">
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
                <th class="text-center" >{{ $dokumen->dikumpulkan_per==0 ? 'Mata Kuliah' : 'Kelas' }}</th>
                <th class="text-center" >waktu_pengumpulan</th>
                <th class="text-center" >Status</th>
                <th class="text-center" >Status Pengumpulan</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @if ($dokumen->dikumpulkan_per==0)

                @foreach ($dokumen->dokumen_matkul as $key => $item)
                <tr>
                  <td class="text-center fs-sm">{{ $key+1 }}</td>
                  <td class="fs-sm">{{ $item->matkul->nama_matkul }}</td>
                  <td class="fs-sm text-center">{{ showWaktu($item->waktu_pengumpulan) }}</td>
                  <td class="text-center fw-semibold fs-sm">
                    <div class="btn-sm btn-alt-{{ isset($item->file_dokumen) ? 'success' : 'danger' }} bg-{{ isset($item->file_dokumen) ? 'success' : 'danger' }}-light">
                      <i class="far fa-fw fa-{{ isset($item->file_dokumen) ? 'square-check' : 'rectangle-xmark' }}"></i>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->waktu_pengumpulan ? backgroundStatus($dokumen->tenggat_waktu, $item->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->waktu_pengumpulan ? statusPengumpulan($dokumen->tenggat_waktu, $item->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
                  </td>
                  <td class="text-center">
                    <form action="/manajemen-pengguna/delete" method="POST">
                      @csrf
                      @if (isset($item->file_dokumen))
                        <a href="{{ asset('/storage'.pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, ismatkul($dokumen->dikumpulkan_per), $item->matkul->nama_matkul ).'/'.$item->file_dokumen ) }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                          <i class="fa fa-fw fa-eye"></i>
                        </a>
                        <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="" data-bs-toggle="tooltip" title="Unduh Dokumen">
                          <i class="fa fa-fw fa-download"></i>
                        </a>
                          <input type="hidden" name="id_pengguna" value="">
                          <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                            <i class="fa fa-fw fa-times"></i>
                          </button>
                      @endif
                    </form>
                  </td>
                </tr>
                @endforeach
              @else
                @foreach ($dokumen->dokumen_kelas as $key => $item)
                <tr>
                  <td class="text-center fs-sm">{{ $key+1 }}</td>
                  <td class="fs-sm">{{ $item->kelas->matkul->nama_matkul.' '.$item->kelas->nama_kelas }}</td>
                  <td class="fs-sm text-center">{{ showWaktu($item->waktu_pengumpulan) }}</td>
                  <td class="text-center fw-semibold fs-sm">
                    <div class="btn-sm btn-alt-{{ isset($item->file_dokumen) ? 'success' : 'danger' }} bg-{{ isset($item->file_dokumen) ? 'success' : 'danger' }}-light">
                      <i class="far fa-fw fa-{{ isset($item->file_dokumen) ? 'square-check' : 'rectangle-xmark' }}"></i>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->waktu_pengumpulan ? backgroundStatus($dokumen->tenggat_waktu, $item->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->waktu_pengumpulan ? statusPengumpulan($dokumen->tenggat_waktu, $item->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
                  </td>
                  <td class="text-center">
                    <form action="/manajemen-pengguna/delete" method="POST">
                      @csrf
                      @if (isset($item->file_dokumen))
                        <a href="{{ asset('/storage/'.pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, ismatkul($dokumen->dikumpulkan_per), $item->kelas->matkul->nama_matkul, $item->kelas->nama_kelas).'/'.$item->file_dokumen ) }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                          <i class="fa fa-fw fa-eye"></i>
                        </a>
                        <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="" data-bs-toggle="tooltip" title="Unduh Dokumen">
                          <i class="fa fa-fw fa-download"></i>
                        </a>
                          <input type="hidden" name="id_pengguna" value="">
                          <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                            <i class="fa fa-fw fa-times"></i>
                          </button>
                      @endif
                    </form>
                  </td>
                </tr>
                @endforeach
              @endif
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
      $(document).ready(function() {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection