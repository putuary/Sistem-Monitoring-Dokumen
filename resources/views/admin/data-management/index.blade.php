@extends('layouts.user-base')
@section('title', 'Manajemen Data')
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
        
        <!-- Mata Kuliah -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/manajemen-data/mata-kuliah">
            <div class="block-content block-content-full text-center bg-primary">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="far fa-rectangle-list fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Mata Kuliah</span>
              </div>
            </div>
          </a>
        </div>
        <!-- END Mata Kuliah -->

        <!-- Dokumen Pengajaran -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/manajemen-data/dokumen-perkuliahan">
            <div class="block-content block-content-full text-center bg-amethyst">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="far fa-file-lines fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Dokumen Perkuliahan</span>
              </div>
            </div>
          </a>
        </div>
        <!-- END Dokumen Pengajaran -->

        <!-- Badge -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/manajemen-data/badge">
            <div class="block-content block-content-full text-center bg-amethyst-light">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="si si-badge fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Badge</span>
              </div>
            </div>
          </a>
        </div>
        <!-- Badge -->

      </div>
      <!-- END Overview -->
    </div>
    <!-- END Page Content -->
    
@endsection

@section('script')
    <script src={{ URL::asset("assets/js/pages/be_pages_dashboard.min.js") }}></script>
@endsection