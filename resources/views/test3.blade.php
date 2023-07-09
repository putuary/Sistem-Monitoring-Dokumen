@extends('layouts.user-base')
@section('title', 'Buat Penugasan Baru')
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
          <div class="progresses py-4">
            <ul class="d-flex align-items-center justify-content-between">
                <li id="step-1" class="blue"></li>
                <li id="step-2" class="blue"></li>
                <li id="step-3" class="blue"></li>
            </ul>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
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
          <h3 class="block-title">Dosen dan Tipe Dokumen</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form class="space-y-1" action="{{ route('penugasan.store') }}" method="POST">
                @csrf
                <div class="row">
                  <h4 class="border-bottom pb-2">Dosen Pengampu Tiap Kelas</h4>
                  
                  @foreach ($data['nama_matkul'] as $key => $kelas)
                    <div class="mb-3">
                      <div class="row row-cols-lg-auto align-items-center">
                        <label class="col-lg-3 col-form-label fw-bold" >{{ $kelas }}</label>
                        <div class="col-md-2 col-lg-8">
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped table-vcenter">
                              <thead>
                                <tr>
                                  <th class="text-center">
                                    Kelas
                                  </th>
                                  <th class="text-center">Dosen Pengampu</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $a='A'; ?>
                                @for ($i = 0; $i < $data['jumlah'][$key]; $i++)
                                  <tr>
                                    <td class="text-center">
                                      <input type="text" class="form-control" value="{{ 'R'.($data['jumlah'][$key]==1 ? '' : $a) }}" name="nama_kelas[{{ $key }}][]">
                                    </td>
                                    <td>
                                      <select class="js-select2 form-select" name="id_dosen[{{ $key }}][{{ $i }}][]" style="width: 100%;" data-placeholder="Pilih Dosen" multiple required>
                                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                        @foreach ($dosen as $item)
                                          <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                      </select>
                                    </td>
                                  </tr>
                                  <?php $a++;?>
                                @endfor
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="row">
                  <h4 class="border-bottom pb-2">Tipe Pengumpulan Dokumen Perkuliahan</h4>
                  <div class="mb-4">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                          <tr>
                            <th class="text-center">Dokumen Perkuliahan</th>
                            <th class="text-center">Dokumen Dikumpulkan</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data['dokumen'] as $key => $dokumen)
                          <tr>
                            <td>
                              <input type="hidden" name="id_dokumen[]" value="{{ unserialize($dokumen)[0] }}">
                              {{ unserialize($dokumen)[1] }}
                            </td>
                            <td>
                              <div class="space-x-2">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" id="example-radios-inline1" name="dikumpul[{{ $key }}]" value=0 checked>
                                  <label class="form-check-label" for="example-radios-inline1">Satu Dokumen</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" id="example-radios-inline2" name="dikumpul[{{ $key }}]" value=1>
                                  <label class="form-check-label" for="example-radios-inline2">Banyak Dokumen</label>
                                </div>
                              </div>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
  
                <div class="row mt-6">
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
     <script src="{{ URL::asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
     <script src="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

     <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
 
     <script>One.helpersOnLoad(["jq-notify", "js-flatpickr", "jq-select2"]);</script>

     <script>
       $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
        $('#btn-submit').click(function (e){
            e.preventDefault();
            let form = $(this).parents('form');
            
            if (form[0].checkValidity()) { // Melakukan validasi form
              Swal.fire({
                title: 'Apakah anda sudah yakin ?',
                text: 'Anda tidak akan bisa mengubah data ini lagi!',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yakin',
                denyButtonText: 'Batal',
              }).then((result) => {
                if (result.isConfirmed) {
                  form.submit();
                }
              });
            } else {
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', "Harap isi semua form"});
            }
        });

      });
     </script>
@endsection