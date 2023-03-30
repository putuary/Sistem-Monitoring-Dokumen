<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if($role == 'superAdmin') {
            if (in_array($request->user()->role, ['kaprodi', 'gkmp'])) {
                if($request->user()->aktif_role->is_dosen == 0) {
                    return $next($request);
                }
                abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
            }
            abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
        } else if($role=='admin') {
            if (in_array($request->user()->role, ['kaprodi', 'gkmp','admin'])) {
                if($request->user()->role == 'admin') {
                    return $next($request);
                } else {
                    if($request->user()->aktif_role->is_dosen == 0) {
                        return $next($request);
                    }
                    abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
                }
            }
            abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');  
        } else if($role == 'dosen') {
            if (in_array($request->user()->role, ['kaprodi', 'gkmp','dosen'])) {
                if($request->user()->role == 'dosen') {
                    return $next($request);
                } else {
                    if($request->user()->aktif_role->is_dosen == 1) {
                        return $next($request);
                    }
                    abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
                }
            }
            abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
        }
        abort(403, 'Anda tidak memiliki hak mengakses laman tersebut!');
    }
}