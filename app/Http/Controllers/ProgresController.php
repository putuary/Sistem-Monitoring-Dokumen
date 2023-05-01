<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\DokumenMatkul;
use App\Models\DokumenKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgresController extends Controller
{
    protected function mergeDokumen($dokumen_kelas, $dokumen_matkul)
    {
        $dokumen_all = [];
        foreach($dokumen_kelas as $dokumen) {
            $dosen=[];
            foreach($dokumen->kelas->dosen_kelas as $dsn) {
                $dosen[] = $dsn->nama;
            }
            $dokumen_all[] = (object) [
                'nama_dokumen'      => $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen,
                'matkul_kelas'      => $dokumen->kelas->matkul->nama_matkul.' '.$dokumen->kelas->nama_kelas,
                'dosen'             => $dosen,
                'waktu_pengumpulan' => $dokumen->waktu_pengumpulan,
                'tenggat_waktu'     => $dokumen->dokumen_ditugaskan->tenggat_waktu,
            ];
        }
        // dd($dokumen_all);
        
        foreach($dokumen_matkul as $dokumen) {
            $dokumen_all[] = (object) [
                'nama_dokumen'      => $dokumen->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen,
                'matkul_kelas'      => $dokumen->matkul->nama_matkul,
                'dosen'             => dosenKelas($dokumen->kelas_dokumen_matkul),
                'waktu_pengumpulan' => $dokumen->waktu_pengumpulan,
                'tenggat_waktu'     => $dokumen->dokumen_ditugaskan->tenggat_waktu,
            ];
        }
        
        // Panggil usort() dengan array dokumen beserta fungsi pengurutannya sebagai parameter
        usort($dokumen_all, function($a, $b) {
            $timeA = strtotime($a->waktu_pengumpulan);
            $timeB = strtotime($b->waktu_pengumpulan);
        
            if ($timeA == $timeB) {
                return 0;
            }
            return ($timeA > $timeB) ? -1 : 1;
        });
        
        return $dokumen_all;
    }

    public function index()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif=TahunAjaran::tahunAktif()->first();

        if(request('filter')== null || request('filter')== 'kelas') {
            $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran', 'matkul','dokumen_kelas'=> function($query) {
                $query->with('dokumen_ditugaskan');
            }, 'kelas_dokumen_matkul' => function($query) {
                $query->with('dokumen_ditugaskan');
            }])->kelasTahun(request('tahun_ajaran'))->searchKelas(request('search'))->orderBy('kode_matkul', 'asc')->get();
            // dd($kelas);
            return view('admin.progres.progres_kelas', ['kelas' => $kelas, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
        } else if(request('filter') == 'dokumen') {
            $dokumen = DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul', 'dokumen_kelas'])
            ->dokumenTahun(request('tahun_ajaran'))->searchDokumen(request('search'))->get();
            // dd($dokumen);
            return view('admin.progres.progres_dokumen', ['dokumen' => $dokumen, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
        }
    }

    public function showProgresKelas()
    {
        $kode_kelas = request('id');
        $kelas=Kelas::with('matkul')->find($kode_kelas);
            
        $dokumen=DokumenDitugaskan::with(['dokumen_perkuliahan', 'dokumen_matkul' => function($query) use ($kode_kelas) {
            $query->dokumenKelas($kode_kelas);
        }, 'dokumen_kelas' => function($query) use ($kode_kelas) {
            $query->where('kode_kelas', $kode_kelas);
        }])->whereHas('dokumen_matkul', function($query) use ($kode_kelas) {
            $query->dokumenKelas($kode_kelas);
        })->orWhereHas('dokumen_kelas', function($query) use ($kode_kelas) {
            $query->where('kode_kelas', $kode_kelas);
        })->get();

        return view('admin.progres.kelas_ditugaskan', ['kelas' => $kelas,'dokumen' => $dokumen]);
    }

    public function showProgresDokumen()
    {
        $id_dokumen = request('id');
        $dokumen=DokumenDitugaskan::with(['tahun_ajaran', 'dokumen_perkuliahan', 'dokumen_matkul' => function($query) {
            $query->with('matkul');
        }, 'dokumen_kelas' => function($query) {
            $query->with(['kelas' => function($query) {
                $query->with('matkul');
            }]);
        }])->where('id_dokumen_ditugaskan', $id_dokumen)->first();
        // dd($dokumen);
        
        return view('admin.progres.dokumen_ditugaskan', ['dokumen' => $dokumen]);
    }

    public function showRiwayat() {
        
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        // union two table

        $dokumen_kelas=DokumenKelas::with(['kelas' => function($query) {
            $query->with(['matkul', 'dosen_kelas']);
        },'dokumen_ditugaskan' => function($query) {
            $query->with('dokumen_perkuliahan');
        }])->whereHas('dokumen_ditugaskan', function($query) {
            $query->dokumenTahun(request('tahun_ajaran'));
        })->filter(request('filter'))->get();
        
        $dokumen_matkul=DokumenMatkul::with(['matkul', 'dokumen_ditugaskan' => function($query) {
            $query->with('dokumen_perkuliahan');
        }, 'kelas_dokumen_matkul' => function($query) {
            $query->with('dosen_kelas');
        }])->whereHas('dokumen_ditugaskan', function($query) {
            $query->dokumenTahun(request('tahun_ajaran'));
        })->filter(request('filter'))->get();
        
        $dokumen_all=$this->mergeDokumen($dokumen_kelas, $dokumen_matkul);

        return view('admin.riwayat.index', [
            'tahun_ajaran' => $tahun_ajaran,
            'tahun_aktif' => $tahun_aktif,
            'dokumen' => $dokumen_all,
            // 'terkumpul' => countStatusDokumen('terkumpul', request('tahun_ajaran') ?? null),
            // 'tepat_waktu' => countStatusDokumen('tepat_waktu', request('tahun_ajaran') ?? null),
            // 'terlambat' => countStatusDokumen('terlambat', request('tahun_ajaran') ?? null),
            // 'belum_terkumpul' => countStatusDokumen('belum_terkumpul', request('tahun_ajaran') ?? null),
        ]);
    }

    public function downloadArchiveDokumen(Request $request)
    {
        $tahun_ajaran = TahunAjaran::find($request->id_tahun_ajaran);

        // Get real path for our folder
        $rootPath = storage_path('app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran->tahun_ajaran));
        // dd($rootPath);

        try {
            // Initialize archive object
            $zip = new \ZipArchive();
            $fileName = str_replace('/','-',$tahun_ajaran->tahun_ajaran).'.zip';
            $zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($rootPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            // dd($files);

            foreach ($files as $name => $file)
            {
                
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    // dd($relativePath);
                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                    
                }
            }

            // Zip archive will be created only after closing object
            $zip->close();

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('failed', $th->getMessage());
        }

        return response()->download(storage_path('app/zip/'.$fileName));
    }

    public function downloadDokumen(Request $request)
    {
        $tahun_ajaran = TahunAjaran::find($request->id_tahun_ajaran);

        // Get real path for our folder
        try {
            $rootPath = storage_path('app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran->tahun_ajaran));
            // dd($rootPath);
    
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($rootPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
    
            $filearr=[];
            foreach ($files as $name => $file)
            {
                
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    if(like_match($request->nama_dokumen.'%', $file->getFilename())){
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        
                        $filearr[]= $filePath;
                    }
                    
                }
            }
            $zip = new \ZipArchive;
    
            $fileName = $request->nama_dokumen.'.zip';
        
            if ($zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE)
            {
                $files = $filearr; //passing the above array
    
                foreach ($files as $key => $value) {
                    // dd($value);
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $request->nama_dokumen.'/'.$relativeNameInZipFile);
                }
    
                $zip->close();
            }
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->back()->with('failed', $th->getMessage());
        }

        return response()->download(storage_path('app/zip/'.$fileName));
    }

    public function downloadDokumenKelas(Request $request)
    {
        // dd($request->all());

        try {
            $tahun_ajaran = TahunAjaran::find($request->id_tahun_ajaran);
    
            // Get real path for our folder
            $rootPath1 = storage_path('app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran->tahun_ajaran).'/'.$request->nama_matkul.'/'.$request->nama_kelas);
            // dd($rootPath);
    
            if(file_exists($rootPath1)) {
                $files1 = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($rootPath1),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
        
                $filearr=[];
                foreach ($files1 as $name => $file)
                {
                    
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir())
                    {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        
                        $filearr[]= $filePath;
                        
                    }
            
                }
            }
    
    
            // Get real path for our folder
            $rootPath2 = storage_path('app/dokumen-perkuliahan/'.str_replace('/','-',$tahun_ajaran->tahun_ajaran).'/'.$request->nama_matkul.'/dokumen-matkul');
            // dd($rootPath);
    
            if(file_exists($rootPath2)) {
                $files2 = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($rootPath2),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
        
                foreach ($files2 as $name => $file)
                {
                    
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir())
                    {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        
                        $filearr[]= $filePath;
                        
                    }
                }
            }
    
            // dd($filearr);
            $zip = new \ZipArchive;
    
            $fileName = $request->nama_matkul.'-'.$request->nama_kelas.'.zip';
        
            if ($zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE)
            {
                $files = $filearr; //passing the above array
    
                foreach ($files as $key => $value) {
                    $parent_dir = dirname($value);
                    if(like_match('%dokumen-matkul%', $value)) {
                        if(basename($parent_dir) != 'dokumen-matkul') {
                            $relativeNameInZipFile = basename($parent_dir).'/'.basename($value);
                        } else {
                            $relativeNameInZipFile =basename($value);
                        }
                    } else {
                        if(basename($parent_dir) != $request->nama_kelas) {
                            $relativeNameInZipFile = basename($parent_dir).'/'.basename($value);
                        } else {
                            $relativeNameInZipFile =basename($value);
                        }
                    }
                    $zip->addFile($value, $request->nama_matkul.'-'.$request->nama_kelas.'/'.$relativeNameInZipFile);
                }
    
                $zip->close();
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('failed', $th->getMessage());
        }

        
        return response()->download(storage_path('app/zip/'.$fileName));
    }
}