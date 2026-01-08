<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override username agar Laravel tidak mencari kolom email di users
     */
    public function username()
    {
        // ini penting, HARUS employee_id
        return 'email';
    }

    /**
     * Override credentials untuk login via employee.email
     */
    protected function credentials(Request $request)
    {
        $employee = Employee::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        // dd($employee);


        if (!$employee || !$employee->user) {
            // paksa gagal login
            return [
                'employee_id' => -1,
                'password'    => 'invalid',
            ];
        }

        return [
            'employee_id' => $employee->id,
            'password'    => $request->password,
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        $employee = $user->employee;
        $googleAccount = $employee?->googleAccount;

        // Jika BELUM pernah connect Google
        if (!$googleAccount || !$googleAccount->refresh_token) {
            return redirect()->route('google.connect', $employee->id);
        }

        // 🔐 SUPERADMIN
        if ($user->role === 'superadmin') {
            return redirect()->route('home');
        }

        // 🔐 PPNPN
        if ($user->role === 'ppnpn') {
            return redirect()->route('attendance.index');
        }

        // 👤 USER BIASA
        return redirect()->route('redirect.tamu');
    }
}
