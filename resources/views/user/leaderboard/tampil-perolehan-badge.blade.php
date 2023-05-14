@extends('layouts.user-base')

@section('title', 'Badge')

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
      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Leaderboards</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="block-content">
            <div class="row justify-content-center">
              <div class="col-md-2 col-lg-3">
                <form action="/badge">
                  <div class="mb-4 d-flex">
                    <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                    <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                    <select class="js-select2 form-select" id="one-ecom-product-category" name="tahun_ajaran" style="width: 100%;" data-placeholder="Choose one..">
                      <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                      @foreach ($tahun_ajaran as $item)
                      <option value="{{ $item->id_tahun_ajaran }}"@selected((request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }}</option>
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
          @if(isset($tahun_aktif) && in_array(auth()->user()->role, ['kaprodi', 'gkmp']))
            @if (request('tahun_ajaran') ? (request('tahun_ajaran') == $tahun_aktif->id_tahun_ajaran ? true :false) : true) 
              @if(auth()->user()->aktif_role->is_dosen==0)
              <form class="row" action="/leaderboard/badge" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id_tahun_ajaran" value="{{ $tahun_aktif->id_tahun_ajaran }}">
                <div class="col-md-2 col-lg-4">
                  <div class="mb-4 text-start">
                    <button type="submit" class="btn btn-alt-info" id="btn-submit">
                      <i class="fa fa-fw fa-delete-left me-1"></i> Hapus Perolehan Badge
                    </button>
                  </div>
                </div>
              </form>
              @endif
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
                <th class="text-center" >Poin</th>
                <th class="text-center" >Badge</th>

              </tr>
            </thead>
            <tbody>
              @foreach ($user_badges as $key => $item)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="text-center fs-sm">{{ $item->user->nama ?? null }}</td>
                <td class="text-center fs-sm">{{ $item->onTime }}</td>
                <td class="text-center fs-sm">{{ $item->late }}</td>
                <td class="text-center fs-sm">{{ $item->empty }}</td>
                <td class="text-center fs-sm">{{ $item->point }}</td>
                <td class="text-center">
                  @foreach ($item->user->user_badge as $badge)
                  <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('storage/badges/'.$badge->gambar) }}" alt="">
                  @endforeach
                </td>
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
     <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
    <script>
      One.helpersOnLoad([
        "jq-select2",
      ]);
    </script>

    <script>
      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
      
     </script>
@endsection