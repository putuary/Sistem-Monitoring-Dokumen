@extends('layouts.user-base')
@section('title', 'Dokumen '.$title)
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
    <div class="content">
       <!-- pop up success upload -->
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
          <h3 class="block-title">Dokumen {{ $title }}</h3>
        </div>
        <div class="block-content block-content-full">
         
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Nama File Dokumen</th>
                <th class="text-center" >Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              @foreach ($nama_files as $key => $file)
              <tr>
                <td class="text-center fs-sm">{{ $no }}</td>
                <td class="fs-sm">{{ $file }}</td>
                <td class="text-center">
                  <a href="/progres-pengumpulan/kelas/{{ $id_dokumen }}?dokumen={{ $file }}" class="btn btn-sm btn-alt-success bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                    <i class="fa fa-fw fa-eye"></i>
                  </a>
                  <a href="/progres-pengumpulan/kelas/unduh/{{ $id_dokumen }}?dokumen={{ $file }}" class="btn btn-sm btn-alt-info bg-info-light" data-bs-toggle="tooltip" title="Unduh Dokumen">
                    <i class="fa fa-fw fa-download"></i>
                  </a>
                  
                  @if(auth()->user()->role!='admin')
                  <button class="btn btn-sm btn-alt-danger bg-danger-light" type="button" onclick="refuseDokumen({{ $key }})"  data-bs-toggle="tooltip" title="Tolak Dokumen">
                    <i class="fa fa-fw fa-times"></i>
                  </button>
                  @endif
              </tr>
              @php
                  $no++;
              @endphp
              @endforeach
            </tbody>
          </table>
          <div class="modal fade modal-catatan" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title title">Catatan Penolakan Dokumen</h3>
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
                            <input type="hidden" name="nama_dokumen" id="nama_dokumen">
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

     <script>
       let dokumen = @json($nama_files);
      
      function refuseDokumen(key) {
        $('.modal-catatan').modal({backdrop: 'static', keyboard: false});
        $('.modal-catatan').modal("show");

        $('#id_dokumen_terkumpul').val('{{ $id_dokumen }}');
        
        $('#nama_dokumen').val(dokumen[key]);
      }

      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection