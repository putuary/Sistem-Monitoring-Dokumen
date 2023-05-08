@extends('layouts.user-base')

@section('style')
     <!-- Stylesheets -->
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('content')

    <!-- Page Content -->
    <div class="content">

      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if (session()->has('failed'))
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>{{ session()->get('failed') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <!-- Overview -->
      <div class="row items-push">
        
        <!-- Buat Penugasan Baru -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0 btn-penugasan" href="/penugasan/buat-penugasan-baru/form-pertama">
            <div class="block-content block-content-full text-center bg-smooth">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="fa fa-user-plus fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Buat Penugasan Baru</span>
              </div>
            </div>
          </a>
        </div>
        <!-- END Buat Penugasan Baru -->

        <!-- Daftar Jumlah Kelas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/penugasan/daftar-jumlah-kelas">
            <div class="block-content block-content-full text-center bg-smooth">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="fa fa-users-between-lines fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Daftar Jumlah Kelas</span>
              </div>
            </div>
          </a>
        </div>
        <!-- End Daftar Jumlah Kelas -->

        <!-- Daftar Kelas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/penugasan/daftar-kelas">
            <div class="block-content block-content-full text-center bg-smooth">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="fa fa-users-rectangle fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Daftar Kelas</span>
              </div>
            </div>
          </a>
        </div>
        <!-- End Daftar Kelas -->

         <!-- Dokumen Ditugaskan -->
         <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/penugasan/dokumen-ditugaskan">
            <div class="block-content block-content-full text-center bg-smooth">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="fa fa-file-lines fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Dokumen Ditugaskan</span>
              </div>
            </div>
          </a>
        </div>
        <!-- End Dokumen ditugaskan -->

      </div>
      <!-- END Overview -->
    </div>
    <!-- END Page Content -->
    
@endsection

@section('script')

    <!-- Page JS Plugins -->
    <script src="{{ URL::asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src={{ URL::asset("assets/js/pages/be_pages_dashboard.min.js") }}></script>

    <script>
      $(document).ready(function() {
          $(".alert").delay(2000).fadeOut("slow");

          $('.btn-penugasan').click(function (e){
              e.preventDefault();
              Swal.fire({
                title: 'Apakah anda sudah yakin akan membuka tahun ajaran baru ?',
                text: 'Anda akan mengubah tahun yang sedang berjalan!',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yakin',
                denyButtonText: `Batal`,
                }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.isConfirmed) {
                  window.location.href = $(this).attr('href');
                  }
              });
          });
      });
     </script>
@endsection