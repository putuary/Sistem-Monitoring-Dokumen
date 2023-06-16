@extends('layouts.user-base')

@section('title', 'Detail Score '. $detail->user->nama ?? null)

@section('style')
     <!-- Stylesheets -->
     <link
     rel="stylesheet"
     href={{ URL::asset("assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css")}} />
   <link
     rel="stylesheet"
     href={{ URL::asset("assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css")}} />
   <link
     rel="stylesheet"
     href={{ URL::asset("assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css")}} />
@endsection

@section('content')
    <!-- Page Content -->
    
    <div class="content">

      <!-- pop up success upload -->
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

      <!-- All Products -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Detail Score {{ $detail->user->nama ?? null }}</h3>
        </div>
        <div class="block-content block-content-full">
          <!-- DataTables init on table by adding .js-dataTable-responsive class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
          <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center" >Dokumen</th>
                <th class="text-center" >Mata Kuliah</th>
                <th class="text-center" >Kelas</th>
                <th class="text-center" >Tenggat Waktu</th>
                <th class="text-center" >Waktu Pengumpulan</th>
                <th class="text-center" style="width: 10%">Poin + Bonus</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($detail->user->score as $key => $item)
              @if($item->poin != null)
              <tr>
                <td class="text-center fs-sm">{{ $key+1 }}</td>
                <td class="fs-sm">{{ $item->scoreable->dokumen_ditugaskan->nama_dokumen }}</td>
                <td class="fs-sm">{{ $item->kelas->matkul->nama_matkul }}</td>
                <td class="text-center fs-sm">{{ $item->kelas->nama_kelas }}</td>
                <td class="fs-sm text-center">{{ showWaktu($item->scoreable->dokumen_ditugaskan->tenggat_waktu) }}</td>
                <td class="fs-sm text-center">{{ showWaktu($item->scoreable->waktu_pengumpulan) }}</td>
                <td class="text-center fs-sm">{{ ($item->poin  != null) ? $item->poin : '-'}} {!! ($item->bonus !=null) ? "<sup class='text-success'><span class='fa-fw fa-plus'></span>".$item->bonus." </sup>" : '' !!}</td>
              </tr>
              @endif
              @endforeach
              
            </tbody>
          </table>
          <table class="table table-bordered table-striped table-vcenter mt-4">
            <tr>
              <td colspan="6" class="text-start"><strong>Total Poin:</strong></td>
              <td class="text-end">{{ $detail->total_poin }}</td>
            </tr>
            <tr>
              <td colspan="6" class="text-start"><strong>Total Ditugaskan:</strong></td>
              <td class="text-end">{{ $detail->task }}</td>
            </tr>
            <tr class="table-success">
              <td colspan="6" class="text-start text-uppercase"><strong>Score:</strong></td>
              <td class="text-end"><strong>{{ $detail->score }}</strong></td>
            </tr>
          </table>

        </div>
      </div>
      <!-- END All Products -->
    </div>
    <!-- END Page Content -->
@endsection

@section('script')
    <script src={{  URL::asset("assets/js/plugins/datatables/jquery.dataTables.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/dataTables.buttons.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js") }}></script>
    <script src={{  URL::asset("assets/js/plugins/datatables-buttons/buttons.html5.min.js") }}></script>

     <!-- Page JS Code -->
     <script src={{  URL::asset("assets/js/pages/be_tables_datatables.min.js") }}></script>

     <script>
      $(document).ready(function () {
        $(".alert").delay(2000).fadeOut("slow");
      });
    </script>
@endsection