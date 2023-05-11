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

     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
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
          <h3 class="block-title">Leaderboards</h3>
        </div>
        <div class="block-content block-content-full">
          @if(isset($tahun_aktif) && auth()->user()->role != 'dosen')
            @if(in_array(auth()->user()->role, ['kaprodi', 'gkmp'])) 
              @if(auth()->user()->aktif_role->is_dosen==0)
              <form class="block-content" action="/leaderboard/badge" method="POST">
                @csrf
                <input type="hidden" name="id_tahun_ajaran" value="{{ $tahun_aktif->id_tahun_ajaran }}">
                <div class="row justify-content-center">
                  <div class="col-md-2 col-lg-4">
                    <div class="mb-4 text-center">
                      <button type="submit" class="btn btn-alt-info" id="btn-submit">
                        <i class="fa fa-fw fa-download me-1"></i> Tampilkan Perolehan Badge Final
                      </button>
                    </div>
                  </div>
                </div>
              </form>
              @endif
            @else
            <form class="block-content" action="/leaderboard/badge" method="POST">
              @csrf
              <input type="hidden" name="id_tahun_ajaran" value="{{ $tahun_aktif->id_tahun_ajaran }}">
              <div class="row justify-content-center">
                <div class="col-md-2 col-lg-4">
                  <div class="mb-4 text-center">
                    <button type="submit" class="btn btn-alt-info" id="btn-submit">
                      <i class="fa fa-fw fa-download me-1"></i> Tampilkan Perolehan Badge Final
                    </button>
                  </div>
                </div>
              </div>
            </form>
            @endif
          @endif
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Nama Dosen</th>
                <th class="text-center" >Tepat Waktu</th>
                <th class="text-center" >Telat</th>
                <th class="text-center" >Kosong</th>
                <th class="text-center" >Tugas</th>
                <th class="text-center" style="width: 15%">Persentase Pengumpulan</th>
                <th class="text-center" >Poin</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($leaderboards as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="text-center fs-sm">{{ $item->user->nama }}</td>
                <td class="text-center fs-sm">{{ $item->onTime }}</td>
                <td class="text-center fs-sm">{{ $item->late }}</td>
                <td class="text-center fs-sm">{{ $item->empty }}</td>
                <td class="text-center fs-sm">{{ $item->task }}</td>
                <td class="text-center fs-sm">
                  <div class="progress mb-1" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $item->percent. '%' }};" aria-valuenow="{{ $item->percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <p class="fs-xs fw-semibold mb-0">{{ $item->percent. ' %' }}</p>
                </td>
                {{-- <td class="text-center fs-sm">{{ $item->percent. ' %' }}</td> --}}
                <td class="text-center fs-sm">{{ $item->point }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
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
     <script src="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      $('#btn-submit').click(function (e){
           e.preventDefault();
           let form = $(this).parents('form');
           Swal.fire({
            title: 'Apakah anda sudah yakin ?',
            text: 'Anda tidak akan bisa mengubah data ini lagi!',
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'Yakin',
            denyButtonText: `Batal`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                form.submit();
              }
          });
      });
     </script>
@endsection