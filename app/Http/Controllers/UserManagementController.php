<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $data=User::get();
        // dd($data);

        return view('admin.user-management', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:5|max:30',
            'role'      => 'required',
        ]);

        //create user
        $user=User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'avatar'    => 'default.png',
        ]);

        if(in_array($request->role, ['kaprodi', 'gkmp'])) {
            $user->aktif_role()->create([
                'is_dosen'  => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'      => 'required|max:255',
            'role'      => 'required',
        ]);
    
        $pengguna=User::find($id);
        if($pengguna->email != $request->email) {
            $request->validate([
                'email'     => 'required|email|unique:users',
            ]);
        }

        if(is_null($request->password)) {
            User::where('id', $id)->update([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'role'      => $request->role,
            ]);

            CreateorDeleteAktifRole($id, $pengguna->role, $request->role);

        } else {
            User::where('id', $id)->update([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
            ]);

            CreateorDeleteAktifRole($request->id, $pengguna->role, $request->role, $request->id);
        }

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        try {
            $user=User::with('dosen_kelas')->find($id);
            if(in_array($user->role, ['kaprodi', 'gkmp']) && count($user->dosen_kelas) == 0) {
                $user->aktif_role()->delete();
            }
            $user->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Tidak dapat menghapus parent data');
        }
    
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}