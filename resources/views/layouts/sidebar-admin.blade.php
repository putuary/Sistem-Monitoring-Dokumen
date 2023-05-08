<li class="nav-main-heading">Data</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ Request::is('manajemen-pengguna') ? 'active' : '' }}" href="/manajemen-pengguna">
    <i class="nav-main-link-icon si si-user"></i>
    <span class="nav-main-link-name">Manajemen Pengguna</span>
  </a>
</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ Request::is('manajemen-data') ? 'active' : '' }}" href="/manajemen-data">
    <i class="nav-main-link-icon si si-folder-alt"></i>
    <span class="nav-main-link-name">Manajemen Data</span>
  </a>
</li>

<li class="nav-main-heading">DOKUMEN PERKULIAHAN</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ (Request::is('penugasan') || Request::is('penugasan/*')) ? 'active' : '' }}" href="/penugasan">
    <i class="nav-main-link-icon far fa-id-badge"></i>
    <span class="nav-main-link-name">Penugasan</span>
  </a>
</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ (Request::is('progres-pengumpulan') || Request::is('progres-pengumpulan/*')) ? 'active' : '' }}" href="/progres-pengumpulan">
    <i class="nav-main-link-icon fa fa-bars-progress"></i>
    <span class="nav-main-link-name">Progres Pengumpulan</span>
  </a>
</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ Request::is('riwayat-pengumpulan') ? 'active' : '' }}" href="/riwayat-pengumpulan">
    <i class="nav-main-link-icon far fa-clock"></i>
    <span class="nav-main-link-name">Riwayat Pengumpulan</span>
  </a>
</li>

<li class="nav-main-heading">PENGINGAT</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ Request::is('atur-pengingat-pengumpulan') ? 'active' : '' }}" href="/atur-pengingat-pengumpulan">
    <i class="nav-main-link-icon fa fa-clock-rotate-left"></i>
    <span class="nav-main-link-name">Atur Pengingat dan Pengumpulan</span>
  </a>
</li>

<li class="nav-main-heading">MANAJEMEN PERINGKAT</li>

<li class="nav-main-item">
  <a class="nav-main-link {{ Request::is('leaderboard') ? 'active' : '' }}" href="/leaderboard">
    <i class="nav-main-link-icon fa fa-ranking-star"></i>
    <span class="nav-main-link-name">Leaderboard</span>
  </a>
</li>
<li class="nav-main-item">
  <a class="nav-main-link {{ Request::is('badge') ? 'active' : '' }}" href="/badge">
    <i class="nav-main-link-icon si si-badge"></i>
    <span class="nav-main-link-name">Badge</span>
  </a>
</li>