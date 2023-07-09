@extends('layouts.user-base')
@section('title', 'Buat Penugasan Baru')

@section('style')
     <!-- Stylesheets -->
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.css') }}" />
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">

@endsection

@section('content')
    <!-- Progres -->
    <div class="bg-body-light">
      <div class="content content-full">
          <div class="progresses py-4">
            <ul class="d-flex align-items-center justify-content-between row">
                <li id="step-1" class="blue"></li>
                <li id="step-2" ></li>
                <li id="step-3" ></li>
            </ul>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
      </div>
    </div>
    <!-- END Progres -->

    <!-- Page Content -->
    <div class="content">

      @if (session()->has('failed'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
          <strong>{{ session()->get('failed') }}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Pembukaan Tahun Ajaran</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form action="/penugasan/buat-penugasan-baru/store-form-kedua" method="POST">
                @csrf
                <div class="space-y-4">
                  <h4 class="border-bottom pb-2">Tahun Ajaran Baru</h4>
                  <div class="row">
                    <div class="col-lg-3">
                      <label class="form-label">Tahun Ajaran</label>
                    </div>
                    <div class="col-lg-2 col-xl-4">
                      <div class="mb-4">
                        <div class="input-daterange input-group">
                          <input type="text" class="js-datepicker form-control" id="tahun1" name="tahun1" placeholder="Dari" value="{{ createTahunAjaran('dari') }}" required>
                          <span class="input-group-text fw-semibold">
                            /
                          </span>
                          <input type="text" class="js-datepicker form-control" id="tahun2" name="tahun2" placeholder="Ke" value="{{ createTahunAjaran('ke') }}" required>
                          <select class="js-select2 form-select" name="jenis" data-placeholder="Jenis" required>
                            <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                            <option value="Ganjil" @selected(createTahunAjaran('jenis') == 'Ganjil')>Ganjil</option>
                            <option value="Genap" @selected(createTahunAjaran('jenis') == 'Genap')>Genap</option>
                            <option value="Pendek" @selected(createTahunAjaran('jenis') == 'Pendek')>Pendek</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3">
                      <label class="form-label" for="example-flatpickr-custom">Tanggal Mulai Perkuliahan</label>
                    </div>
                    <div class="col-lg-8 col-xl-4">
                      <div class="mb-3">
                        <input type="text" class="js-flatpickr form-control @error('tanggal_mulai_kuliah') is-invalid @enderror"  name="tanggal_mulai_kuliah" placeholder="Masukkan tanggal mulai perkuliahan" data-date-format="j F Y" data-min-date="today" value="{{ old('tanggal_mulai_kuliah') }}" required>
                        @error('tanggal_mulai_kuliah')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-5 text-end me-4 mb-3">
                  <div class="col">
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
  <script src="{{ URL::asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>
  <script src="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

  <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->

  <script>

   $("#tahun1").datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      startDate: (new Date().getFullYear() -1 ).toString(),
      endDate: new Date().getFullYear().toString(),
    });

    $("#tahun2").datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      startDate: new Date().getFullYear().toString(),
      endDate: (new Date().getFullYear() +1 ).toString(),
    });
  
    One.helpersOnLoad([
    "js-flatpickr",
      "jq-select2",
    ]);
  </script>

  <script>
    $(document).ready(function() {
      $(".alert").delay(2000).fadeOut("slow");
      $('#tahun1').on('change', function() {
        var tahun1 = $(this).val();
        var tahun2 = parseInt(tahun1) + 1;
        $('#tahun2').val(tahun2);
      });

      $('#tahun2').on('change', function() {
        var tahun2 = $(this).val();
        var tahun1 = parseInt(tahun2) - 1;
        $('#tahun1').val(tahun1);
      });
    });
  </script>

@endsection