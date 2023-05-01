<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges=Badge::all();
        // dd($badges);
        
        return view('admin.data-management.badge', ['badges' => $badges]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_badge'    => 'required',
            'gambar'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'deskripsi'     => 'required',
        ]);

        if($request->hasFile('gambar')) {
            Badge::where('id', $id)->update([
                'nama_badge'    => $request->nama_badge,
                'gambar'        => $request->gambar,
                'deskripsi'     => $request->deskripsi,
            ]);
        } else {
            Badge::where('id', $id)->update([
                'nama_badge'    => $request->nama_badge,
                'deskripsi'     => $request->deskripsi,
            ]);
        }
        return redirect()->back()->with('success', 'Data bersil diubah');
    }
}