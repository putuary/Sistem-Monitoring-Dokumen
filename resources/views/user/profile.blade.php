@extends('layouts.user-base')
@section('title', 'Profil')

@section('content')
    <!-- Hero -->
    <div class="bg-image" style="background-image: url('assets/media/photos/photo12@2x.jpg')">
      <div class="bg-black-50">
        <div class="content content-full text-center">
          <div class="my-3">
            <img class="img-avatar img-avatar-thumb" src="{{ (auth()->user()->avatar == null) ? asset('storage/avatar/avatar13.jpg') : asset('storage/avatar/'.auth()->user()->avatar) }}" alt="" />
          </div>
          <h1 class="h2 text-white mb-0">{{ $user->nama }}</h1>
          <span class="text-white-75">{{ isset($user->aktif_role) ? ($user->aktif_role->is_dosen==0 ? namaPeran($user->role) : 'Dosen Pengampu') : namaPeran($user->role) }}</span>
        </div>
      </div>
    </div>
    <!-- END Hero -->

    <!-- Stats -->
    @if (isset($user_badges))
    <div class="bg-body-extra-light">
      <div class="content content-boxed">
        <div class="row items-push text-center">
        @foreach ($user_badges as $badge)
          <div class="col-6 col-md-3">
            <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('storage/badges/'.$badge->gambar) }}" alt="">
            <p class="mb-0 text-muted fs-sm fw-medium">{{ $badge->total.'x' }}</p>
          </div>
        @endforeach
        </div>
      </div>
    </div>
    @endif
    <!-- END Stats -->

    <!-- Page Content -->
    <div class="content content-boxed">
      
       <!-- pop up success upload -->
      @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @error('email')
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @enderror

      @error('avatar')
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @enderror

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
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="one-profile-edit-email" name="email" placeholder="Masukkan email anda" value="{{ $user->email }}" required/>
                </div>
                <div class="mb-4">
                  <label class="form-label">Avatar</label>
                  <div class="mb-4">
                    <img id="avatar-preview" class="img-avatar" src="{{ (auth()->user()->avatar == null) ? asset('storage/avatar/avatar13.jpg') : asset('storage/avatar/'.auth()->user()->avatar) }}" alt="Avatar" />
                  </div>
                  <div class="mb-4">
                    <label for="new_avatar" class="form-label">Update Avatar (format: jpeg,png,jpg,gif,svg Max: 2MB)</label>
                    <input id="new_avatar" class="form-control @error('avatar') is-invalid @enderror" type="file" name="avatar" />
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
                  <input type="password" class="form-control" name="old_password" required/>
                </div>
                <div class="row mb-4">
                  <div class="col-12">
                    <label class="form-label" for="one-profile-edit-password-new">Password Baru</label>
                    <input type="password" class="form-control" name="new_password" id="new_password" required/>
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col-12">
                    <label class="form-label" for="one-profile-edit-password-new-confirm">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="new_password_confirm" id="new_password_confirm" required/>
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
        $(".alert").delay(2000).fadeOut("slow");
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
                $('#different').html('<div class="alert alert-danger" role="alert">Konfirmasi password baru tidak sama</div>');
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