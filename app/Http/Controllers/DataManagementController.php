<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;


class DataManagementController extends Controller
{
    public function index()
    {
        return view('user.data-management');
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