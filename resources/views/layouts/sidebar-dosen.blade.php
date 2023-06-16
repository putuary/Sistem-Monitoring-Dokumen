<li class="nav-main-heading">KELAS DAN DOKUMEN</li>

<li class="nav-main-item">
    <a class="nav-main-link {{ (Request::is('kelas-diampu') || Request::is('kelas-diampu/*')) ? 'active' : '' }}" href="/kelas-diampu">
        <i class="nav-main-link-icon fa fa-chalkboard-user"></i>
        <span class="nav-main-link-name">Kelas Diampu</span>
    </a>
</li>

<li class="nav-main-item">
    <a class="nav-main-link {{ Request::is('riwayat-pengumpulan-perolehan-poin') ? 'active' : '' }}" href="/riwayat-pengumpulan-perolehan-poin">
      <i class="nav-main-link-icon far fa-clock"></i>
      <span class="nav-main-link-name">Riwayat Pengumpulan dan Perolehan Poin</span>
    </a>
  </li>

<li class="nav-main-item">
    <a class="nav-main-link {{ (Request::is('dokumen-perkuliahan') || Request::is('dokumen-perkuliahan/*')) ? 'active' : '' }}" href="/dokumen-perkuliahan">
        <i class="nav-main-link-icon far fa-file-lines"></i>
        <span class="nav-main-link-name">Dokumen Perkuliahan</span>
    </a>
</li>

<li class="nav-main-heading">PERINGKAT PENGUMPULAN</li>

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




