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
                  <form action="/kelas-diampu/dokumen/{{ $id_dokumen }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="/kelas-diampu/dokumen/{{ $id_dokumen }}?dokumen={{ $file }}" class="btn btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Lihat Dokumen" target="_blank">
                      <i class="fa fa-fw fa-eye"></i>
                    </a>
                    <a type="button" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" onclick="editNamaFile({{ $key }})" data-bs-toggle="tooltip" title="Rename">
                      <i class="fa fa-fw fa-pencil-alt"></i>
                    </a>
                    <a href="/dokumen-perkuliahan/unduh/{{ $id_dokumen }}?dokumen={{ $file }}" class="btn btn-edit btn-sm btn-alt-warning bg-success-light" data-bs-toggle="tooltip" title="Unduh Dokumen">
                      <i class="fa fa-fw fa-download"></i>
                    </a>
                    <input type="hidden" name="nama_dokumen" value="{{ $file }}">
                    <button class="btn btn-sm btn-alt-danger bg-danger-light" type="submit"  data-bs-toggle="tooltip" title="Hapus">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @php
                  $no++;
              @endphp
              @endforeach
            </tbody>
          </table>
          <div class="modal fade modal-edit" id="modal-block-fromleft" tabindex="-1" aria-labelledby="modal-block-fromleft" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Nama File Dokumen</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  
                  <form  action="/kelas-diampu/dokumen/{{ $id_dokumen }}"
                  method="POST"
                  enctype="multipart/form-data">
                   @csrf
                   @method('PUT')
                    <div class="block-content fs-sm mb-3">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="example-text-input">Nama Lama</label>
                            <input type="hidden" class="form-control" name="old_name" id="old_name">
                            <div class="form-control" id="div_old_name"></div>
                            <label for="example-text-input">Nama Baru</label>
                            <input type="text" class="form-control" name="new_name">
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
      let jsfiles = @json($nama_files);
      
      //modal
      function editNamaFile(id) {
        $('.modal-edit').modal("show");
        $('#div_old_name').html(jsfiles[id]);
        $('#old_name').val(jsfiles[id]);
      }

      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection