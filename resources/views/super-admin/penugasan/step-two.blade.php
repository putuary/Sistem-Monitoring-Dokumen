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
                  <div class="row row-cols-lg-auto align-items-center">
                    <label class="col-lg-3 col-form-label fw-bold" >Pilih Mata Kuliah Dibuka</label>
                    <div class="col-md-2 col-lg-8">
                      <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start mb-3">
                        <div class="mt-3 mt-md-0">
                          <button class="btn btn-success btn-all" type="button">
                            <i class="fa fa-fw fa-check me-1"></i> Semua
                          </button>
                          <button class="btn btn-danger btn-reset" type="button">
                            <i class="fa-fw si si-refresh me-1"></i> Reset
                          </button>
                        </div>
                      </div>
                    
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                          <thead>
                            <tr>
                              <th class="text-center" style="width: 85%;">Mata Kuliah</th>
                              <th class="text-center">Jumlah Kelas</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($matkul as $key => $item)
                              <tr>
                                <td>
                                  <div class="form-check matkul-dibuka">
                                    <input class="form-check-input form-matkul-dibuka" type="checkbox" value="{{ $item->kode_matkul }}" id="checkbox_{{ $key }}" name="kode_matkul[]">
                                    <label class="form-check-label" for="checkbox_{{ $key }}">{{ $item->nama_matkul }}</label>
                                  </div>
                                </td>
                                <td>
                                  <div class="jumlah_kelas_container">
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      
                      {{-- <select class="js-select2 form-select" id="matkul_dibuka" style="width: 100%;" data-placeholder="Pilih Mata Kuliah" multiple required>
                        <option id="placeholder-matkul"></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($matkul as $key => $item)
                        <option value="{{ $key }}">{{ $item->nama_matkul }}</option>
                        @endforeach
                      </select> --}}
                      
                    </div>
                  </div>
                </div>

                <div class="row mt-5">
                  <h4 class="border-bottom pb-2">Dokumen Perkuliahan Dikumpulkan</h4>
                  <div class="row row-cols-lg-auto align-items-center">
                    <label class="col-lg-3 col-form-label fw-bold" >Pilih Dokumen</label>
                    <div class="col-md-2 col-lg-8">
                      <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start mb-3">
                        <div class="mt-3 mt-md-0">
                          <button class="btn btn-success btn-all-dokumen" type="button">
                            <i class="fa fa-fw fa-check me-1"></i> Semua
                          </button>
                          <button class="btn btn-danger btn-reset-dokumen" type="button">
                            <i class="fa-fw si si-refresh me-1"></i> Reset
                          </button>
                        </div>
                      </div>
                      <div class="mb-3">
                        <select class="js-select2 form-select @error('dokumen') is-invalid @enderror" id="select-document" name="dokumen[]" style="width: 100%;" data-placeholder="Masukkan dokumen yang akan dikumpulkan" multiple required>
                          <option id='placeholder'></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
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
       
        // Ketika pilihan radio "Semua" dipilih
        $(".btn-all").click(function() {
            $(".form-matkul-dibuka").prop("checked", true);
            $(".jumlah_kelas_container").html(`<input type="number" class="form-control @error('jumlah') is-invalid @enderror" name="jumlah[]" min="1" required>`);
        });

        // Ketika pilihan radio "Pilih Manual" dipilih
        $(".btn-reset").click(function() {
            $(".form-matkul-dibuka").prop("checked", false);
            $(".jumlah_kelas_container").html("");
        });

        $(".matkul-dibuka").on("change", "input[type='checkbox']", function() {
          var checkbox = $(this);
          var container = checkbox.closest("tr").find(".jumlah_kelas_container");

          // Jika checkbox dicentang, tambahkan elemen input jumlah kelas. Jika tidak, hapus elemen input jumlah kelas.
          if (checkbox.is(":checked")) {
            var inputJumlahKelas = `<input type="number" class="form-control @error('jumlah') is-invalid @enderror" name="jumlah[]" min="1" required>`;
            container.html(inputJumlahKelas);
          } else {
            container.empty();
          }
        });

         // Inisialisasi Select2
        $(".js-select2").select2();


        // Ketika pilihan radio "Semua" dipilih
        $(".btn-all-dokumen").click(function() {
            $("#select-document option").prop("selected", true);
            $("#placeholder").prop("selected", false);
            $("#select-document").trigger("change"); // Memperbarui Select2
        });

        // Ketika pilihan radio "Pilih Manual" dipilih
        $(".btn-reset-dokumen").click(function() {
            $("#select-document option").prop("selected", false);
            $("#select-document").trigger("change"); // Memperbarui Select2
        });
      });
     </script>
@endsection