<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenDitugaskan;
use App\Models\MataKuliah;
use App\Models\DokumenPerkuliahan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

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
        
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
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

        return redirect()->back()->with('success', 'Data berhasil diubah');
      
    }

    public function deleteMatkul(Request $request)
    {
        try {
            MataKuliah::where('kode_matkul', $request->kode_matkul)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Tidak dapat menghapus parent data');
        }
    
        return redirect()->back()->with('success', 'Data berhasil dihapus');
      
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
            'template'              => 'mimes:docx,doc,xls,xlsx|max:3072'
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
        
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function editDokumen(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'id_dokumen'            => 'required',
            'nama_dokumen'          => 'required',
            'tenggat_waktu_default' => 'required',
            'dikumpulkan_per'       => 'required',
            'template'              => 'mimes:docx,doc,xls,xlsx|max:3072'
            ]);

        if($request->hasFile('template')) {
            // dd($request->file('template')->extension());
            $nama_dokumen= 'Template-'.$request->nama_dokumen.'.'.$request->file('template')->extension();
            $request->file('template')->storeAs('public/template', $nama_dokumen); // 'public' adalah nama folder di storage/app/public/template

            // dd($id);
            DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen)->update([
                'nama_dokumen'          => $request->nama_dokumen,
                'tenggat_waktu_default' => $request->tenggat_waktu_default,
                'dikumpulkan_per'       => $request->dikumpulkan_per,
                'template'              => $nama_dokumen,
            ]);
        } else {
            DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen)->update([
                'nama_dokumen'          => $request->nama_dokumen,
                'tenggat_waktu_default' => $request->tenggat_waktu_default,
                'dikumpulkan_per'       => $request->dikumpulkan_per,
            ]);
        }
        
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function deleteDokumen(Request $request)
    {
        try {
            DokumenPerkuliahan::where('id_dokumen', $request->id_dokumen)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Tidak dapat menghapus parent data');
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

}