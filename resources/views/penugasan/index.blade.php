@extends('layouts.user-base')
@section('content')
    <!-- Hero -->
    <div class="content">
      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
      <!-- Overview -->
      <div class="row items-push">
        
        <!-- Buat Penugasan Baru -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/penugasan/buat-penugasan-baru/form-pertama">
            <div class="block-content block-content-full text-center bg-smooth">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="fab fa-html5 fa-2x text-white-75"></i>
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
                <i class="fab fa-html5 fa-2x text-white-75"></i>
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
                <i class="fab fa-html5 fa-2x text-white-75"></i>
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


      </div>
      <!-- END Overview -->
    </div>
    <!-- END Page Content -->
    
@endsection

@section('script')
    <script src={{ URL::asset("assets/js/pages/be_pages_dashboard.min.js") }}></script>
@endsection