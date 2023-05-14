<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AktifRole;
use App\Models\DokumenDitugaskan;
use App\Models\DokumenKelas;
use App\Models\DokumenMatkul;
use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;
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

    public function adminDashboard()
    {
        $dokumen_ditugaskan= DokumenDitugaskan::dokumenAktif()->orderBy('id_dokumen_ditugaskan', 'asc')->get();

        $kelas=Kelas::with(['dosen_kelas', 'matkul','dokumen_kelas' => function($query) {
            $query->with('dokumen_ditugaskan');
            }, 'kelas_dokumen_matkul' => function($query) {
                $query->with('dokumen_ditugaskan');
            }])->kelasAktif()->orderBy('id_matkul_dibuka', 'asc')->get();

        // dd(count($kelas));
        $report=showReport($dokumen_ditugaskan, $kelas);

        try {
            $persentase_dikumpulkan=round(($report->total_dikumpul/$report->total_ditugaskan)*100, 2);
        } catch (\Throwable $th) {
            $persentase_dikumpulkan=0;
        }

        $jumlahKelas=count($kelas);

        $jumlahDosen=User::where('role', '!=', 'admin')->count();
        

        return view('admin.dashboard', [
            'report' => $report,
            'persentase_dikumpulkan' => $persentase_dikumpulkan,
            'jumlahKelas' => $jumlahKelas,
            'jumlahDosen' => $jumlahDosen,
        ]);
    }

    public function dosenDashboard()
    {
        $dokumen_ditugaskan= DokumenDitugaskan::dokumenAktif()->orderBy('id_dokumen_ditugaskan', 'asc')->get();
        
        $kelas = Kelas::with(['dokumen_kelas' => function($query) {
            $query->with('dokumen_ditugaskan');
        }, 'matkul','kelas_dokumen_matkul' => function($query) {
            $query->with('dokumen_ditugaskan');
        }])->kelasDiampu()->kelasAktif()->orderBy('id_matkul_dibuka', 'asc')->get();

        $report=showReport($dokumen_ditugaskan, $kelas);

        try {
            $persentase_dikumpulkan=round(($report->total_dikumpul/$report->total_ditugaskan)*100, 2);
        } catch (\Throwable $th) {
            $persentase_dikumpulkan=0;
        }
        // dd($report);
        
        $jumlahKelas=count($kelas);

        return view('dosen.dashboard-dosen', [
            'persentase_dikumpulkan' => $persentase_dikumpulkan,
            'report' => $report,
            'jumlahKelas' => $jumlahKelas,
        ]);
    }

    public function dashboard()
    {
        if(in_array(Auth::user()->role, ['kaprodi', 'gkmp'] )) {
            if(Auth::user()->aktif_role->is_dosen == 0) {
                return $this->adminDashboard();
            }
            return $this->dosenDashboard();
        } else if(Auth::user()->role == "admin") {
            return $this->adminDashboard();
        }
        return $this->dosenDashboard();
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

    public function setSession(Request $request) {
        if($request->name == 'btn-mini') {
            if(session()->has('btn-mini')) {
                $btn_mini = session()->get('btn-mini');
                if($btn_mini == false) {
                    $request->session()->put('btn-mini', true);      
                } else {
                    $request->session()->put('btn-mini', false);
                }
            } else {
                $request->session()->put('btn-mini', true);
            }
            return response()->json([
                'success' => true,
                'message' => 'Session btn-mini berhasil diubah'
            ]);
        } else if($request->name == 'btn-dark-mode') {
            if(session()->has('btn-dark-mode')) {
                $btn_dark_mode = session()->get('btn-dark-mode');
                if($btn_dark_mode == false) {
                    $request->session()->put('btn-dark-mode', true);      
                } else {
                    $request->session()->put('btn-dark-mode', false);
                }
            } else {
                $request->session()->put('btn-dark-mode', true);
            }
            return response()->json([
                'success' => true,
                'message' => 'Session btn-dark-mode berhasil diubah'
            ]);
        }
    }

}