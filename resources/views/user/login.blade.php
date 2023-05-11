<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Sistem Informasi Monitoring Dokumen</title>

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
    <link rel="shortcut icon" href={{ URL::asset("assets/media/favicons/favicon.png")}}>
    <link rel="icon" type="image/png" sizes="192x192" href={{ URL::asset("assets/media/favicons/favicon-192x192.png")}}>
    <link rel="apple-touch-icon" sizes="180x180" href={{ URL::asset("assets/media/favicons/apple-touch-icon-180x180.png")}}>
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- OneUI framework -->
    <link rel="stylesheet" id="css-main" href={{ URL::asset("assets/css/oneui.min.css")}}>

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/amethyst.min.css"> -->
    <!-- END Stylesheets -->
  </head>

  <body>
    <!-- Page Container -->
    <!--
        Available classes for #page-container:

    GENERIC

      'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                  - Theme helper buttons [data-toggle="theme"],
                                                  - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                  - ..and/or One.layout('dark_mode_[on/off/toggle]')

    SIDEBAR & SIDE OVERLAY

      'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
      'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
      'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
      'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
      'sidebar-dark'                              Dark themed sidebar

      'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
      'side-overlay-o'                            Visible Side Overlay by default

      'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

      'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

    HEADER

      ''                                          Static Header if no class is added
      'page-header-fixed'                         Fixed Header

    HEADER STYLE

      ''                                          Light themed Header
      'page-header-dark'                          Dark themed Header

    MAIN CONTENT LAYOUT

      ''                                          Full width Main Content if no class is added
      'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
      'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

    DARK MODE

      'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
    -->
    <div id="page-container">

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="bg-image" style="background-image: url({{ URL::asset('assets/media/photos/background.png')}});">
          <div class="row g-0 bg-primary-dark-op">
            <!-- Meta Info Section -->
            <div class="hero-static col-lg-4 d-none d-lg-flex flex-column justify-content-center">
              <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
                <div class="w-100">
                  <p class="link-fx fw-semibold fs-2 text-white" >
                    Sistem Monitoring <br> Dokumen Perkuliahan
                  </p>
                  <p class="text-white-75 me-xl-8 mt-2">
                   "Teknik Informatika ITERA"
                  </p>
                </div>
              </div>
              <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center fs-sm">
                <p class="fw-medium text-white-50 mb-0">
                  <strong>Program Studi Teknik Informatika ITERA</strong> &copy; <span data-toggle="year-copy"></span>
                </p>
              </div>
            </div>
            <!-- END Meta Info Section -->

            <!-- Main Section -->
            <div class="hero-static col-lg-8 d-flex flex-column align-items-center bg-body-extra-light">
              <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
                <div class="w-100">
                  <!-- Header -->
                  <div class="text-center mb-5">
                    <p class="mb-3">
                      <img class="fa fa-2x navbar-toggler-icon" src={{ URL::asset("assets/media/favicons/logo-if.png")}} />
                    </p>
                    <h1 class="fw-bold mb-2">
                      Log In
                    </h1>
                  </div>
                  <!-- END Header -->

                  <!-- Sign In Form -->
                  <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                  <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                  <div class="row g-0 justify-content-center">
                    <div class="col-sm-8 col-xl-4">
                      <form class="js-validation-signin" action="/user-login" method="POST">
                        @csrf
                        <div class="mb-4">
                          <input type="email" class="form-control form-control-lg form-control-alt py-3" id="login-email" name="email" placeholder="Email">
                          @if ($errors->has('email'))
                            <!-- <div class="alert alert-danger">
                            {{ $errors }}
                            </div> -->
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                          @endif
                        </div>
                        <div class="mb-4">
                          <div class="input-group">
                            <input type="password" class="form-control form-control-lg form-control-alt py-3" id="login-password" name="password" placeholder="Password">
                            <span class="input-group-text">
                                  <i
                                    class="fa fa-eye-slash"
                                    id="togglePassword"
                                    style="cursor: pointer"></i>
                            </span>
                          </div>
                             @if ($errors->has('password'))
                                    <!-- <div class="alert alert-danger">
                                        {{ $errors }}
                                    </div> -->
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                        </div>
                        <div class="text-end mb-4">
                          {{-- <div>
                            <a class="text-muted fs-sm fw-medium d-block d-lg-inline-block mb-1" href="op_auth_reminder3.html">
                              Lupa Password?
                            </a>
                          </div> --}}
                          <div>
                            <button type="submit" class="btn btn-lg btn-alt-primary">
                              <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Masuk
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!-- END Sign In Form -->
                </div>
              </div>
              <div class="px-4 py-3 w-100 d-lg-none d-flex flex-column flex-sm-row justify-content-between fs-sm text-center text-sm-start">
                <p class="fw-medium text-black-50 py-2 mb-0">
                  <strong>Program Studi Teknik Informatika ITERA</strong> &copy; <span data-toggle="year-copy"></span>
                </p>
              </div>
            </div>
            <!-- END Main Section -->
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!--
        OneUI JS

        Core libraries and functionality
        webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src={{ URL::asset("assets/js/oneui.app.min.js")}}></script>

    <!-- jQuery (required for jQuery Validation plugin) -->
    <script src={{ URL::asset("assets/js/lib/jquery.min.js")}}></script>

    <!-- Page JS Plugins -->
    <script src={{ URL::asset("assets/js/plugins/jquery-validation/jquery.validate.min.js")}}></script>

    <!-- Page JS Code -->
    <script src={{ URL::asset("assets/js/pages/op_auth_signin.min.js")}}></script>

    <script>
      $("#togglePassword").click(function (e) {
        e.preventDefault();
        var type = $(this)
          .parent()
          .parent()
          .find("#login-password")
          .attr("type");
        if (type == "password") {
          $(this)
            .parent()
            .parent()
            .find("#login-password")
            .attr("type", "text");
          $(this).addClass("fa-eye");
          $(this).removeClass("fa-eye-slash");
        } else {
          $(this)
            .parent()
            .parent()
            .find("#login-password")
            .attr("type", "password");
          $(this).addClass("fa-eye-slash");
          $(this).removeClass("fa-eye");
        }
      });
    </script>
  </body>
</html>
