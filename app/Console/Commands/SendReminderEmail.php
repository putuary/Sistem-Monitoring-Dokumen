<?php

namespace App\Console\Commands;

use App\Models\DokumenDitugaskan;
use App\Models\Pengingat;
use App\Notifications\ReminderEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function isSendEmail() {
        $dokumen=DokumenDitugaskan::dokumenAktif()->get();
        foreach($dokumen as $d) {
            $tenggat_waktu = Carbon::parse($d->tenggat_waktu);
            if(in_array($tenggat_waktu->diffInDays(Carbon::now()), [1, 2, 3, 4, 5, 6, 7])) {
                return true;
            }
        }
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if($this->isSendEmail()) {
            $users = User::with(['dosen_kelas' => function($query) {
                $query->with(['dokumen_kelas' => function($query) {
                    $query->with(['dokumen_ditugaskan' => function($query) {
                        $query->with('dokumen_perkuliahan');
                    }]);
                }, 'kelas_dokumen_matkul' => function($query) {
                    $query->with(['dokumen_ditugaskan' => function($query) {
                        $query->with('dokumen_perkuliahan');
                    }]);
                }])->kelasAktif();
            }])->where('role', '!=', 'admin')->get();
    
            foreach ($users as $user) {
                $remainder_before= array();
                $remainder_after= array();
                if(count($user->dosen_kelas) != 0) {
                    foreach($user->dosen_kelas as $kelas) {
                       foreach($kelas->dokumen_kelas as $dokumen_kelas) {
                           if(is_null($dokumen_kelas->file_dokumen)) {
                                $tenggat_waktu = Carbon::parse($dokumen_kelas->dokumen_ditugaskan->tenggat_waktu);
                                $diff = $tenggat_waktu->diffInDays(Carbon::now());
                                if(Carbon::now()->isAfter($tenggat_waktu)) {
                                    $remainder_after[]=[
                                        'nama_kelas' => $kelas->matkul->nama_matkul.' '.$kelas->nama_kelas,
                                        'nama_dokumen' => $dokumen_kelas->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen,
                                        'tenggat_waktu' => showWaktu($dokumen_kelas->dokumen_ditugaskan->tenggat_waktu),
                                        'waktu_terlewat' => $diff
                                    ];
                                } else {
                                    if($tenggat_waktu->diffInDays(Carbon::now()) >= 0 && $tenggat_waktu->diffInDays(Carbon::now()) <= 7) {
                                        $remainder_before[]=[
                                            'nama_kelas' => $kelas->matkul->nama_matkul.' '.$kelas->nama_kelas,
                                            'nama_dokumen' => $dokumen_kelas->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen,
                                            'tenggat_waktu' => showWaktu($dokumen_kelas->dokumen_ditugaskan->tenggat_waktu),
                                            'waktu_tersisa' => $diff
                                        ];
                                    }
                                }
                                
                           }
                       }
                       foreach($kelas->kelas_dokumen_matkul as $dokumen_matkul) {
                           if(is_null($dokumen_matkul->file_dokumen)) {
                                $tenggat_waktu = Carbon::parse($dokumen_matkul->dokumen_ditugaskan->tenggat_waktu);
                                $diff = $tenggat_waktu->diffInDays(Carbon::now());
                                if(Carbon::now()->isAfter($tenggat_waktu)) {
                                    $remainder_after[]=[
                                        'nama_kelas' => $kelas->matkul->nama_matkul.' '.$kelas->nama_kelas,
                                        'nama_dokumen' => $dokumen_matkul->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen,
                                        'tenggat_waktu' => showWaktu($dokumen_matkul->dokumen_ditugaskan->tenggat_waktu),
                                        'waktu_terlewat' => $diff
                                    ];
                                } else {
                                    if($tenggat_waktu->diffInDays(Carbon::now()) >= 0 && $tenggat_waktu->diffInDays(Carbon::now()) <= 7) {
                                        $remainder_before[]=[
                                            'nama_kelas' => $kelas->matkul->nama_matkul.' '.$kelas->nama_kelas,
                                            'nama_dokumen' => $dokumen_matkul->dokumen_ditugaskan->dokumen_perkuliahan->nama_dokumen,
                                            'tenggat_waktu' => showWaktu($dokumen_matkul->dokumen_ditugaskan->tenggat_waktu),
                                            'waktu_tersisa' => $diff
                                        ];
                                    }
                                }
                           }
                       }
                    }
                }
                $user->notify(new ReminderEmail($user->nama, $remainder_before, $remainder_after));
            }
        }
    }

}