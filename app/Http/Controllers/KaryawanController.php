<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawanRole = Role::where('name', 'karyawan')->first();
        $karyawan = User::role('karyawan')
            ->get()
            ->map(function ($user) {
                // Calculate age from tanggal_lahir
                $age = isset($user->custom_fields['tanggal_lahir'])
                    ? Carbon::parse($user->custom_fields['tanggal_lahir'])->age
                    : null;
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->avatar_url,
                    'age' => $age
                ];
            });

        return view('pages.profile', compact('karyawan'));
    }
}
