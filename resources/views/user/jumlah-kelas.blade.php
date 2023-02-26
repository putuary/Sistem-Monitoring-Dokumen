@extends('layouts.user-base')

@section('style')
     <!-- Stylesheets -->
     <link rel="stylesheet" href="{{ URL::asset('assets/js/plugins/select2/css/select2.min.css') }}">
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
          <h3 class="block-title">Jumlah Kelas Pada Setiap Mata Kuliah</h3>
        </div>
        <div class="block-content block-content-full">
          <div class="row">
            <div class="col-lg">
              <!-- Form Horizontal - Default Style -->
              <form class="space-y-4" action="be_forms_layouts.html" method="POST" onsubmit="return false;">
                <div class="row row-cols-lg-auto g-3 align-items-center">
                    <label class="col-sm-4 col-form-label" for="example-hf-password">Mata Kuliah</label>
                    <div class="col-md-2 col-lg-4">
                      <select class="js-select2 form-select" name="one-ecom-product-category" style="width: 100%;" data-placeholder="Pilih Mata Kuliah">
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        <option value="1">2020/2021 Genap</option>
                        <option value="2">Video Games</option>
                        <option value="3">Tablets</option>
                        <option value="4">Laptops</option>
                        <option value="5">PC</option>
                        <option value="6">Home Cinema</option>
                        <option value="7">Sound</option>
                        <option value="8">Office</option>
                        <option value="9">Adapters</option>
                      </select>
                    </div>
                    <label class="col-sm-4 col-form-label" for="example-hf-password">Jumlah Kelas</label>
                    <div class="col-md-2 col-lg-1">
                        <input type="number" class="form-control" id="example-hf-password" name="example-hf-password" min="1" >
                    </div>
                    <div>
                      <button class="btn btn-sm btn-alt-danger bg-danger-light" data-bs-toggle="tooltip" title="Delete">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                </div>

                <div class="row row-cols-lg-auto g-3 align-items-center">
                  <label class="col-sm-4 col-form-label" for="example-hf-password">Mata Kuliah</label>
                  <div class="col-md-2 col-lg-4">
                    <select class="js-select2 form-select" name="one-ecom-product-category" style="width: 100%;" data-placeholder="Pilih Mata Kuliah">
                      <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                      <option value="1">2020/2021 Genap</option>
                      <option value="2">Video Games</option>
                      <option value="3">Tablets</option>
                      <option value="4">Laptops</option>
                      <option value="5">PC</option>
                      <option value="6">Home Cinema</option>
                      <option value="7">Sound</option>
                      <option value="8">Office</option>
                      <option value="9">Adapters</option>
                    </select>
                  </div>
                  <label class="col-sm-4 col-form-label" for="example-hf-password">Jumlah Kelas</label>
                  <div class="col-md-2 col-lg-1">
                      <input type="number" class="form-control" id="example-hf-password" name="example-hf-password" min="1" >
                  </div>
                  <div>
                    <button class="btn btn-sm btn-alt-danger bg-danger-light" data-bs-toggle="tooltip" title="Delete">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-3 offset-md-3">
                    <button type="button" class="btn btn-primary">Tambah</button>
                  </div>
                  <div class="col-md-3 offset-md">
                    <button type="submit" class="btn btn-success">Selanjutnya</button>
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

     <!-- Page JS Helpers (Select2 + Bootstrap Maxlength + CKEditor plugins) -->
 
     <script>
       One.helpersOnLoad([
         "jq-select2",
       ]);
     </script>
@endsection