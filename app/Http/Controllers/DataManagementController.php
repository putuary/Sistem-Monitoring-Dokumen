<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\DokumenPerkuliahan;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Storage;

class DataManagementController extends Controller
{
    public function index()
    {
        return view('admin.data-management.index');
    }

    public function showMatkul()
    {
        // dd(nameRoles('midleRole'));
        $matkul= MataKuliah::all();

        return view('admin.data-management.matkul', ['matkul' => $matkul]);
    }

    public function storeMatkul()
    {
       // dd(request()->all());
       $data = request()->validate([
            'kode_matkul' => 'required|unique:mata_kuliah',
            'nama_matkul' => 'required',
            'bobot_sks' => 'required',
            'praktikum' => 'required',
        ]);

        MataKuliah::create($data);
        
        return redirect()->back()->with('success', 'Data mata kuliah berhasil ditambahkan');
    }

    public function editMatkul(Request $request, $kode_matkul)
    {
        $request->validate([
            'kode_matkul' => 'required',
            'nama_matkul' => 'required',
            'bobot_sks' => 'required',
            'praktikum' => 'required',
        ]);

        MataKuliah::where('kode_matkul', $kode_matkul)->update([
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'bobot_sks'   => $request->bobot_sks,
            'praktikum'   => $request->praktikum,
        ]);

        return redirect()->back()->with('success', 'Data mata kuliah berhasil diubah');
      
    }

    public function deleteMatkul($kode_matkul)
    {
        try {
            MataKuliah::where('kode_matkul', $kode_matkul)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Tidak dapat menghapus parent data');
        }
    
        return redirect()->back()->with('success', 'Data mata kuliah berhasil dihapus');
      
    }

    public function showDokumen()
    {
        $dokumen= DokumenPerkuliahan::all();

        return view('admin.data-management.dokumen', ['dokumen' => $dokumen]);
    }

    public function storeDokumen(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'nama_dokumen'          => 'required',
            'tenggat_waktu_default' => 'required',
            'dikumpulkan_per'       => 'required',
            'template'              => 'mimes:docx,doc,xls,xlsx,zip|max:2048'
        ]);

        $id = IdGenerator::generate(['table' => 'dokumen_perkuliahan', 'field'=>'id_dokumen', 'length' => 6, 'prefix' => 'DP']);
        if($request->hasFile('template')) {
            // dd($request->file('template')->extension());
            $nama_dokumen= 'Template '.$request->nama_dokumen.'.'.$request->file('template')->extension();
            $request->file('template')->storeAs('public/template', $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template

            // dd($id);
            DokumenPerkuliahan::create([
                'id_dokumen'            => $id,
                'nama_dokumen'          => $request->nama_dokumen,
                'tenggat_waktu_default' => $request->tenggat_waktu_default,
                'dikumpulkan_per'       => $request->dikumpulkan_per,
                'template'              => $nama_dokumen,
        ]);
        } else {
            DokumenPerkuliahan::create([
                'id_dokumen'            => $id,
                'nama_dokumen'          => $request->nama_dokumen,
                'tenggat_waktu_default' => $request->tenggat_waktu_default,
                'dikumpulkan_per'       => $request->dikumpulkan_per,
        ]);
        }
        
        return redirect()->back()->with('success', 'Data dokumen perkuliahan berhasil ditambahkan');
    }

    public function editDokumen(Request $request, $id_dokumen)
    {
        // dd(request()->all());
        $request->validate([
            'nama_dokumen'          => 'required',
            'tenggat_waktu_default' => 'required',
            'dikumpulkan_per'       => 'required',
            'template'              => 'mimes:docx,doc,xls,xlsx,zip|max:2048'
            ]);

        $dokumen=DokumenPerkuliahan::find($id_dokumen);

        if($request->hasFile('template')) {
            Storage::delete('public/template/'.$dokumen->template);
            $nama_dokumen= 'Template-'.$request->nama_dokumen.'.'.$request->file('template')->extension();
            $request->file('template')->storeAs('public/template', $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template

            // dd($id);
            $dokumen->update([
                'nama_dokumen'          => $request->nama_dokumen,
                'tenggat_waktu_default' => $request->tenggat_waktu_default,
                'dikumpulkan_per'       => $request->dikumpulkan_per,
                'template'              => $nama_dokumen,
            ]);
        } else {
            $dokumen->update([
                'nama_dokumen'          => $request->nama_dokumen,
                'tenggat_waktu_default' => $request->tenggat_waktu_default,
                'dikumpulkan_per'       => $request->dikumpulkan_per,
            ]);
        }
        
        return redirect()->back()->with('success', 'Data dokumen perkuliahan berhasil diubah');
    }

    public function deleteDokumen($id_dokumen)
    {
        try {
            $dokumen=DokumenPerkuliahan::find($id_dokumen);
            Storage::delete('public/template/'.$dokumen->template);
            $dokumen->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Tidak dapat menghapus parent data');
        }

        return redirect()->back()->with('success', 'Data dokumen perkuliahan berhasil dihapus');
    }
}