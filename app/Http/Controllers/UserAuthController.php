<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AktifRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

    public function profile()
    {
        if(in_array(Auth::user()->role, ['kaprodi', 'gkmp'] )) {
            if(Auth::user()->aktif_role->is_dosen == 1) {
                return view('user.profile', [
                    'user'         => User::with('aktif_role')->where('id', Auth::user()->id)->first(),
                    'user_badges'  => auth()->user()->user_badge()->selectRaw('user_badges.id_user, user_badges.id_badge, nama_badge, gambar, COUNT(*) as total')
                                    ->groupBy('user_badges.id_user', 'user_badges.id_badge', 'nama_badge', 'gambar')
                                    ->get(),
                ]);
            } 
        } else if(Auth::user()->role == "dosen") {
            return view('user.profile', [
                'user'         => Auth::user(),
                'user_badges'  => auth()->user()->user_badge()->selectRaw('user_badges.id_user, user_badges.id_badge, nama_badge, gambar, COUNT(*) as total')
                                ->groupBy('user_badges.id_user', 'user_badges.id_badge', 'nama_badge', 'gambar')
                                ->get(),
            ]);
        } return view('user.profile', [
            'user'  => Auth::user(),
        ]);
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

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama'      => 'required|max:255',
        ]);

        if(Auth::user()->email != $request->email) {
            $request->validate([
                'email'     => 'required|email|unique:users',
            ]);
        }

        if($request->hasFile('avatar')) {
            Storage::delete('public/avatar/' . Auth::user()->avatar);
            $fileName = Auth::user()->nama.'-'.Auth::user()->id.'.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('public/avatar/', $fileName);
            User::where('id', Auth::user()->id)->update([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'avatar'    => $fileName,
            ]);

        } else {
            User::where('id', Auth::user()->id)->update([
                'nama'      => $request->nama,
                'email'     => $request->email,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function updatePassword(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'old_password'  => 'required|min:5|max:30',
            'new_password'  => 'required|min:5|max:30',
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if(Hash::check($request->old_password, Auth::user()->password)) {
            User::where('id', Auth::user()->id)->update([
                'password'  => Hash::make($request->new_password),
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ]);
        }
    }

}