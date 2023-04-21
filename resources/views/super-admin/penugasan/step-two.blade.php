@extends('layouts.user-base')

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
            <ul class="d-flex align-items-center justify-content-between">
                <li id="step-1" class="blue"></li>
                <li id="step-2" class="blue" ></li>
                <li id="step-3" ></li>
            </ul>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
      </div>
    </div>
    <!-- END Progres -->
    <!-- Page Content -->
    <div class="content">
      @error('jumlah')
      <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <strong>Mata kuliah beserta jumlah kelasnya belum di setel</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @enderror
      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Jumlah Kelas dan Dokumen</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form action="/penugasan/buat-penugasan-baru/store-form-ketiga" method="POST">
                @csrf
                <div class="space-y-4">
                  <h4 class="border-bottom pb-2">Mata Kuliah Yang Di Buka</h4>
                  <div class="row row-cols-lg-auto g-3 align-items-center">
                    <label class="col-lg-3 col-form-label" >Mata Kuliah</label>
                    <div class="col-md-2 col-lg-8">
                      <select class="js-select2 form-select" id="matkul_dibuka" style="width: 100%;" data-placeholder="Pilih Mata Kuliah" multiple required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($matkul as $key => $item)
                        <option value="{{ $key }}">{{ $item->nama_matkul }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row mt-3 mb-3 text-center">
                  <div class="col">
                    <button type="button" class="btn btn-primary btn-kelas">Jumlah Kelas</button>
                  </div>
                </div>

                <div id="matkul_dipilih"></div>

                <div class="row mt-5">
                  <h4 class="border-bottom pb-2">Dokumen</h4>
                  <div class="col-lg-3">
                    <label class="form-label" for="example-select2-multiple">Dokumen Di Kumpulkan</label>
                  </div>
                  <div class="col-md-2 col-lg-8">
                    <div class="mb-3">
                      <select class="js-select2 form-select @error('dokumen') is-invalid @enderror" id="example-select2-multiple" name="dokumen[]" style="width: 100%;" data-placeholder="Masukkan dokumen yang akan dikumpulkan" multiple required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($dokumen as $item)
                        <option value="{{ serialize([$item->id_dokumen, $item->nama_dokumen]) }}">{{ $item->nama_dokumen }}</option>
                        @endforeach
                      </select>
                      @error('id_dokumen')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
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
       One.helpersOnLoad([
         "jq-select2",
       ]);
     </script>

     <script>
     let matkul={{ Js::from($matkul) }};    
      $(document).ready(function(){
        $(".alert").delay(2000).fadeOut("slow");

        $(".btn-kelas").click(function(){
          let html="";
          $('#matkul_dibuka').val().forEach(element => {
            html+= `<div class="row row-cols-lg-auto g-3 align-items-center mb-3">
                        <label class="col-lg-2 col-form-label" >Mata Kuliah</label>
                        <div class="col-md-2 col-lg-4">
                          <input type="hidden" name="kode_matkul[]" value='${matkul[element].kode_matkul}' required>
                          <input type="hidden" name="nama_matkul[]" value='${matkul[element].nama_matkul}' required>
                          <div class="form-control">${matkul[element].nama_matkul}</div>
                        </div>
                        <div class="col-lg-2"></div>
                        <label class="col-lg-2 col-form-label" >Jumlah Kelas</label>
                        <div class="col-md-2 col-lg-1">
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" name="jumlah[]" min="1" required>
                        </div>
                      </div>`;
          });
          $("#matkul_dipilih").html(html);
        });
      });
     </script>
@endsection