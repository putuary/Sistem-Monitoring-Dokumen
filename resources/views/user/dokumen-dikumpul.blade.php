@extends('layouts.user-base')

@section('style')
     <!-- Stylesheets -->
     {{-- <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" /> --}}
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
     {{-- <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.css') }}">
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/dropzone/min/dropzone.min.css') }}"> --}}
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.css') }}" />
@endsection

@section('content')
    <!-- Progres -->
    <div class="bg-body-light">
      <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
          <div class="flex-grow-1">
            <h1 class="h3 fw-bold mb-2">
              Buttons
            </h1>
            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
              Custom buttons styles to fulfill any design approach.
            </h2>
          </div>
          <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-alt">
              <li class="breadcrumb-item">
                <a class="link-fx" href="javascript:void(0)">Elements</a>
              </li>
              <li class="breadcrumb-item" aria-current="page">
                Buttons
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <!-- END Progres -->

    <!-- Page Content -->
    <div class="content">
      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Dokumen Pengajaran Yang Dikumpul</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form class="space-y-4" action="be_forms_layouts.html" method="POST" onsubmit="return false;">
                <div class="row">
                  <div class="col-lg-3">
                    <label class="form-label" for="example-flatpickr-custom">Tanggal Mulai Perkuliahan</label>
                  </div>
                  <div class="col-lg-8 col-xl-5">
                    <div class="mb-4">
                      <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom" name="example-flatpickr-custom" placeholder="Masukkan tanggal mulai perkuliahan" data-date-format="j F Y" datetime-local="id">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-3">
                    <label class="form-label" for="example-select2-multiple">Dokumen Di Kumpulkan</label>
                  </div>
                  <div class="col-lg-8 col-xl-5">
                    <div class="mb-4">
                      <select class="js-select2 form-select" id="example-select2-multiple" name="example-select2-multiple" style="width: 100%;" data-placeholder="Masukkan dokumen yang akan dikumpulkan" multiple>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        <option value="1">HTML</option>
                        <option value="2">CSS</option>
                        <option value="3">JavaScript</option>
                        <option value="4">PHP</option>
                        <option value="5">MySQL</option>
                        <option value="6">Ruby</option>
                        <option value="7">Angular</option>
                        <option value="8">React</option>
                        <option value="9">Vue.js</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-center">
                    <button type="submit" class="btn btn-success">Selanjutnya</button>
                  </div>
                </div>
              </form>
              <!-- END Form Horizontal - Default Style -->
              
            </div>
          </div>
        </div>
      </div>
    </div>
          
@endsection

@section('script')
     <!-- Page JS Plugins -->
    <script src="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dropzone/min/dropzone.min.js') }}"></script> --}}

     <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
     <script>One.helpersOnLoad(["js-flatpickr", "jq-select2"]);</script>
@endsection