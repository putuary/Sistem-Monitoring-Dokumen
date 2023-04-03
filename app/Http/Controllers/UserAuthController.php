<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AktifRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;


class UserAuthController extends Controller
{
    public function index()
    {
        return view('user.login');
    }

    public function authenticate(Request $request)
    {
        $errors = new MessageBag;
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // dd(Auth::user()->nama);
            return redirect()->intended('/')->with('success', 'Login Berhasil!');
        }
       // $request->session()->flash('flash', 'Welcome!');
        $errors = new MessageBag(['password' => ['username atau password salah.']]);
        return back()->withErrors($errors)->withSuccess('Login details are not valid');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/user-login');
    }

    public function dashboard()
    {
        if(in_array(Auth::user()->role, ['kaprodi', 'gkmp'] )) {
            if(Auth::user()->aktif_role->is_dosen == 0) {
                return view('admin.dashboard');
            }
            return view('dosen.dashboard-dosen');
        } else if(Auth::user()->role == "admin") {
            return view('admin.dashboard');
        }
        return view('dosen.dashboard-dosen');
    }

    public function changeDashboard()
    {
        if(in_array(Auth::user()->role, ['kaprodi', 'gkmp'] )) {
            if(Auth::user()->aktif_role->is_dosen == 0) {
                AktifRole::where('id_user', Auth::user()->id)->update([
                    'is_dosen' => 1
                ]);
            } else {
                AktifRole::where('id_user', Auth::user()->id)->update([
                    'is_dosen' => 0
                ]);
            }
            return redirect()->intended('/')->with('success', 'Login Berhasil!');
        }
        abort (403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    }

    public function user_management()
    {
        $data=User::get();
        // dd($data);

        return view('admin.user-management', ['data' => $data]);
    }

    public function add_user(Request $request)
    {
        $request->validate([
            'nama'      => 'required|max:255',
            'email'     => 'required|email|unique:users|domain:itera.ac.id',
            'password'  => 'required|min:5|max:255',
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

    public function edit_user(Request $request)
    {
        $request->validate([
            'nama'      => 'required|max:255',
            'role'      => 'required',
        ]);
    
        $pengguna=User::find($request->id);
        if($pengguna->email != $request->email) {
            $request->validate([
                'email'     => 'required|email|unique:users',
            ]);
        }

        if(is_null($request->password)) {
            User::where('id', $request->id)->update([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'role'      => $request->role,
            ]);

            CreateorDeleteAktifRole($request->id, $pengguna->role, $request->role);

        } else {
            User::where('id', $request->id)->update([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
            ]);

            CreateorDeleteAktifRole($request->id, $pengguna->role, $request->role, $request->id);
        }

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function delete_user(Request $request)
    {
        User::where('id', $request->id_pengguna)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

}