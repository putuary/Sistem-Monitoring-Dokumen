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
      <div
        class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
        <div class="flex-grow-1 mb-1 mb-md-0">
          <h1 class="h3 fw-bold mb-2">Dashboard</h1>
          <h2 class="h6 fw-medium fw-medium text-muted mb-0">
            Welcome
            <a class="fw-semibold" href="be_pages_generic_profile.html"
              >{{ Auth::user()->nama }}</a
            >, everything looks great.
          </h2>
        </div>
      </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
      <!-- Overview -->
      <div class="row items-push">
        <div class="col-sm-6 col-xxl-3">
          <!-- Pending Orders -->
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">tes</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Jumlah Paket
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="far fa-envelope fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/admin/paket">
                <span>Lihat Semua Paket</span>
                <i
                  class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
              </a>
            </div>
          </div>
          <!-- END Pending Orders -->
        </div>
        <div class="col-sm-6 col-xxl-3">
          <!-- New Customers -->
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">ba</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Jumlah Barang
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="far fa-user-circle fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/admin/barang">
                <span>Lihat Semua Barang</span>
                <i
                  class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
              </a>
            </div>
          </div>
          <!-- END New Customers -->
        </div>
        <div class="col-sm-6 col-xxl-3">
          <!-- Messages -->
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">te</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Pesanan Sistem Masuk
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="far fa-envelope fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/admin/pesanan-sistem">
                <span>Lihat Semua Pesanan Sistem Masuk</span>
                <i
                  class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
              </a>
            </div>
          </div>
          <!-- END Messages -->
        </div>
        <div class="col-sm-6 col-xxl-3">
          <!-- Conversion Rate -->
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">te</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Total Saldo
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-dollar fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/admin/keuangan">
                <span>Lihat Informasi</span>
                <i
                  class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
              </a>
            </div>
          </div>
          <!-- END Conversion Rate-->
        </div>
      </div>
      <!-- END Overview -->
    </div>
    <!-- END Page Content -->
    
@endsection

@section('script')
    <script src={{ URL::asset("assets/js/pages/be_pages_dashboard.min.js") }}></script>
@endsection