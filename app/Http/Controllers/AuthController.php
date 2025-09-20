<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Reseller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AuthController extends Controller
{
    public function showLoginAdminForm()
    {
        return view('auth.login_admin');
    }
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin, $remember);
            return redirect()->intended('/staff-only/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    public function showLoginResellerForm()
    {
        return view('auth.login_reseller');
    }
    public function loginReseller(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        $reseller = Reseller::where('email', $request->email)->first();

        if ($reseller && Hash::check($request->password, $reseller->password)) {
            Auth::guard('reseller')->login($reseller, $remember);
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    public function showForgotForm($ir)
    {
        return view('auth.forgot_password', compact('ir'));
    }
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'ir' => 'required|boolean',
        ]);

        $email = $request->email;
        $isReseller = $request->boolean('ir');

        if ($isReseller) {
            $exists = Reseller::where('email', $email)->exists();
        } else {
            $exists = Admin::where('email', $email)->exists();
        }

        if (!$exists) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam data ' . ($isReseller ? 'reseller' : 'admin') . '.',
            ]);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
                'is_reseller' => $request->ir ? 1 : 0,
            ],
        );

        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $request->email, 'ir' => $request->ir]);

        Mail::send(
            'email.reset_password',
            [
                'resetUrl' => $resetUrl,
            ],
            function ($message) use ($request) {
                $message->to($request->email)->subject('Reset Password ALADZAN CORPORA');
            },
        );

        return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan klik link tersebut kedaluarsa 5 menit.');
    }
    public function showResetForm(Request $request)
    {
        return view('auth.reset_password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
    public function resetPassword(Request $request)
    {
        DB::table('password_reset_tokens')
            ->where('created_at', '<', now()->subMinutes(5))
            ->delete();
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
                'token' => 'required',
                'g-recaptcha-response' => 'required|captcha',
            ],
            [
                'g-recaptcha-response.required' => 'Harap centang reCAPTCHA terlebih dahulu.',
                'g-recaptcha-response.captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ],
        );

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token) || now()->diffInMinutes($record->created_at) > 5) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }
        if ($record->is_reseller) {
            $reseller = Reseller::where('email', $request->email)->update([
                'password' => bcrypt($request->password),
            ]);
            if (!$reseller) {
                return back()->withErrors(['email' => 'Email tidak ditemukan.']);
            }
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login.reseller')->with('success', 'Password berhasil diubah.');
        } else {
            $admin = Admin::where('email', $request->email)->update([
                'password' => bcrypt($request->password),
            ]);
            if (!$admin) {
                return back()->withErrors(['email' => 'Email tidak ditemukan.']);
            }
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login.admin')->with('success', 'Password berhasil diubah.');
        }
    }
    public function redirectToGoogle(Request $request)
    {
        if ($request->has('redirect')) {
            session(['reseller_redirect_back' => $request->query('redirect')]);
        }
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google.']);
        }
        $user = Reseller::where('email', $googleUser->getEmail())->first();

        if ($user) {
            Auth::login($user);
            $redirectTo = session()->pull('reseller_redirect_back', '/home');
            if ($redirectTo == url('/')) {
                return redirect("/home");
            }
            return redirect($redirectTo);
        } else {
            $user = [
                'name' => $googleUser->getName(),
                'username' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'pfp_path' => $googleUser->getAvatar(),
                'google_id' => $googleUser->getId(),
            ];
            session(['google_user_data' => $user]);

            return redirect()->route('register')->with('success', 'Pendaftaran berhasil. Selamat datang!');
        }
    }
    public function showRegisterForm()
    {
        if (session()->has('google_user_data')) {
            $user = (object) session('google_user_data');
        } else {
            $user = null;
        }
        return view('auth.register', compact('user'));
    }
    public function register(Request $request)
    {
        if ($request->has('google_id')) {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:resellers,email',
                'phone' => 'required|string|max:15|unique:resellers,phone',
            ]);

            $googleUser = session('google_user_data');

            $pfpUrl = $googleUser['pfp_path'] ?? null;
            $googleId = $googleUser['google_id'] ?? null;

            if ($pfpUrl) {
                $tmpFile = tempnam(sys_get_temp_dir(), 'avatar_');
                file_put_contents($tmpFile, file_get_contents($pfpUrl));

                $uploadResult = Cloudinary::uploadApi()->upload($tmpFile, [
                    'public_id' => 'Profile/R-' . auth()->id(),
                    'folder' => 'Profile',
                    'overwrite' => true,
                ]);
                $pfp = $uploadResult['public_id'];
            }

            // Validasi form dari request (misal)
            $validated = $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:resellers,email',
                    'phone' => 'required|string|max:20',
                ],
                [
                    'name.required' => 'Nama wajib diisi.',
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'email.unique' => 'Email sudah terdaftar.',
                    'phone.required' => 'Nomor telepon wajib diisi.',
                ],
            );

            // Simpan data reseller ke database
            $reseller = Reseller::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'google_id' => $googleId,
                'pfp_path' => $pfp,
                'password' => bcrypt($googleId),
                'plan_id' => null,
            ]);

            Auth::login($reseller);
            session()->forget('google_user_data');

            return redirect()->route('upgrade.account')->with('success', 'Pendaftaran berhasil. Selamat datang!');
        } else {
            $validated = $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:resellers,email',
                    'password' => 'required|string|min:8|confirmed',
                    'phone' => 'required|string|max:15|unique:resellers,phone',
                ],
                [
                    'name.required' => 'Nama wajib diisi.',
                    'name.string' => 'Nama harus berupa teks.',
                    'name.max' => 'Nama maksimal 100 karakter.',

                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'email.unique' => 'Email sudah terdaftar.',

                    'password.required' => 'Kata sandi wajib diisi.',
                    'password.string' => 'Kata sandi harus berupa teks.',
                    'password.min' => 'Kata sandi minimal 8 karakter.',
                    'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',

                    'phone.required' => 'Nomor telepon wajib diisi.',
                    'phone.string' => 'Nomor telepon harus berupa teks.',
                    'phone.max' => 'Nomor telepon maksimal 15 karakter.',
                    'phone.unique' => 'Nomor telepon sudah terdaftar.',
                ],
            );

            $temp = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'plan_id' => null,
            ];

            Cache::put('register_' . $validated['email'], $temp, now()->addMinutes(5));

            $url = URL::temporarySignedRoute('register.verify', now()->addMinutes(5), ['email' => $validated['email']]);
            Mail::send(
                'email.verify_email',
                [
                    'url' => $url,
                    'name' => $temp['name'],
                ],
                function ($message) use ($validated) {
                    $message->to($validated['email'])->subject('Verifikasi Pendaftaran');
                },
            );

            return redirect('/home')->with('success', 'Link verifikasi telah dikirim ke email Anda. Silakan cek email Anda dan konfirmasi melalui tautan yang telah dikirim.');
        }
    }

    public function verifyLink(Request $request, $email)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
        }

        if (Reseller::where('email', $email)->exists()) {
            return redirect()->route('register.form')->withErrors('Email sudah terdaftar.');
        }

        // Ambil data dari cache
        $data = Cache::get('register_' . $email);

        if (!$data) {
            return redirect()->route('register.form')->withErrors('Data pendaftaran tidak ditemukan atau link sudah kedaluwarsa.');
        }

        // Simpan user baru
        $user = Reseller::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'pfp_path' => 'default_wxli5k.jpg',
        ]);

        Auth::login($user);

        return redirect()->route('upgrade.account')->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }

    public function showChangePassword()
    {
        $layout = Auth::guard('reseller')->check() ? 'layouts.app' : 'layouts.dashboard';

        return view('auth.change_password', compact('layout'));
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
        } elseif (Auth::guard('reseller')->check()) {
            $user = Auth::guard('reseller')->user();
        } else {
            return redirect()
                ->back()
                ->withErrors(['auth' => 'Tidak ditemukan pengguna aktif.']);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('reseller')->check()) {
            Auth::guard('reseller')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
