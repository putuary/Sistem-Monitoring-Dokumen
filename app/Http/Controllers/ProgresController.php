<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CatatanPenolakan;
use App\Models\DokumenDitugaskan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\DokumenMatkul;
use App\Models\DokumenKelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class ProgresController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif=TahunAjaran::tahunAktif()->first();

        if(request('filter')== null || request('filter')== 'kelas') {
            $kelas = Kelas::with(['dosen_kelas', 'tahun_ajaran', 'matkul','dokumen_kelas'=> function($query) {
                $query->with('dokumen_ditugaskan');
            }, 'kelas_dokumen_matkul' => function($query) {
                $query->with('dokumen_ditugaskan');
            }])->kelasTahun(request('tahun_ajaran'))->searchKelas(request('search'))->orderBy('id_matkul_dibuka', 'asc')->get();
            // dd($kelas);
            return view('admin.progres.progres_kelas', ['kelas' => $kelas, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
        } else if(request('filter') == 'dokumen') {
            $dokumen = DokumenDitugaskan::with(['dokumen_matkul', 'dokumen_kelas'])
            ->dokumenTahun(request('tahun_ajaran'))->searchDokumen(request('search'))->get();
            // dd($dokumen);
            return view('admin.progres.progres_dokumen', ['dokumen' => $dokumen, 'tahun_ajaran' => $tahun_ajaran, 'tahun_aktif' => $tahun_aktif]);
        }
    }

    public function showProgresKelas()
    {
        $kode_kelas = request('id');
        $kelas=Kelas::with('matkul')->find($kode_kelas);
            
        $dokumen=DokumenDitugaskan::with(['dokumen_matkul' => function($query) use ($kode_kelas) {
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
        $dokumen=DokumenDitugaskan::with(['tahun_ajaran', 'dokumen_matkul' => function($query) {
            $query->with('matkul')->orderByRaw("ISNULL(waktu_pengumpulan), waktu_pengumpulan ASC");
        }, 'dokumen_kelas' => function($query) {
            $query->with(['kelas' => ['matkul']])->orderByRaw("ISNULL(waktu_pengumpulan), waktu_pengumpulan ASC");
        }])->where('id_dokumen_ditugaskan', $id_dokumen)->first();
        // dd($dokumen);
        
        return view('admin.progres.dokumen_ditugaskan', ['dokumen' => $dokumen]);
    }

    public function storeCatatan(Request $request)
    {
        $dokumen=DokumenDikumpulController::showProfilDokumen($request->id_dokumen_terkumpul);
        $dokumen->dokumen_dikumpul->load('scores');
        // dd($dokumen->dokumen_dikumpul->scores);
        $dokumen->dokumen_dikumpul->note()->where('is_aktif', 0)->delete();
        
        $dokumen->dokumen_dikumpul->note()->create([
            'isi_catatan' => $request->isi_catatan,
            'is_aktif' => 1,
        ]);
        
        if($request->nama_dokumen) {
            // Cek apakah file ada di dalam folder
            if (file_exists($dokumen->path_multiple . '/' . $request->nama_dokumen)) {
                // Hapus file
                unlink($dokumen->path_multiple . '/' . $request->nama_dokumen);
                
                if($dokumen->dokumen_dikumpul->scores[0]->bonus != null) {
                    updateBonusDokumen($dokumen->dokumen_dikumpul->dokumen_ditugaskan->id_dokumen_ditugaskan, $dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpulkan_per);
                 }

                $dokumen->dokumen_dikumpul->scores()->update([
                    'poin' => -50,
                    'bonus' => null,
                ]);

                $storage = array_diff(scandir($dokumen->path_multiple, SCANDIR_SORT_ASCENDING), array('.', '..'));
                if(count($storage) == 0) {
                    $dokumen->dokumen_dikumpul->update([
                        'file_dokumen'      => null,
                        'waktu_pengumpulan' => null,
                    ]);
                }
                
                return redirect()->back()->with('success', 'Catatan berhasil disimpan');
            } else{
                return redirect()->back()->with('failed', 'File dokumen tidak ada');
            }
        }
        
        if($dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpul==0) {
            File::delete($dokumen->path_dokumen);
        } else {
            File::deleteDirectory($dokumen->path_dokumen);
        }
        
        $dokumen->dokumen_dikumpul->update([
            'file_dokumen'      => null,
            'waktu_pengumpulan' => null,
        ]);
        
        if($dokumen->dokumen_dikumpul->scores[0]->bonus != null) {
            updateBonusDokumen($dokumen->dokumen_dikumpul->dokumen_ditugaskan->id_dokumen_ditugaskan, $dokumen->dokumen_dikumpul->dokumen_ditugaskan->dikumpulkan_per);
         }
         
        $dokumen->dokumen_dikumpul->scores()->update([
            'poin' => -50,
            'bonus' => null,
        ]);
        

        return redirect()->back()->with('success', 'Catatan berhasil disimpan');
    }

    public function showRiwayat() {
        
        $tahun_ajaran = TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get();
        $tahun_aktif = TahunAjaran::tahunAktif()->first();
        
        $kelas = Kelas::with(['dokumen_kelas' => function($query) {
                $query->with('dokumen_ditugaskan')->filter(request('filter'));
                }, 'matkul','dosen_kelas','kelas_dokumen_matkul' => function($query) {
                    $query->with('dokumen_ditugaskan')->filter(request('filter'));
                }])->kelasTahun(request('tahun_ajaran'))->get();
        $dokumen_all=mergerDokumen($kelas);
        

        return view('admin.riwayat.index', [
            'tahun_ajaran' => $tahun_ajaran,
            'tahun_aktif' => $tahun_aktif,
            'dokumen' => $dokumen_all,
        ]);
    }

    public function downloadAllDokumenKelas(Request $request)
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
            
            return response()->download(storage_path('app/zip/'.$fileName));
        
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('failed', 'Gagal mengunduh dokumen, periksa kembali dokumen yang diunduh!');
        }
    }

    public function downloadAllDokumen($id_tahun_ajaran) 
    {
        try {
            $dokumens = DokumenDitugaskan::with(['tahun_ajaran', 'dokumen_matkul' => function($query) {
                $query->whereNotNull('file_dokumen')->with('matkul');
            }, 'dokumen_kelas' => function($query) {
                $query->whereNotNull('file_dokumen')->with(['kelas' => function($query) {
                    $query->with('matkul');
                }]);
            }])->whereHas('dokumen_matkul', function($query) {
                $query->whereNotNull('file_dokumen');
            })->orWhereHas('dokumen_kelas', function($query) {
                $query->whereNotNull('file_dokumen');
            })->where('id_tahun_ajaran', $id_tahun_ajaran)->get();
            // dd($dokumens); 
            // dd($dokumen);
            $zip = new \ZipArchive;
    
            $fileName = (($dokumens != null ) ? str_replace('/','-',$dokumens[0]->tahun_ajaran->tahun_ajaran) : 'dokumen').'.zip';
            
            $zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach($dokumens as $dokumen) {
                if($dokumen->dikumpulkan_per == 0) {
                    if($dokumen->dikumpul==0) {
                        foreach($dokumen->dokumen_matkul as $dokumen_matkul) {
                            // dd($dokumen_matkul);
                            $filePathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, true, $dokumen_matkul->matkul->nama_matkul).'/'.$dokumen_matkul->file_dokumen);
                            $relativeNameInZipFile = basename($filePathDokumen);
                            // dd($filePathDokumen);
                            $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$relativeNameInZipFile);
                        } 
                    } else {
                        foreach($dokumen->dokumen_matkul as $dokumen_matkul) {
                            // dd($dokumen_matkul);
                            $pathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, true, $dokumen_matkul->matkul->nama_matkul).'/'.$dokumen->nama_dokumen);
                            // dd($pathDokumen);
                            $files = array_diff(scandir($pathDokumen, SCANDIR_SORT_ASCENDING), array('.', '..'));
                            // dd($files);
                            foreach($files as $file) {
                                $filePathDokumen = $pathDokumen.'/'.$file;
                                $relativeNameInZipFile = basename($filePathDokumen);
                                $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$dokumen_matkul->matkul->nama_matkul.'/'.$relativeNameInZipFile);
                            }
                        }
                    }
                } else {
                    if($dokumen->dikumpul==0) {
                        foreach($dokumen->dokumen_kelas as $dokumen_kelas) {
                            $filePathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, false, $dokumen_kelas->kelas->matkul->nama_matkul, $dokumen_kelas->kelas->nama_kelas).'/'.$dokumen_kelas->file_dokumen);
                            $relativeNameInZipFile = basename($filePathDokumen);
                            $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$dokumen_kelas->kelas->matkul->nama_matkul.'/'.$relativeNameInZipFile);
                        }
                    } else {
                        foreach($dokumen->dokumen_kelas as $dokumen_kelas) {
                            $pathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, false, $dokumen_kelas->kelas->matkul->nama_matkul, $dokumen_kelas->kelas->nama_kelas).'/'.$dokumen->nama_dokumen);
                            $files = array_diff(scandir($pathDokumen, SCANDIR_SORT_ASCENDING), array('.', '..'));
                            foreach($files as $file) {
                                $filePathDokumen = $pathDokumen.'/'.$file;
                                $relativeNameInZipFile = basename($filePathDokumen);
                                $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$dokumen_kelas->kelas->matkul->nama_matkul.'/'.$dokumen_kelas->kelas->nama_kelas.'/'.$relativeNameInZipFile);
                            }
                        }
                    }
                }
            }

            $zip->close();

            return response()->download(storage_path('app/zip/'.$fileName));
            
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    public function downloadFileDokumen($id_dokumen_ditugaskan) {
        // dd($request->all());
        try {
            $dokumen = DokumenDitugaskan::with('tahun_ajaran')->find($id_dokumen_ditugaskan);
    
            // dd($dokumen);
            $zip = new \ZipArchive;
    
            $fileName = $dokumen->nama_dokumen.'.zip';

            $zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if($dokumen->dikumpulkan_per == 0) {
                $dokumen->load(['dokumen_matkul' => function($query) {
                    $query->whereNotNull('file_dokumen')->with('matkul');
                }]);
                if($dokumen->dikumpul==0) {
                    foreach($dokumen->dokumen_matkul as $dokumen_matkul) {
                        // dd($dokumen_matkul);
                        $filePathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, true, $dokumen_matkul->matkul->nama_matkul).'/'.$dokumen_matkul->file_dokumen);
                        $relativeNameInZipFile = basename($filePathDokumen);
                        // dd($filePathDokumen);
                        $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$relativeNameInZipFile);
                    } 
                } else {
                    foreach($dokumen->dokumen_matkul as $dokumen_matkul) {
                        $pathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, true, $dokumen_matkul->matkul->nama_matkul).'/'.$dokumen->nama_dokumen);
                        // dd($pathDokumen);
                        $files = array_diff(scandir($pathDokumen, SCANDIR_SORT_ASCENDING), array('.', '..'));
                        // dd($files);
                        foreach($files as $file) {
                            $filePathDokumen = $pathDokumen.'/'.$file;
                            $relativeNameInZipFile = basename($filePathDokumen);
                            $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$dokumen_matkul->matkul->nama_matkul.'/'.$relativeNameInZipFile);
                        }
                    }
                }
            } else {
                $dokumen->load(['dokumen_kelas' => function($query) {
                    $query->whereNotNull('file_dokumen')->with(['kelas' => function($query) {
                        $query->with('matkul');
                    }]);
                }]);
                if($dokumen->dikumpul==0) {
                    foreach($dokumen->dokumen_kelas as $dokumen_kelas) {
                        $filePathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, false, $dokumen_kelas->kelas->matkul->nama_matkul, $dokumen_kelas->kelas->nama_kelas).'/'.$dokumen_kelas->file_dokumen);
                        $relativeNameInZipFile = basename($filePathDokumen);
                        $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$dokumen_kelas->kelas->matkul->nama_matkul.'/'.$relativeNameInZipFile);
                    }
                } else {
                    foreach($dokumen->dokumen_kelas as $dokumen_kelas) {
                        $pathDokumen = storage_path(pathDokumen($dokumen->tahun_ajaran->tahun_ajaran, false, $dokumen_kelas->kelas->matkul->nama_matkul, $dokumen_kelas->kelas->nama_kelas).'/'.$dokumen->nama_dokumen);
                        $files = array_diff(scandir($pathDokumen, SCANDIR_SORT_ASCENDING), array('.', '..'));
                        foreach($files as $file) {
                            $filePathDokumen = $pathDokumen.'/'.$file;
                            $relativeNameInZipFile = basename($filePathDokumen);
                            $zip->addFile($filePathDokumen, $dokumen->nama_dokumen.'/'.$dokumen_kelas->kelas->matkul->nama_matkul.'/'.$dokumen_kelas->kelas->nama_kelas.'/'.$relativeNameInZipFile);
                        }
                    }
                }
            }

            $zip->close();

            return response()->download(storage_path('app/zip/'.$fileName));
            
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->back()->with('failed', $th->getMessage());
        }
        
    }

    public function downloadKelasDokumen($kode_kelas) {
        try {
            $kelas=Kelas::with(['matkul', 'tahun_ajaran', 'dokumen_kelas' => function($query) {
                $query->with('dokumen_ditugaskan')->whereNotNull('file_dokumen');
                }, 'kelas_dokumen_matkul' => function($query) {
                    $query->with('dokumen_ditugaskan')->whereNotNull('file_dokumen');
                }])->find($kode_kelas);
        
            $zip = new \ZipArchive;
            $fileName = $kelas->matkul->nama_matkul.'-'.$kelas->nama_kelas.'.zip';
            $zip->open(storage_path('app/zip/'.$fileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            
            foreach($kelas->kelas_dokumen_matkul as $dokumen_matkul) {
                // dd($dokumen_matkul);
                if($dokumen_matkul->dokumen_ditugaskan->dikumpul==0) {
                    $filePathDokumen = storage_path(pathDokumen($kelas->tahun_ajaran->tahun_ajaran, true, $kelas->matkul->nama_matkul).'/'.$dokumen_matkul->file_dokumen);
                    $relativeNameInZipFile = basename($filePathDokumen);
                    // dd($filePathDokumen);
                    $zip->addFile($filePathDokumen, ($kelas->matkul->nama_matkul.'-'.$kelas->nama_kelas).'/'.$relativeNameInZipFile);
                } else {
                    $pathDokumen = storage_path(pathDokumen($kelas->tahun_ajaran->tahun_ajaran, true, $kelas->matkul->nama_matkul).'/'.$dokumen_matkul->dokumen_ditugaskan->nama_dokumen);
                    // dd($pathDokumen);
                    $files = array_diff(scandir($pathDokumen, SCANDIR_SORT_ASCENDING), array('.', '..'));
                    // dd($files);
                    foreach($files as $file) {
                        $filePathDokumen = $pathDokumen.'/'.$file;
                        $relativeNameInZipFile = basename($filePathDokumen);
                        $zip->addFile($filePathDokumen, ($kelas->matkul->nama_matkul.'-'.$kelas->nama_kelas).'/'.$dokumen_matkul->dokumen_ditugaskan->nama_dokumen.'/'.$relativeNameInZipFile);
                    }
                }
            }

            foreach($kelas->dokumen_kelas as $dokumen_kelas) {
                if($dokumen_kelas->dokumen_ditugaskan->dikumpul==0) {
                    $filePathDokumen = storage_path(pathDokumen($kelas->tahun_ajaran->tahun_ajaran, false, $kelas->matkul->nama_matkul, $kelas->nama_kelas).'/'.$dokumen_kelas->file_dokumen);
                    $relativeNameInZipFile = basename($filePathDokumen);
                    $zip->addFile($filePathDokumen, ($kelas->matkul->nama_matkul.'-'.$kelas->nama_kelas).'/'.$relativeNameInZipFile);
                } else {
                    $pathDokumen = storage_path(pathDokumen($kelas->tahun_ajaran->tahun_ajaran, false, $kelas->matkul->nama_matkul, $kelas->nama_kelas).'/'.$dokumen_kelas->dokumen_ditugaskan->nama_dokumen);
                    $files = array_diff(scandir($pathDokumen, SCANDIR_SORT_ASCENDING), array('.', '..'));
                    foreach($files as $file) {
                        $filePathDokumen = $pathDokumen.'/'.$file;
                        $relativeNameInZipFile = basename($filePathDokumen);
                        $zip->addFile($filePathDokumen, ($kelas->matkul->nama_matkul.'-'.$kelas->nama_kelas).'/'.$dokumen_kelas->dokumen_ditugaskan->nama_dokumen.'/'.$relativeNameInZipFile);
                    }
                }
            }

            $zip->close();

            return response()->download(storage_path('app/zip/'.$fileName));

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('failed', $th->getMessage());
        }
        
    }

    public function showReport() {
        $id_tahun_ajaran=request('tahun_ajaran');
        $tahun_ajaran=TahunAjaran::find($id_tahun_ajaran);
        $dokumen_ditugaskan= DokumenDitugaskan::dokumenTahun($id_tahun_ajaran)->orderBy('id_dokumen_ditugaskan', 'asc')->get();

        $kelas=Kelas::with(['dosen_kelas', 'matkul','dokumen_kelas' => function($query) use($id_tahun_ajaran) {
            $query->with(['dokumen_ditugaskan' => function($query) use($id_tahun_ajaran) {
                $query->dokumenTahun($id_tahun_ajaran);
                }]);
            }, 'kelas_dokumen_matkul' => function($query) use($id_tahun_ajaran) {
                $query->with(['dokumen_ditugaskan' => function($query) use($id_tahun_ajaran) {
                    $query->dokumenTahun($id_tahun_ajaran);
                    }]);
                }])->kelasTahun($id_tahun_ajaran)->orderBy('id_matkul_dibuka', 'asc')->get();

        // dd($kelas);
        $report=showReport($dokumen_ditugaskan, $kelas);

        // dd($report);
        // $pdf = Pdf::loadView('admin.progres.report', ['dokumen' => $dokumen_ditugaskan, 'report' => $report]);
        // return $pdf->download('laporan.pdf');

        return view('admin.progres.tampil-resume', ['tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen_ditugaskan, 'report' => $report]);
    }

    public function generateReport() {
        $id_tahun_ajaran=request('tahun_ajaran');
        $tahun_ajaran=TahunAjaran::find($id_tahun_ajaran);
        $dokumen_ditugaskan= DokumenDitugaskan::dokumenTahun($id_tahun_ajaran)->orderBy('id_dokumen_ditugaskan', 'asc')->get();

        $kelas=Kelas::with(['dosen_kelas', 'matkul','dokumen_kelas' => function($query) use($id_tahun_ajaran) {
            $query->with(['dokumen_ditugaskan' => function($query) use($id_tahun_ajaran) {
                $query->dokumenTahun($id_tahun_ajaran);
                }]);
            }, 'kelas_dokumen_matkul' => function($query) use($id_tahun_ajaran) {
                $query->with(['dokumen_ditugaskan' => function($query) use($id_tahun_ajaran) {
                    $query->dokumenTahun($id_tahun_ajaran);
                    }]);
                }])->kelasTahun($id_tahun_ajaran)->orderBy('id_matkul_dibuka', 'asc')->get();

        // dd($kelas);
        $report=showReport($dokumen_ditugaskan, $kelas);

        // dd($report);
        $pdf = Pdf::loadView('admin.progres.report', ['tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen_ditugaskan, 'report' => $report]);
        return $pdf->download('laporan.pdf');

        return view('admin.progres.report', ['tahun_ajaran' => $tahun_ajaran, 'dokumen' => $dokumen_ditugaskan, 'report' => $report]);
    }
}