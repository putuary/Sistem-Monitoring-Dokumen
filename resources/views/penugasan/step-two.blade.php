@extends('layouts.user-base')

@section('style')
     <!-- Stylesheets -->
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
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
          <h3 class="block-title">Dosen Pengampu Pada Setiap Kelas</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form class="space-y-1" action="{{ route('penugasan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $data['tahun_ajaran'] }}">
                <h4 class="border-bottom pb-2">Kelas</h4>
                <div class="mb-4">
                  <?php $jumlah=0; ?>
                @foreach ($nama_matkul as $key => $kelas)
                <?php $a='A'; ?>
                  @for ($i = 0; $i < $data['jumlah'][$key]; $i++)
                  <div class="row row-cols-lg-auto g-3 align-items-center mb-3">
                    <input type="hidden" name="kode_matkul[]" value="{{ $data['kode_matkul'][$key] }}">
                    <label class="col-sm-4 col-form-label" for="example-hf-password">Kelas</label>
                    <div class="col-md-2 col-lg-6">
                      <div class="form-control">{{ $kelas.' R'.($data['jumlah'][$key]==1 ? '' : $a) }}</div>
                      <input type="hidden" value="{{ 'R'.($data['jumlah'][$key]==1 ? '' : $a) }}" name="nama_kelas[]">
                    </div> 
                    <label class="col-sm-4 col-form-label" for="example-hf-password">Dosen</label>
                    <div class="col-md-2 col-lg-4">
                      <select class="js-select2 form-select" name="id_dosen[{{ $jumlah+$i }}][]" style="width: 100%;" data-placeholder="Pilih Dosen" multiple required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($dosen as $item)
                          <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <?php $a++;?>
                  @endfor
                  <?php $jumlah=$jumlah+$data['jumlah'][$key]; ?>
                @endforeach
              </div>
                <div class="row">
                  <h4 class="border-bottom pb-2">Perkuliahan</h4>
                  <div class="col-lg-3">
                    <label class="form-label" for="example-flatpickr-custom">Tanggal Mulai Perkuliahan</label>
                  </div>
                  <div class="col-lg-8 col-xl-5">
                    <div class="mb-3">
                      <input type="text" class="js-flatpickr form-control @error('tanggal_mulai_kuliah') is-invalid @enderror"  name="tanggal_mulai_kuliah" placeholder="Masukkan tanggal mulai perkuliahan" data-date-format="j F Y" required>
                      @error('tanggal_mulai_kuliah')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <h4 class="border-bottom pb-2">Dokumen</h4>
                  <div class="col-lg-3">
                    <label class="form-label" for="example-select2-multiple">Dokumen Di Kumpulkan</label>
                  </div>
                  <div class="col-lg-8 col-xl-5">
                    <div class="mb-3">
                      <select class="js-select2 form-select @error('id_dokumen') is-invalid @enderror" id="example-select2-multiple" name="id_dokumen[]" style="width: 100%;" data-placeholder="Masukkan dokumen yang akan dikumpulkan" multiple required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @foreach ($dokumen as $item)
                        <option value="{{ $item->id_dokumen }}">{{ $item->nama_dokumen }}</option>
                        @endforeach
                      </select>
                      @error('id_dokumen')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-center">
                    <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
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
     <script src="{{ URL::asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

     <!-- Page JS Plugins -->
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>

     <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
 
     <script>One.helpersOnLoad(["js-flatpickr", "jq-select2"]);</script>

     <script>
      $('#btn-submit').click(function (e){
           e.preventDefault();
           let form = $(this).parents('form');
           swal({
               title: 'Apakah anda sudah yakin ?',
               text: 'Anda tidak akan bisa mengubah data ini lagi!',
               icon: 'warning',
               buttons: ["Buat Perubahan", "Ya!"],
           }).then(function(value) {
               if(value){
                   form.submit();
               }
           });
       });
     </script>
@endsection