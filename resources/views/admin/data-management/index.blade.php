@extends('layouts.user-base')
@section('title', 'Manajemen Data')
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
        
        <!-- Mata Kuliah -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/manajemen-data/mata-kuliah">
            <div class="block-content block-content-full text-center bg-amethyst">
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
            <div class="block-content block-content-full text-center bg-amethyst-op">
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

        <!-- Indikator Penilaian -->
        <div class="col-md-6 col-lg-4 col-xl-3">
          <a class="block block-rounded block-link-pop h-100 mb-0" href="/manajemen-data/indikator-penilaian">
            <div class="block-content block-content-full text-center bg-default-light">
              <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto">
                <i class="fa fa-file-pen fa-2x text-white-75"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <div class="block-content block-content-full block-content-sm text-center fs-sm fw-medium">
                <span>Indikator Penilaian</span>
              </div>
            </div>
          </a>
        </div>
        <!-- Indikator Penilaian -->

      </div>
      <!-- END Overview -->
    </div>
    <!-- END Page Content -->
    
@endsection

@section('script')
    <script src={{ URL::asset("assets/js/pages/be_pages_dashboard.min.js") }}></script>
    <script>
      $(document).ready(function() {
          $(".alert").delay(2000).fadeOut("slow");
      });
     </script>
@endsection