@extends('layouts.user-base')
@section('title', 'Dashboard')
@section('content')
    <!-- Hero -->
    <div class="content">
      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif
      <div
        class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
        <div class="flex-grow-1 mb-1 mb-md-0">
          <h1 class="h3 fw-bold mb-2">Dashboard</h1>
          <h2 class="h6 fw-medium fw-medium text-muted mb-0">
            Selamat datang kembali,
            <a class="fw-semibold" href="/profil"
              >{{ Auth::user()->nama }}</a
            >
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
              <dl class="mb-0 text-center">
                <dt class="fs-3 fw-bold">{{ $persentase_dikumpulkan. '%' }}</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Progres Pengumpulan
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-bars-progress fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/progres-pengumpulan">
                <span>Lihat Progres Pengumpulan</span>
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
              <dl class="mb-0 text-center">
                <dt class="fs-3 fw-bold">{{ $jumlahKelas }}</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Jumlah Kelas
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-users-between-lines fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/penugasan/daftar-kelas">
                <span>Lihat Semua Kelas</span>
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
              <dl class="mb-0 text-center">
                <dt class="fs-3 fw-bold">{{ $jumlahDosen }}</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Jumlah Dosen
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-user-graduate fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/manajemen-pengguna">
                <span>Lihat Semua Dosen</span>
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
              <dl class="mb-0 text-center">
                <dt class="fs-3 fw-bold">{{ $report->total_terlambat }}</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Pengumpulan Terlambat
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-file-circle-exclamation fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/riwayat-pengumpulan-poin?filter=terlambat">
                <span>Lihat Pengumpulan Terlambat</span>
                <i
                  class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
              </a>
            </div>
          </div>
          <!-- END Conversion Rate-->
        </div>

        <div class="col-sm-6 col-xxl-3">
          <!-- Conversion Rate -->
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div
              class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
              <dl class="mb-0 text-center">
                <dt class="fs-3 fw-bold">{{ $report->total_belum_dikumpul }}</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                  Belum Dikumpulkan
                </dd>
              </dl>
              <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-file-circle-minus fs-3 text-primary"></i>
              </div>
            </div>
            <div class="bg-body-light rounded-bottom">
              <a
                class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                href="/riwayat-pengumpulan-poin?filter=belum_terkumpul">
                <span>Lihat Belum Dikumpulkan</span>
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
    <script>
      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection