@extends('layouts.user-base')

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <!-- Hero -->
    <div class="content">
      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <form action="/kelas-diampu">
        <div class="block-content">
          <div class="row justify-content-center">
            <div class="col-md-2 col-lg-3">
              <div class="mb-4 d-flex">
                <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                <select class="js-select2 form-select" id="one-ecom-product-category" name="tahun_ajaran" style="width: 100%;" data-placeholder="Pilih Tahun Ajaran ....">
                  <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                  @foreach ($tahun_ajaran as $item)
                  <option value={{ $item->id_tahun_ajaran }} @selected((request('tahun_ajaran') ?? $tahun_aktif->id_tahun_ajaran) == $item->id_tahun_ajaran)>{{ $item->tahun_ajaran }} </option>
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

      <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-center py-2 text-center text-md-start">
          {{-- <div class="mt-3 mt-md-0">
            <button type="button" class="btn btn-info">
              <i class="fa fa-fw fa-download me-1"></i> Unduh Semua Dokumen
            </button>
          </div> --}}
        
        <div class="mt-md-0 ms-md-3 space-x-1">
          
          {{-- <a class="btn btn-sm btn-alt-secondary space-x-1" href="be_pages_generic_profile_edit.html">
            <i class="fa fa-cogs opacity-50"></i>
            <span>Settings</span>
          </a> --}}
          <div class="btn btn-sm space-x-1">
            <div class="input-group input-group-sm">
              <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="page-header-search-input2">
              <button class="input-group-text border-0">
                <i class="fa fa-fw fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
      <!-- Overview -->
      <div class="row items-push">

        @foreach ($kelas as $item)
        @php
          $status = kelasSummary($item->dokumen_kelas, $item->kelas_dokumen_matkul);
          // dd($status);
        @endphp
            <!-- Progres Mata Kuliah -->
        <div class="col-sm-6 col-xxl-3">
          <a class="block block-rounded d-flex flex-column h-100 mb-0" href="/kelas-diampu/{{ $item->kode_kelas }}">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0">
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Terlewat <span class="text-danger">{{ $status->terlewat }}</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Telat <span class="text-danger">{{ $status->telat }}</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Terkumpul <span class="text-success">{{ $status->terkumpul }}</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Ditugaskan <span class="text-primary">{{ $status->ditugaskan }}</span>
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
                <span>{{ $item->matkul->nama_matkul.' '.$item->nama_kelas }}</span>
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
    <script src={{ URL::asset("assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js") }}></script>
    <script src={{ URL::asset("assets/js/plugins/chart.js/chart.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>
    
    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
    <script>One.helpersOnLoad(["jq-select2"]);</script>

    <!-- Page JS Code -->
    <script src={{ URL::asset("assets/js/pages/be_comp_charts.min.js") }}></script>

    <!-- Page JS Helpers (Easy Pie Chart + jQuery Sparkline Plugins) -->
    <script>One.helpersOnLoad(['jq-easy-pie-chart', 'jq-sparkline']);</script>
    <script>
      $(document).ready(function(){
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection