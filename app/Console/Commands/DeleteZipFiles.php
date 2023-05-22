<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteZipFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:zip-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderPath = storage_path('app/zip'); // Ganti dengan path folder yang ingin Anda hapus filenya

        $files = glob($folderPath . '/*'); // Mendapatkan daftar file dalam folder
    
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Menghapus file
            }
        }
    
        $this->info('Files deleted successfully.');
    }
}