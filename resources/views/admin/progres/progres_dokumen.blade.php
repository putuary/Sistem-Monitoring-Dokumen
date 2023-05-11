@extends('layouts.user-base')
@section('style')
  <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <!-- Page Content -->
    <div class="content">

       <!-- pop up success upload -->
      @if (session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show mb-3 alert-notification" role="alert">
          <strong>{{ session()->get('success') }}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      @if (session()->has('failed'))
          <div class="alert alert-danger alert-dismissible fade show mb-3 alert-notification" role="alert">
            <strong>{{ session()->get('failed') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      {{-- <div class="row">
        <div class="col-md mb-4 text-center">
          <span class="p-2 btn-outline-dark bg-white rounded text-dark fw-semibold">TA : {{ $kelas[0]->tahun_ajaran->tahun_ajaran ?? '-' }}</span>
        </div>
      </div> --}}
      {{-- @php
          dd($kelas[0]->tahun_ajaran->id_tahun_ajaran);
      @endphp --}}

      <form action="/progres-pengumpulan">
        <div class="block-content">
          <div class="row justify-content-center">
            <div class="col-md-2 col-lg-3">
              <div class="mb-4 d-flex">
                @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                <select class="js-select2 form-select" id="one-ecom-product-category" name="tahun_ajaran" style="width: 100%;" data-placeholder="Pilih Tahun Ajaran ....">
                  <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                  @foreach ($tahun_ajaran as $item)
                  <option value={{ $item->id_tahun_ajaran }} @selected((request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran)==$item->id_tahun_ajaran)>{{ $item->tahun_ajaran }} </option>
                  @endforeach
                </select>
                <button class="input-group-text" type="submit">
                  <i class="fa fa-fw fa-search"></i>
                </button>                
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start mb-3">
        <form class="mt-3 mt-md-0" action="/progres-pengumpulan/unduh-semua-dokumen" method="POST">
          @csrf
          <input type="hidden" name="id_tahun_ajaran" value="{{ ($tahun_aktif != null) ? (request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) : '' }}">

          <button type="submit" class="btn btn-info">
            <i class="fa fa-fw fa-download me-1"></i> Unduh Semua Dokumen
          </button>
        </form>

        <div class="mt-3 mt-md-0">
          <a class="btn btn-success" href="/progres-pengumpulan/resume-pengumpulan?tahun_ajaran={{ ($tahun_aktif != null) ? (request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) : '' }}">
            <i class="fa fa-fw fa-rectangle-list me-1"></i> Tampilkan Resume Pengumpulan
          </a>
        </div>
        
        <div class="mt-md-0 ms-md-3 space-x-1">
          <div class="dropdown d-inline-block">
            <button type="button" class="btn btn-sm btn-alt-secondary space-x-1" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-fw fa-file-lines opacity-50"></i>
              <span>@if(request('filter') == null || request('filter') == 'kelas') {{ 'Kelas' }} @elseif(request('filter') != null && request('filter') == 'dokumen') {{ 'Dokumen' }} @endif</span>
              <i class="fa fa-fw fa-angle-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
              <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="{{ request('tahun_ajaran') ? '/progres-pengumpulan?filter=kelas&tahun_ajaran='.request('tahun_ajaran') : '/progres-pengumpulan?filter=kelas' }}">
                <span>Kelas</span>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between">
                <span>Dokumen</span>
                <i class="fa fa-check"></i>
              </a>
            </div>
          </div>
          {{-- <a class="btn btn-sm btn-alt-secondary space-x-1" href="be_pages_generic_profile_edit.html">
            <i class="fa fa-cogs opacity-50"></i>
            <span>Settings</span>
          </a> --}}
          <form class="btn btn-sm space-x-1" id="search-form" method="GET">
            <div class="input-group input-group-sm">
              <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="search">
              <button type="submit" class="input-group-text border-0">
                <i class="fa fa-fw fa-search"></i>
              </button>
            </div>
          </form>
        </div>
      </div>

      @if (count($dokumen) == 0)
      <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Dokumen tidak ditemukan!</h4>
        <p>Maaf, Dokumen tidak ada di dalam database.</p>
      </div>
      @endif

      <!-- Overview -->
      <div class="row items-push">

        @foreach ($dokumen as $item)

        <?php
          $status = dokumenSummary($item);
        ?>
            <!-- Progres Mata Kuliah -->
        <div class="col-sm-6 col-xxl-3">
          <a class="block block-rounded d-flex flex-column h-100 mb-0" href="/progres-pengumpulan/dokumen?id={{ $item->id_dokumen_ditugaskan }}">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0 text-justify">
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0 d-flex align-items-center justify-content-between">
                  Terlewat <span class="badge bg-danger rounded-pill"> {{ $status->terlewat }}</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0 d-flex align-items-center justify-content-between">
                  Telat <span class="badge bg-warning rounded-pill"> {{ $status->telat }}</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0 d-flex align-items-center justify-content-between">
                  Terkumpul <span class="badge bg-success rounded-pill"> {{ $status->terkumpul }}</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0 d-flex align-items-center justify-content-between">
                  Ditugaskan <span class="badge bg-info rounded-pill"> {{ $status->ditugaskan }}</span>
                </dd>
              </dl>
              <div class="item item-2x item-circle bg-body-light">
                <!-- Pie Chart Container -->
                <div class="js-pie-chart pie-chart" data-percent={{ $status->persentase_dikumpul }} data-line-width="3" data-size="100" data-bar-color="#fadb7d" data-track-color="#eeeeee" data-scale-color="#dddddd">
                  <span>{{ $status->persentase_dikumpul.'%' }}</span>
                </div>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>{{ $item->nama_dokumen }}</span>
              </div>
            </div>
          </a>
        </div>
        <!-- END Progres Mata Kuliah -->
        @endforeach
        

      </div>
      <!-- END Overview -->
    </div>
    <!-- END Page Content -->
    
@endsection

@section('script')
    <!-- Page JS Plugins -->
    <script src={{ URL::asset("assets/js/plugins/easy-pie-chart/jquery.easypiechart.min.js") }}></script>
    <script src={{ URL::asset("assets/js/plugins/chart.js/chart.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>
    
    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
    <script>One.helpersOnLoad(["jq-select2"]);</script>

    <!-- Page JS Code -->
    <script src={{ URL::asset("assets/js/pages/be_comp_charts.min.js") }}></script>

    <!-- Page JS Helpers (Easy Pie Chart + jQuery Sparkline Plugins) -->
    <script>One.helpersOnLoad(['jq-easy-pie-chart']);</script>

    <script>
      $(document).ready(function () {
        $(".alert-notification").delay(2000).fadeOut("slow");

        $('#search-form').submit(function(e) {
            // Mencegah aksi default dari form
            e.preventDefault();

            // Mengambil nilai query dari input
            var search = $('input[name="search"]').val();
            var tahun_ajaran={{ ($tahun_aktif != null) ? (request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) : '' }}
            var filter='{{ request('filter') ?? '' }}';

            // Melakukan request Ajax ke server
            $.ajax({
                url: '/progres-pengumpulan',
                method: 'GET',
                data: {
                    tahun_ajaran: tahun_ajaran,
                    filter: filter,
                    search: search 
                  },
                success: function(response) {
                    // Menampilkan hasil pencarian pada halaman
                    $('body').html(response);
                    // $('#search-results').html(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
          });
      });
    </script>
@endsection