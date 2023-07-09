@extends('layouts.user-base')
@section('title', 'Resume Pengumpulan Dokumen Perkuliahan')
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

      
      <!-- Table -->
      <div class="block block-rounded">
        <div class="block-content">
          <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 mb-2">
            <div class="flex-grow-1">
              <h1 class="h3 fw-bold mb-2 text-center">
                Laporan Pengumpulan Dokumen Perkuliahan <br />
                TA : {{ $tahun_ajaran->tahun_ajaran }} 
              </h1>
            </div>
          </div>
          <div class="block-content">
            <div class="row justify-content-left">
              <div class="col-md-2 col-lg-4">
                <div class="mb-4 text-center">
                  <a href="/progres-pengumpulan/resume-pengumpulan/unduh?tahun_ajaran={{ request('tahun_ajaran') ?? null }}" class="btn btn-alt-info" id="btn-submit">
                    <i class="fa fa-fw fa-download me-1"></i> Unduh Laporan Pengumpulan
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter">
              <thead>
                <tr>
                  <th class="text-center">Mata Kuliah</th>
                  <th class="text-center">Kelas</th>
                  <th class="text-center">Dosen</th>
                  @foreach ($dokumen as $item)
                  <th class="text-center">{{ $item->nama_dokumen }}</th>    
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach ($report->kelas as $item)
                <tr>
                  <th class="text-center">{{ $item->nama_matkul }}</th>
                  <th class="text-center">{{ $item->nama_kelas }}</th>
                  <td>
                    <ul>
                      @foreach ($item->dosen as $nama_dosen)
                      <li>{{ $nama_dosen }}</li>
                      @endforeach
                    </ul>
                  </td>
                  @foreach ($item->dokumen as $dokumen)
                  <td class="text-center">@if($dokumen->status !=2) <i class="fa fa-2x fa-{{ $dokumen->status==1 ? ($dokumen->is_late==1 ? 'check text-warning' : 'check text-success' ) : 'xmark text-danger' }}"></i>@else TD @endif</td>
                  @endforeach
                </tr>
                @endforeach
                <tr class="table-success">
                  <td colspan="2" class="text-start"><strong>Total Terkumpul:</strong></td>
                  <td class="text-end">{{ $report->total_dikumpul }}</td>
                </tr>
                <tr class="table-danger">
                  <td colspan="2" class="text-start"><strong>Total Belum Dikumpul:</strong></td>
                  <td class="text-end">{{ $report->total_belum_dikumpul }}</td>
                </tr>
                <tr class="table-primary">
                  <td colspan="2" class="text-start"><strong>Total Tepat Waktu:</strong></td>
                  <td class="text-end"><strong>{{ $report->total_tepat_waktu }}</strong></td>
                </tr>
                <tr class="table-warning">
                  <td colspan="2" class="text-start"><strong>Total Terlambat:</strong></td>
                  <td class="text-end"><strong>{{ $report->total_terlambat }}</strong></td>
                </tr>
                <tr class="table-info">
                  <td colspan="2" class="text-start"><strong>Total Ditugaskan:</strong></td>
                  <td class="text-end"><strong>{{ $report->total_ditugaskan}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-xl-12 mt-2">
            <!-- Pie Chart -->
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">Diagram Resume Pengumpulan</h3>
              </div>
              <div class="block-content block-content-full text-center">
                <div class="py-3 px-xxl-7">
                  <!-- Pie Chart Container -->
                  <canvas id="js-chartjs-pie"></canvas>
                </div>
              </div>
            </div>
            <!-- END Pie Chart -->
          </div>
      </div>
      <!-- Table -->
    </div>
    <!-- END Page Content -->
@endsection

@section('script')
    <!-- Page JS Plugins -->
    <script src="{{ URL::asset('assets/js/plugins/chart.js/chart.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> --}}
    
    <!-- Page JS Code -->
    <script>
      var values = [{{ $report->total_tepat_waktu }}, {{ $report->total_terlambat }}, {{ $report->total_belum_dikumpul }}];

      var ctx = document.getElementById('js-chartjs-pie').getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'pie',
          data: {
              labels: ["Tepat Waktu", "Terlambat", "Belum Dikumpulkan"],
              datasets: [{
                  data: values,
                  backgroundColor: [
                    "rgba(171, 227, 125, 1)",
                    "rgba(250, 219, 125, 1)",
                    "rgba(117, 176, 235, 1)",
                  ],
                  hoverBackgroundColor: [
                      "rgba(171, 227, 125, .75)",
                      "rgba(250, 219, 125, .75)",
                      "rgba(117, 176, 235, .75)",
                  ],
                  borderWidth: 1
              }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  var dataset = data.datasets[tooltipItem.datasetIndex];
                  var labels = data.labels[tooltipItem.index];
                  var currentValue = dataset.data[tooltipItem.index];
                  return labels+": "+currentValue+" %";
                }
              }
            }
          }
      });
  </script>

@endsection