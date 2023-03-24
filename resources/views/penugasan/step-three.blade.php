@extends('layouts.user-base')

@section('style')
     <!-- Stylesheets -->
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
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
              <form class="space-y-4" action="{{ route('penugasan.store') }}" method="POST" id="form-control">
                @csrf
                {{-- <?php dd($data); ?> --}}
                <input type="hidden" name="data" value="<?php echo htmlspecialchars(serialize($data)) ?>">
                <div class="row">
                  <div class="col-lg-3">
                    <label class="form-label" for="example-flatpickr-custom">Tanggal Mulai Perkuliahan</label>
                  </div>
                  <div class="col-lg-8 col-xl-5">
                    <div class="mb-4">
                      <input type="text" class="js-flatpickr form-control @error('tanggal_mulai_kuliah') is-invalid @enderror"  name="tanggal_mulai_kuliah" placeholder="Masukkan tanggal mulai perkuliahan" data-date-format="j F Y" required>
                      @error('tanggal_mulai_kuliah')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-3">
                    <label class="form-label" for="example-select2-multiple">Dokumen Di Kumpulkan</label>
                  </div>
                  <div class="col-lg-8 col-xl-5">
                    <div class="mb-4">
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
                    <button type="submit" class="btn btn-success" id="btn-submit">Submit</button>
                  </div>
                </div>
              </form>
              
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

    <!-- Page JS Plugins -->
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    {{-- <script src="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}

    <!-- Page JS Code -->
    {{-- <script src="{{ URL::asset('assets/js/pages/be_comp_dialogs.min.js') }}"></script> --}}

     <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
     <script>One.helpersOnLoad(["js-flatpickr", "jq-select2"]);</script>

      <!-- Page JS Code -->
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
        // $(document).ready(function () {
        //   $(".btn-submit").click(function () {
        //     $("#form-control").submit();
        //   });
        // });
        // post data to server using ajax
        // function postData() {
        //   let arr=<?php echo json_encode($data); ?>;
        //   console.log(arr);
        //   var data = {
        //     'tanggal_mulai_perkuliahan': $('#example-flatpickr-custom').val(),
        //     'dokumen_di_kumpulkan': $('#example-select2-multiple').val(),
        //     '_token': '{{ csrf_token() }}',
        //     'data': arr,
        //   };
        //   $.ajax({
        //     url: "{{ route('penugasan.store') }}",
        //     type: "POST",
        //     data: data,
        //     success: function (data) {
        //       console.log(data);
        //     },
        //     error: function (data) {
        //       console.log('Error:', data);
        //     }
        //   });
        // }

      </script>
@endsection