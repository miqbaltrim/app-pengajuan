<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Lakukan proses autentikasi pengguna
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Jika autentikasi berhasil, arahkan ke dashboard yang sesuai dengan peran pengguna
            return $this->redirectToDashboard(Auth::user()->role);
        } else {
            // Jika autentikasi gagal, arahkan kembali ke halaman login dengan pesan error
            return redirect()->route('login')->with('error', 'Email atau password salah.');
        }
    }

    /**
     * Redirect user to the appropriate dashboard based on their role.
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
                break;

            case 'area-manager':
                return redirect()->route('area-manager.dashboard');
                break;
            case 'staff-office':
                return redirect()->route('staff-office.dashboard');
                break;
            // Tambahkan case untuk setiap peran dengan rute dashboard yang sesuai
            default:
                // Jika tidak ada peran yang cocok, arahkan ke rute dashboard default
                return redirect()->route('dashboard');
        }
    }

    /**
     * Menampilkan form registrasi.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses registrasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validasi input form
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Konfirmasi password
        ]);

        // Simpan data pengguna ke database
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Jika data berhasil disimpan, tampilkan pesan sukses dan arahkan ke halaman login
        if ($user) {
            return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login untuk melanjutkan.');
        } else {
            // Jika terjadi kesalahan, tampilkan pesan gagal
            return redirect()->back()->with('error', 'Registrasi gagal. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        // Contoh pengambilan data role dari user yang sedang login
        $role = Auth::user()->role;

        // Kemudian Anda lewatkan nilai $role ke view
        return view('dashboard', ['role' => $role]);
    }

    public function logout()
    {
        Auth::logout(); // Melakukan logout pengguna
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
