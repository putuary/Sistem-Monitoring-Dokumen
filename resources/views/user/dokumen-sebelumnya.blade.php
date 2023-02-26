@extends('layouts.user-base')

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/dropzone/min/dropzone.min.css') }}">
@endsection

@section('content')
    <!-- Hero -->
    <div class="content">
      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif
      <div class="block-content">
        <div class="row justify-content-center">
          <div class="col-md-2 col-lg-3">
            <div class="mb-4 d-flex">
              <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
              <!-- For more info and examples you can check out https://github.com/select2/select2 -->
              <select class="js-select2 form-select" id="one-ecom-product-category" name="one-ecom-product-category" style="width: 100%;" data-placeholder="Choose one..">
                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                <option value="1">2020/2021 Genap</option>
                <option value="2" selected>Video Games</option>
                <option value="3">Tablets</option>
                <option value="4">Laptops</option>
                <option value="5">PC</option>
                <option value="6">Home Cinema</option>
                <option value="7">Sound</option>
                <option value="8">Office</option>
                <option value="9">Adapters</option>
              </select>
              <button class="input-group-text">
                <i class="fa fa-fw fa-search"></i>
              </button>                
            </div>
          </div>
        </div>
      </div>



      <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
          <div class="mt-3 mt-md-0">
            <button type="button" class="btn btn-info">
              <i class="fa fa-fw fa-download me-1"></i> Unduh Semua Dokumen
            </button>
          </div>
        
        <div class="mt-md-0 ms-md-3 space-x-1">
          <div class="dropdown d-inline-block">
            <button type="button" class="btn btn-sm btn-alt-secondary space-x-1" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-fw fa-calendar-alt opacity-50"></i>
              <span>Kelas</span>
              <i class="fa fa-fw fa-angle-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
              <a class="dropdown-item fw-medium" href="javascript:void(0)">Dokumen</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                <span>Kelas</span>
                <i class="fa fa-check"></i>
              </a>
            </div>
          </div>
          {{-- <a class="btn btn-sm btn-alt-secondary space-x-1" href="be_pages_generic_profile_edit.html">
            <i class="fa fa-cogs opacity-50"></i>
            <span>Settings</span>
          </a> --}}
          <div class="btn btn-sm space-x-1">
            <div class="input-group input-group-sm">
              <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="page-header-search-input2">
              <button class="input-group-text">
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

        <!-- Progres Mata Kuliah -->
        <div class="col-sm-6 col-xxl-3">
          <a class="block block-rounded d-flex flex-column h-100 mb-0" href="/">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0">
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Terkumpul <span class="text-success">5</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Telat <span class="text-danger">5</span>
                </dd>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Ditugaskan <span class="text-primary">5</span>
                </dd>
              </dl>
              <div class="item item-2x item-circle bg-body-light">
                <!-- Pie Chart Container -->
                <div class="js-pie-chart pie-chart" data-percent="100" data-line-width="3" data-size="100" data-bar-color="#fadb7d" data-track-color="#eeeeee" data-scale-color="#dddddd">
                  <span>45%</span>
                </div>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Algoritma Pemrograman RA</span>
              </div>
            </div>
          </a>
        </div>
        <!-- END Progres Mata Kuliah -->

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

    <!-- Page JS Code -->
    <script src={{ URL::asset("assets/js/pages/be_comp_charts.min.js") }}></script>

    <!-- Page JS Helpers (Easy Pie Chart + jQuery Sparkline Plugins) -->
    <script>One.helpersOnLoad(['jq-easy-pie-chart', 'jq-sparkline']);</script>

    <!-- Page JS Plugins -->
    <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/select2/js/select1.full.min.js") }}></script>

    <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

    <script>
      One.helpersOnLoad([
        "jq-select2",
      ]);
    </script>
@endsection