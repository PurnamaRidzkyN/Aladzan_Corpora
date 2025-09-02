<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Discount;
use App\Models\Reseller;
use Illuminate\Http\Request;
use App\Models\OrderSubscription;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\URL;
use App\Helpers\AdminActivityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DiscountController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ResellerController extends Controller
{
    public function updateProfile(Request $request)
    {
        $resellerId = auth()->id();
        $validated = $request->validate(
            [
                'pfp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:resellers,email,' . ($resellerId ?? ''),
            ],
            [
                'pfp.image' => 'File profil harus berupa gambar.',
                'pfp.mimes' => 'File profil harus berformat JPG, JPEG, atau PNG.',
                'pfp.max' => 'Ukuran file profil maksimal 2MB.',
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon harus berupa teks/angka.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email tidak valid.',
                'email.max' => 'Email maksimal 255 karakter.',
                'email.unique' => 'Email sudah digunakan.',
            ],
        );

        $user = Reseller::findOrFail(auth()->id());
        if ($request->has('pfp')) {
            $pfp = $request->pfp;

            $uploadResult = Cloudinary::uploadApi()->upload($request->file('pfp')->getRealPath(), [
                'public_id' => 'Profile/R-' . auth()->id(),
                'folder' => 'Profile',
                'overwrite' => true,
                'resource_type' => 'image',
            ]);
            $pfp = $uploadResult['public_id'];
            $user->pfp_path = $pfp;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        if ($request->email != $user->email) {
            $temp = [
                'name' => $request->name,
                'new_email' => $request->email,
                'old_email' => $user->email,
            ];
            $url = URL::temporarySignedRoute('change.email.reseller', now()->addMinutes(5), $temp);

            Mail::send(
                'email.change_email',
                [
                    'url' => $url,
                    'name' => $temp['name'],
                    'old_email' => $temp['old_email'],
                    'new_email' => $temp['new_email'],
                ],
                function ($message) use ($user) {
                    $message->to($user->email)->subject('Konfirmasi Perubahan Email Anda');
                },
            );
            return back()->with('verification', 'Untuk mengkonfirmasi perubahan email, silakan klik link yang sudah dikirim ke email Anda.');
        }
        return back()->with('success', 'Informasi berhasil diubah.');
    }

    public function changeEmailReseller(Request $request)
    {
        $user = Reseller::findOrFail(auth()->id());
        $user->email = $request->new_email;
        $user->save();
        return redirect()->route('profile')->with('success', 'Email berhasil diubah.');
    }

    public function showUpgradeAccount()
    {
        $order = OrderSubscription::where('reseller_id', auth()->id())
            ->where('status', '!=', 3) // ambil semua status selain 3
            ->first();

        if ($order) {
            return view('store.profile.detail_order_upgrade_account', compact('order'));
        }
        $user = auth()->user()->load('plan');
        $plans = Plan::all();
        return view('store.profile.upgrade_account', compact('user', 'plans'));
    }
    public function showUpgradePayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'discount_code' => 'nullable|string|max:100',
            'final_price' => 'required|integer|min:0',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $finalPrice = $plan->price;

        $discount = null;

        $discountAmount = 0; // default

        if ($request->filled('discount_code')) {
            $discount = Discount::where('code', $request->discount_code)
                ->where(function ($q) {
                    $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                })
                ->first();

            if ($discount) {
                // Hitung potongan
                if ($discount->is_percent) {
                    $discountAmount = ($plan->price * $discount->amount) / 100;
                } else {
                    $discountAmount = $discount->amount;
                }

                $finalPrice = $plan->price - $discountAmount;

                if ($finalPrice < 0) {
                    $finalPrice = 0;
                }
            } else {
                return back()->withErrors(['discount_code' => 'Kode diskon tidak valid.']);
            }
        } else {
            $finalPrice = $plan->price;
        }

        // Ambil metode pembayaran (mirip fungsi payment())
        $bankAccounts = json_decode(Setting::where('key', 'bank_accounts')->first()->value ?? '[]', true);
        $ewallets = json_decode(Setting::where('key', 'ewallets')->first()->value ?? '[]', true);
        $methods = [
            'Bank' => array_map(function ($bank) {
                return [
                    'id' => strtolower($bank['name']),
                    'name' => $bank['name'],
                    'type' => 'va',
                    'description' => 'Transfer ke rekening virtual ' . $bank['name'],
                    'va_number' => $bank['number'],
                    'steps' => ['Buka mobile banking / ATM', 'Masukkan nomor virtual account', 'Masukkan nominal pembayaran', 'Konfirmasi pembayaran'],
                ];
            }, $bankAccounts),

            'E-Wallet' => array_map(function ($wallet) {
                return [
                    'id' => strtolower($wallet['provider']),
                    'name' => $wallet['provider'],
                    'type' => 'ewallet',
                    'description' => 'Bayar pakai ' . $wallet['provider'],
                    'phone_number' => $wallet['number'],
                    'steps' => ['Buka aplikasi ' . $wallet['provider'], 'Pilih menu transfer / top-up', 'Masukkan nomor tujuan / scan QR', 'Masukkan nominal pembayaran', 'Konfirmasi pembayaran'],
                ];
            }, $ewallets),
        ];

        return view('store.profile.payment_upgrade_account', compact('plan', 'discount', 'finalPrice', 'methods', 'discountAmount'));
    }

    public function storeUpgradePaymentProof(Request $request)
    {
        if (!in_array($request->selected_method, ['va', 'ewallet', 'qris'])) {
            return redirect()
                ->route('profile')
                ->withErrors(['selected_method' => 'Pilih metode pembayaran yang valid'])
                ->withInput();
        }
        $validated = $request->validate(
            [
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'plan_id' => 'required|integer',
                'final_price' => 'required|numeric',
                'selected_method' => 'required|string',
                'discount_code' => 'nullable|string',
                'discount_amount' => 'nullable|numeric',
            ],
            [
                'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
                'bukti_pembayaran.image' => 'File bukti pembayaran harus berupa gambar.',
                'bukti_pembayaran.mimes' => 'Format bukti pembayaran harus jpeg, png, atau jpg.',
                'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',

                'selected_method.required' => 'Silakan pilih metode pembayaran.',
                'selected_method.string' => 'Metode pembayaran tidak valid.',
            ],
        );

        $publicId = 'OS/O-' . auth()->id() . '/' . time();

        $payment = Cloudinary::uploadApi()->upload($request->file('bukti_pembayaran')->getRealPath(), [
            'public_id' => $publicId,
            'overwrite' => true,
            'resource_type' => 'image',
        ]);

        OrderSubscription::create([
            'reseller_id' => auth()->id(),
            'plan_id' => $validated['plan_id'],
            'price' => $validated['final_price'],
            'discount_code' => $validated['discount_code'] ?? null,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'payment_method' => $request['selected_method'],
            'payment_proof' => $payment['public_id'],
            'paid_at' => now(),
        ]);
        $plan = Plan::findOrFail($validated['plan_id']);
        $reseller = auth()->user();
        NotificationHelper::notifyAdmins('Pesanan Baru', "Reseller {{ $reseller->name }} telah mengajukan upgrade plan ke {{ $plan->name }}.", route('orders.current'));
        return redirect()->route('profile')->with('success', 'Bukti pembayaran berhasil dikirim!');
    }

    public function resellerAccount(Request $request)
    {
        $query = Reseller::with('plan'); // eager load plan
        if (Auth::guard('admin')->user()->is_super_admin) {
            $query = $query->withTrashed();
        }
        // Filter pencarian nama/email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter membership berdasarkan plan
        if ($planId = $request->input('plan_id')) {
            $query->where('plan_id', $planId);
        }

        // Filter tanggal bergabung
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }
        $plans = Plan::all();
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reseller.index', compact('users', 'plans'));
    }
    public function resellerDestroy(string $id)
    {
        $reseller = Reseller::findOrFail($id);
        $reseller->delete();
        // Log aktivitas admin
        AdminActivityHelper::log('DELETE', 'resellers', $reseller->id, 'Menghapus akun reseller : ' . $reseller->name);
        return redirect()->route('reseller.index')->with('success', 'Toko berhasil dihapus.');
    }
    public function resellerRestore($id)
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            $reseller = Reseller::onlyTrashed()->findOrFail($id);
            $reseller->restore();
        } else {
            return redirect()->route('reseller.index')->with('error', 'Maaf, Anda tidak memiliki izin. Hanya Super Admin yang bisa memulihakan reseller.');
        }

        return redirect()->route('reseller.index')->with('success', 'Shop berhasil direstore.');
    }

    public function resellerForceDelete($id)
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            $reseller = Reseller::onlyTrashed()->findOrFail($id);

            $reseller->forceDelete();
        } else {
            return redirect()->route('reseller.index')->with('error', 'Maaf, Anda tidak memiliki izin. Hanya Super Admin yang bisa menghapus permanent reseller.');
        }

        return redirect()->route('reseller.index')->with('success', 'Shop berhasil dihapus permanen.');
    }

    public function pending()
    {
        $orders = OrderSubscription::with(['reseller', 'plan'])
            ->where('status', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.reseller.order_upgrade_account', compact('orders'));
    }

    // Terima pembelian
    public function approve(OrderSubscription $order)
    {
        $order->update([
            'status' => 1,
            'paid_at' => now(),
        ]);

        // Opsional: upgrade reseller plan
        if ($order->reseller) {
            $order->reseller->update([
                'plan_id' => $order->plan_id,
            ]);
        }
        NotificationHelper::notifyReseller($order->reseller->id, 'Upgrade Disetujui', 'Upgrade akunmu telah disetujui oleh admin. Sekarang akunmu berada di plan ' . $order->plan->name, route('upgrade.account'));
        AdminActivityHelper::log('APPROVE', 'order_subscriptions', $order->id, 'Menyetujui upgrade akun: ' . $order->order_code);

        return redirect()->route('admin.orders.pending')->with('success', 'Pembelian disetujui.');
    }

    // Tolak pembelian
    public function reject(OrderSubscription $order)
    {
        $order->update([
            'status' => 3,
        ]);
        AdminActivityHelper::log('REJECT', 'order_subscriptions', $order->id, 'Menolak upgrade akun: ' . $order->order_code);
        NotificationHelper::notifyReseller($order->reseller->id, 'Upgrade Ditolak', 'Upgrade akunmu telah ditolak oleh admin.');
        return redirect()->route('admin.orders.pending')->with('error', 'Pembelian ditolak.');
    }
}
