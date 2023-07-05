@extends('layouts.user-base')
@section('title', 'Progres Dokumen '.($kelas->matkul->nama_matkul.' '.$kelas->nama_kelas) )
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
          @if($isDownloadable)
          <div class="row">
            <div class="col-md-2 col-lg-4">
              <div class="mb-4 text-start">
                <a href="/progres-pengumpulan/kelas/unduh-dokumen-kelas/{{ $kelas->kode_kelas }}" class="btn btn-alt-info">
                  <i class="fa fa-fw fa-download me-1"></i> Unduh Semua Dokumen
                </a>      
              </div>
            </div>
          </div>
          @endif
  
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Nama Dokumen</th>
                <th class="text-center" >Tenggat Waktu</th>
                <th class="text-center" >Waktu Pengumpulan</th>
                <th class="text-center" >Status Pengumpulan</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
             
              @foreach ($dokumen as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->nama_dokumen }}</td>
                <td class="fs-sm text-center">{{ showWaktu($item->tenggat_waktu) }}</td>
                <td class="fs-sm text-center">
                  @if ($item->dikumpulkan_per==0)
                  {{ showWaktu($item->dokumen_matkul[0]->waktu_pengumpulan) }}
                  @else
                  {{ showWaktu($item->dokumen_kelas[0]->waktu_pengumpulan) }}
                  @endif
                </td>
                @if ($item->dikumpulkan_per==0)
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ backgroundStatus($item->tenggat_waktu, $item->dokumen_matkul[0]->waktu_pengumpulan) }} ">{{ statusPengumpulan($item->tenggat_waktu, $item->dokumen_matkul[0]->waktu_pengumpulan) }}</span>
                </td>
                <td class="text-center">
                  @if (isset($item->dokumen_matkul[0]->file_dokumen))
                    <a href="/progres-pengumpulan/kelas/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" class="btn btn-sm btn-alt-success bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" @if($item->dikumpul==0) target="_blank" @endif>
                      <i class="fa fa-fw fa-eye"></i>
                    </a>
                    <a href="/progres-pengumpulan/kelas/unduh/{{ $item->dokumen_matkul[0]->id_dokumen_matkul }}" class="btn btn-sm btn-alt-info bg-info-light" data-bs-toggle="tooltip" title="Unduh Dokumen">
                      <i class="fa fa-fw fa-download"></i>
                    </a>
                    @if(auth()->user()->role!='admin')
                      <button class="btn btn-sm btn-alt-danger bg-danger-light" onclick="refuseDokumen({{ $key }})" type="button"  data-bs-toggle="tooltip" title="Tolak Dokumen">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    @endif
                  @endif
                @else
                <td class="text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success {{ $item->dokumen_kelas[0]->waktu_pengumpulan ? backgroundStatus($item->tenggat_waktu, $item->dokumen_kelas[0]->waktu_pengumpulan) : 'bg-warning-light text-warning' }} ">{{ $item->dokumen_kelas[0]->waktu_pengumpulan ? statusPengumpulan($item->tenggat_waktu, $item->dokumen_kelas[0]->waktu_pengumpulan) : 'Belum Dikumpulkan' }}</span>
                </td>
                <td class="text-center">
                  @if (isset($item->dokumen_kelas[0]->file_dokumen))
                  <a href="/progres-pengumpulan/kelas/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" class="btn btn-sm btn-alt-success bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" @if($item->dikumpul==0) target="_blank" @endif>
                    <i class="fa fa-fw fa-eye"></i>
                  </a>
                  <a href="/progres-pengumpulan/kelas/unduh/{{ $item->dokumen_kelas[0]->id_dokumen_kelas }}" class="btn btn-sm btn-alt-info bg-info-light" onclick="" data-bs-toggle="tooltip" title="Unduh Dokumen">
                    <i class="fa fa-fw fa-download"></i>
                  </a>
                  @if(auth()->user()->role!='admin')
                    <button class="btn btn-sm btn-alt-danger bg-danger-light" onclick="refuseDokumen({{ $key }})" type="button"  data-bs-toggle="tooltip" title="Tolak Dokumen">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  @endif
                  @endif
                @endif
              </tr>
              @endforeach
              
            </tbody>
          </table>
          <div class="modal fade modal-catatan" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title title">Penolakan Dokumen</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  action="/progres-pengumpulan/dokumen/catatan"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input type="hidden" name="id_dokumen_terkumpul" id="id_dokumen_terkumpul">
                            <label for="example-text-input">Catatan</label>
                            <textarea class="form-control @error('isi_catatan') is-invalid @enderror" placeholder="Masukkan Catatan Penolakan" name="isi_catatan" required ></textarea>
                          </div>
                          @error('isi_catatan')
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
                        <i class="fa fa-check me-1"></i>Submit
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

     <!-- Page JS Plugins -->
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      One.helpersOnLoad([
        "jq-select2",
      ]);
    </script>
     <script>

      let dokumen = {{ Js::from($dokumen) }};
     
      //modal
      function refuseDokumen(key) {
        // console.log(dokumen[key]);
        $('.modal-catatan').modal({backdrop: 'static', keyboard: false});
        $('.modal-catatan').modal("show");

        if(dokumen[key].dikumpulkan_per===0) {
          $('#id_dokumen_terkumpul').val(dokumen[key].dokumen_matkul[0].id_dokumen_matkul);
        } else {
          $('#id_dokumen_terkumpul').val(dokumen[key].dokumen_kelas[0].id_dokumen_kelas);
        }
      }

      $(document).ready(function() {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection