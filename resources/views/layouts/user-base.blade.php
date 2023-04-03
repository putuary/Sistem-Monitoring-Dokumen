<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Halaman 
      @if (in_array(Auth()->user()->role, ['kaprodi', 'gkmp']))
      {{ Auth()->user()->aktif_role->is_dosen == 1 ? "Dosen Pengampu" : namaPeran(Auth()->user()->role)  }}
      @else
        {{ namaPeran(Auth()->user()->role) }}
      @endif
    </title>

    <meta name="description" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="OneUI">
    <meta property="og:description" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href={{ URL::asset("assets/media/favicons/logo-if.png")}}>
    <link rel="icon" type="image/png" sizes="192x192" href={{ URL::asset("assets/media/favicons/favicon-192x192.png")}}>
    <link rel="apple-touch-icon" sizes="180x180" href={{ URL::asset("assets/media/favicons/apple-touch-icon-180x180.png")}}>
    <!-- END Icons -->

   @yield('style')

    <!-- OneUI framework -->
    <link rel="stylesheet" id="css-main" href={{ URL::asset("assets/css/oneui.min.css")}}>

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/amethyst.min.css"> -->
    <!-- END Stylesheets -->
  </head>

  <body>
    <!-- Page Container -->
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
      <!-- Side Overlay-->
      <aside id="side-overlay">
        <!-- Side Header -->
        <div class="content-header border-bottom">
          <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
          <a class="ms-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
            <i class="fa fa-fw fa-times"></i>
          </a>
          <!-- END Close Side Overlay -->
        </div>
        <!-- END Side Header -->
      </aside>
      <!-- END Side Overlay -->


      <!-- Sidebar -->
      <!--
          Sidebar Mini Mode - Display Helper classes

          Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
          Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
              If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

          Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
          Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
          Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
      <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header -->
        <div class="content-header">
          <!-- Logo -->
          <a class="text-dual" href="/">
            <img class="fa fa-2x navbar-toggler-icon" src="{{ URL::asset("assets/media/favicons/logo-if.png")}}" />
          </a>
          <!-- END Logo -->

          <!-- Extra -->
          <div>
            <div class="dropdown d-inline-block ms-1">
              <span class="smini-hide fs-sm tracking-wider">Sistem Monitoring Dokumen Perkuliahan</span>
            </div>
            <!-- END Options -->

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
              <i class="fa fa-fw fa-times"></i>
            </a>
            <!-- END Close Sidebar -->
          </div>
          <!-- END Extra -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
          <!-- Side Navigation -->
          <div class="content-side">
            <ul class="nav-main">
              <li class="nav-main-item">
                <a class="nav-main-link {{ URL::to("/") ? 'active' : '' }}" href="/">
                  <i class="nav-main-link-icon si si-speedometer"></i>
                  <span class="nav-main-link-name">Dashboard</span>
                </a>
              </li>

              @if (in_array(Auth()->user()->role, ['kaprodi', 'gkmp']))
                @if (Auth()->user()->aktif_role->is_dosen == 1)
                  @include('layouts.sidebar-dosen')
                @else
                  @include('layouts.sidebar-admin')
                @endif
              @elseif(Auth()->user()->role == 'admin')
                @include('layouts.sidebar-admin')
              @else
                @include('layouts.sidebar-dosen')
              @endif



            </ul>
          </div>
          <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
      </nav>
      <!-- END Sidebar -->

      <!-- Header -->
      <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
          <!-- Left Section -->
          <div class="d-flex align-items-center">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->

            <!-- Toggle Mini Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
              <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>
            <!-- END Toggle Mini Sidebar -->

            <!-- Open Search Section (visible on smaller screens) -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout" data-action="header_search_on">
              <i class="fa fa-fw fa-search"></i>
            </button>
            <!-- END Open Search Section -->
            
          </div>
          <!-- END Left Section -->

          <!-- Right Section -->
          <div class="d-flex align-items-center">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block ms-2">
              <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" src={{ URL::asset("assets/media/avatars/avatar10.jpg")}} alt="Header Avatar" style="width: 21px;">
                <span class="d-none d-sm-inline-block ms-2">{{ Auth()->user()->nama }}</span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                  <img class="img-avatar img-avatar48 img-avatar-thumb" src={{ URL::asset("assets/media/avatars/avatar10.jpg")}} alt="">
                  <p class="mt-2 mb-0 fw-medium">{{ Auth()->user()->nama }}</p>
                  <p class="mb-0 text-muted fs-sm fw-medium">
                    @if (in_array(Auth()->user()->role, ['kaprodi', 'gkmp']))
                      {{ Auth()->user()->aktif_role->is_dosen == 1 ? "Dosen Pengampu" : namaPeran(Auth()->user()->role)  }}
                    @else
                      {{ namaPeran(Auth()->user()->role) }}
                    @endif
                  </p>
                </div>
                <div class="p-2">
                  <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_profile.html">
                    <span class="fs-sm fw-medium">Profile</span>
                    <span class="badge rounded-pill bg-primary ms-2">1</span>
                  </a>
                </div>
                <div role="separator" class="dropdown-divider m-0"></div>
                @if (in_array(Auth()->user()->role, ['kaprodi', 'gkmp']))
                <div class="p-2">
                  <form action="/change-dashboard" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between"><span class="fs-sm fw-medium">Login Sebagai {{ Auth()->user()->aktif_role->is_dosen == 0 ? "Dosen" : ucfirst(Auth()->user()->role)  }}</span></button>
                  </form>
                </div>
                <div role="separator" class="dropdown-divider m-0"></div>
                @endif
                <div class="p-2">
                  <form action="/user-logout" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between"><span class="fs-sm fw-medium">Log Out</span></button>
                  </form>
                </div>
              </div>
            </div>
            <!-- END User Dropdown -->

           
          </div>
          <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Search -->
        <div id="page-header-search" class="overlay-header bg-body-extra-light">
          <div class="content-header">
            <form class="w-100" action="be_pages_generic_search.html" method="POST">
              <div class="input-group">
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-alt-danger" data-toggle="layout" data-action="header_search_off">
                  <i class="fa fa-fw fa-times-circle"></i>
                </button>
                <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
              </div>
            </form>
          </div>
        </div>
        <!-- END Header Search -->

        <!-- Header Loader -->
        <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-body-extra-light">
          <div class="content-header">
            <div class="w-100 text-center">
              <i class="fa fa-fw fa-circle-notch fa-spin"></i>
            </div>
          </div>
        </div>
        <!-- END Header Loader -->
      </header>
      <!-- END Header -->

      <!-- Main Container -->
      <main id="main-container">
        @yield('content')
      </main>
      <!-- END Main Container -->

      <!-- Footer -->
      <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
          <div class="row fs-sm">
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
              <a class="fw-semibold" href="#">Program Studi Tenik Informatika ITERA</a> &copy; <span data-toggle="year-copy"></span>
            </div>
          </div>
        </div>
      </footer>
      <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--
        OneUI JS

        Core libraries and functionality
        webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src={{ URL::asset("assets/js/oneui.app.min.js")}}></script>

     <!-- jQuery (required for DataTables plugin) -->
     <script src={{  URL::asset("assets/js/lib/jquery.min.js") }}></script>

    @yield('script')
  </body>
</html>
