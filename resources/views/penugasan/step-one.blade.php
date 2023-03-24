@extends('layouts.user-base')

@section('style')
     <!-- Stylesheets -->
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
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
          <h3 class="block-title">Jumlah Kelas Pada Setiap Mata Kuliah</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form action="/penugasan/buat-penugasan-baru/form-kedua" method="POST">
                @csrf
                <div class="space-y-4">
                  <h4 class="border-bottom pb-2">Tahun Ajaran Baru</h4>
                  <div class="row">
                    <div class="col-lg-2">
                      <label class="form-label" for="example-flatpickr-custom">Tahun Ajaran</label>
                    </div>
                    <div class="col-lg-2 col-xl-4">
                      <div class="mb-4">
                        <input type="text" class="form-control" name="tahun_ajaran" placeholder="Masukkan tahun ajaran baru" required>
                      </div>
                    </div>
                  </div>
                  <h4 class="border-bottom pb-2">Mata Kuliah Yang Di Buka</h4>
                  <div class="row row-cols-lg-auto g-3 align-items-center">
                    <label class="col-lg-2 col-form-label" >Mata Kuliah</label>
                    <div class="col-md-2 col-lg-4">
                      <select class="js-select2 form-select" name="kode_matkul[]" style="width: 100%;" data-placeholder="Pilih Mata Kuliah" required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($matkul as $item)
                        <option value="{{ $item->kode_matkul }}">{{ $item->nama_matkul }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-lg-2"></div>
                    <label class="col-lg-2 col-form-label" >Jumlah Kelas</label>
                    <div class="col-md-2 col-lg-1">
                        <input type="number" class="form-control" name="jumlah[]" min="1" required>
                    </div>
                  </div>
                  
                  <div class="row row-cols-lg-auto g-3 align-items-center">
                    <label class="col-lg-2 col-form-label" >Mata Kuliah</label>
                    <div class="col-md-2 col-lg-4">
                      <select class="js-select2 form-select form-option" name="kode_matkul[]" style="width: 100%;" data-placeholder="Pilih Mata Kuliah" required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($matkul as $item)
                        <option value="{{ $item->kode_matkul }}">{{ $item->nama_matkul }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-lg-2"></div>
                    <label class="col-lg-2 col-form-label" >Jumlah Kelas</label>
                    <div class="col-md-2 col-lg-1">
                        <input type="number" class="form-control" name="jumlah[]" min="1" required>
                    </div>
                    <div>
                      <button class="btn btn-hapus btn-sm btn-alt-danger bg-danger-light" data-bs-toggle="tooltip" title="Delete">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row mt-5">
                  <div class="col-md-3 offset-md-3">
                    <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
                  </div>
                  <div class="col-md-3 offset-md">
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
     <script src={{  URL::asset("assets/js/plugins/select2/js/select2.full.min.js") }}></script>

     <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
 
     <script>
       One.helpersOnLoad([
         "jq-select2",
       ]);
     </script>

     <script>    
      $(document).ready(function(){
        // Remove list item on click
        let data = <?php echo json_encode($matkul); ?>;
        console.log(data);

        $(".btn-tambah").click(function(){
          var html = `<div class="row row-cols-lg-auto g-3 align-items-center">
                        <label class="col-lg-2 col-form-label" >Mata Kuliah</label>
                        <div class="col-md-2 col-lg-4">
                          <select class="js-select2 form-select" name="kode_matkul[]" style="width: 100%;" data-placeholder="Pilih Mata Kuliah" required>
                            <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                            @foreach ($matkul as $item)
                            <option value="{{ $item->kode_matkul }}">{{ $item->nama_matkul }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg-2"></div>
                        <label class="col-lg-2 col-form-label" >Jumlah Kelas</label>
                        <div class="col-md-2 col-lg-1">
                            <input type="number" class="form-control" name="jumlah[]" min="1" required>
                        </div>
                        <div>
                          <button class="btn btn-hapus btn-sm btn-alt-danger bg-danger-light" data-bs-toggle="tooltip" title="Delete">
                            <i class="fa fa-fw fa-times"></i>
                          </button>
                        </div>
                      </div>`;
          $(".space-y-4").append(html);
          One.helpersOnLoad([
            "jq-select2",
          ]);
        });

        $(".space-y-4").on("click", ".btn-hapus", function(){
          $(this).closest(".row").remove();
        });
      });
     </script>
@endsection