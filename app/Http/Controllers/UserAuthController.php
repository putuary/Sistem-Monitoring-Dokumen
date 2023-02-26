<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        return view('user.dashboard');
    }

    public function user_management()
    {
        if(in_array(Auth::user()->role, ["kaprodi", "gkmp", "admin"])) {
            $data=User::get();
            // dd($data);

            return view('user.user-management', ['data' => $data]);
        }
        return abort(404);
    }

    public function add_user(Request $request)
    {
        if(in_array(Auth::user()->role, ["kaprodi", "gkmp", "admin"])) {
            Validator::make($request->all(), [
                'nama'      => 'required|max:255',
                'email'     => ['required', 'email', 'unique:users', 'domain:itera.ac.id'],
                'password'  => 'required|min:5|max:255',
                'role'      => 'required',
            ]);

            //create user
            User::create([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
                'avatar'    => 'default.png',
            ]);

            return redirect('/manajemen-pengguna')->with('success', 'Data berhasil ditambahkan');
        }
        return abort(404);
    }

    public function edit_user(Request $request)
    {
        // dd($request->all());
        if(in_array(Auth::user()->role, ["kaprodi", "gkmp", "admin"])) {
            Validator::make($request->all(), [
                'nama'      => 'required|max:255',
                'email'     => ['required', 'email', 'unique:users', 'domain:itera.ac.id'],
                'role'      => 'required',
            ]);

            if(is_null($request->password)) {
                User::where('id', $request->id)->update([
                    'nama'      => $request->nama,
                    'email'     => $request->email,
                    'role'      => $request->role,
                ]);
            } else {
                User::where('id', $request->id)->update([
                    'nama'      => $request->nama,
                    'email'     => $request->email,
                    'password'  => $request->password,
                    'role'      => $request->role,
                ]);
            }

            return redirect('/manajemen-pengguna')->with('success', 'Data berhasil diubah');
        }
        return abort(404);
    }

    public function delete_user(Request $request)
    {
        if(in_array(Auth::user()->role, ["kaprodi", "gkmp", "admin"])) {
            User::where('id', $request->id_pengguna)->delete();
            return redirect('/manajemen-pengguna')->with('success', 'Data berhasil dihapus');
        }
        return abort(404);
    }

}