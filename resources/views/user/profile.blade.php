@extends('layouts.user-base')

@section('content')
    <!-- Hero -->
    <div class="bg-image" style="background-image: url('assets/media/photos/photo12@2x.jpg')">
      <div class="bg-black-50">
        <div class="content content-full text-center">
          <div class="my-3">
            <img class="img-avatar img-avatar-thumb" src="{{ auth()->user()->avatar == 'default.png' ? asset('storage/avatar/avatar13.jpg') : asset('storage/avatar/'.auth()->user()->avatar) }}" alt="" />
          </div>
          <h1 class="h2 text-white mb-0">{{ $user->nama }}</h1>
          <span class="text-white-75">{{ isset($user->aktif_role) ? ($user->aktif_role->is_dosen==0 ? namaPeran($user->role) : 'Dosen Pengampu') : namaPeran($user->role) }}</span>
        </div>
      </div>
    </div>
    <!-- END Hero -->

    <!-- Stats -->
    <div class="bg-body-extra-light">
      <div class="content content-boxed">
        <div class="row items-push text-center">
          <div class="col-6 col-md-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase">Sales</div>
            <a class="link-fx fs-3" href="javascript:void(0)">17980</a>
          </div>
          <div class="col-6 col-md-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase">Products</div>
            <a class="link-fx fs-3" href="javascript:void(0)">27</a>
          </div>
          <div class="col-6 col-md-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase">Followers</div>
            <a class="link-fx fs-3" href="javascript:void(0)">1360</a>
          </div>
          <div class="col-6 col-md-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase mb-2">739 Ratings</div>
            <span class="text-warning">
              <i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
              <i class="fa fa-star-half"></i>
            </span>
            <span class="fs-sm text-muted">(4.9)</span>
          </div>
        </div>
      </div>
    </div>
    <!-- END Stats -->

    <!-- Page Content -->
    <div class="content content-boxed">
      <!-- User Profile -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Profil Pengguna</h3>
        </div>
        <div class="block-content">
          <form action="/profil/update" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row push">
              <div class="col-lg-4">
              </div>
              <div class="col-lg-8 col-xl-5">
                <div class="mb-4">
                  <label class="form-label" for="one-profile-edit-name">Nama</label>
                  <input type="text" class="form-control" id="one-profile-edit-name" name="nama" placeholder="Masukkan nama anda" value="{{ $user->nama }}" required/>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="one-profile-edit-email">Email</label>
                  <input type="email" class="form-control" id="one-profile-edit-email" name="email" placeholder="Masukkan email anda" value="{{ $user->email }}" required/>
                </div>
                <div class="mb-4">
                  <label class="form-label">Avatar</label>
                  <div class="mb-4">
                    <img id="avatar-preview" class="img-avatar" src="{{ auth()->user()->avatar == 'default.png' ? asset('storage/avatar/avatar13.jpg') : asset('storage/avatar/'.auth()->user()->avatar) }}" alt="" />
                  </div>
                  <div class="mb-4">
                    <label for="new_avatar" class="form-label">Update Avatar</label>
                    <input id="new_avatar" class="form-control" type="file" name="avatar" />
                  </div>
                </div>
                <div class="mb-4">
                  <button type="submit" class="btn btn-alt-primary">Update</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- END User Profile -->

      <!-- Change Password -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Ubah Password</h3>
        </div>
        <div class="block-content">
          <form action="/profil/update-password" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row push">
              <div class="col-lg-4">
              </div>
              <div class="col-lg-8 col-xl-5">
                <div class="mb-4">
                  <label class="form-label" for="one-profile-edit-password">Password Sekarang</label>
                  <input type="password" class="form-control" name="old_password" />
                </div>
                <div class="row mb-4">
                  <div class="col-12">
                    <label class="form-label" for="one-profile-edit-password-new">Password Baru</label>
                    <input type="password" class="form-control" name="new_password" id="new_password" />
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col-12">
                    <label class="form-label" for="one-profile-edit-password-new-confirm">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="new_password_confirm" id="new_password_confirm" />
                    <div id="different"></div>
                  </div>
                </div>
                <div class="mb-4">
                  <button type="button" class="btn btn-alt-primary" id="btn-update-password">Update</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- END Change Password -->
    </div>
    <!-- END Page Content -->

@endsection

@section('script')

 <!-- Page JS Plugins -->
<script src="{{ URL::asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- Page JS Helpers  -->
<script>
  One.helpersOnLoad([
    "jq-notify",
  ]);
</script>

<script>
    $(document).ready(function() {
        $('#new_avatar').change(function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                $('#avatar-preview').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            }
        });

        $('#new_password_confirm').keyup(function() {
            if ($('#new_password').val() === $('#new_password_confirm').val()) {
              $('#different').html('');
            } else {
                $('#different').html('<div class="alert alert-danger" role="alert">Password tidak sama</div>');
            }
          });

          $('#btn-update-password').click(function() {
            if ($('#new_password').val() === $('#new_password_confirm').val()) {
              $.ajax({
                url: '/profil/update-password',
                type: 'POST',
                data: {
                  old_password: $('input[name=old_password]').val(),
                  new_password: $('input[name=new_password]').val(),
                  _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                  if(data.success) {
                    One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: data.message});
                    $('input[name=old_password]').val('');
                    $('input[name=new_password]').val('');
                    $('input[name=new_password_confirm]').val('');
                  } else{
                    One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: data.message});
                  }
                }
              });
            }
          });
      });

</script>
    
@endsection