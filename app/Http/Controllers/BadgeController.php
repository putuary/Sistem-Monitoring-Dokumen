<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // dd($request->all());
        $request->validate([
            'nama_badge'    => 'required',
            'gambar'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'deskripsi'     => 'required',
        ]);

        $badge = Badge::find($id);

        if($request->hasFile('gambar')) {
            Storage::delete('public/badges/' . $badge->gambar);
            
            $fileName = $request->nama_badge . '.' . $request->file('gambar')->extension();
            $request->file('gambar')->storeAs('public/badges', $fileName);
            
            $badge->update([
                'nama_badge'    => $request->nama_badge,
                'gambar'        => $fileName,
                'deskripsi'     => $request->deskripsi,
            ]);
        } else {
            $badge->update([
                'nama_badge'    => $request->nama_badge,
                'deskripsi'     => $request->deskripsi,
            ]);
        }
        return redirect()->back()->with('success', 'Data badge berhasil diubah');
    }

    public function showAllBadge()
    {
        $badges=Badge::all();
        
        return view('user.gamifikasi.badge', ['badges' => $badges]);
    }
}