<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ResellerController extends Controller
{
    public function orderHistory()
    {
        $orders = Order::where('reseller_id', auth()->id())
            ->with('orderItems.variant.product', 'rating')
            ->get();
        return view('store.profile.order_history', compact('orders'));
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'pfp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

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
        return view('store.profile.upgrade_account');
    }

    public function upgradeAccount(Request $request)
    {
        $user = Reseller::findOrFail(auth()->id());
        $user->upgrade_account = $request->upgrade_account;
        $user->save();
        return redirect()->route('upgrade.account')->with('success', 'Upgrade Account berhasil diubah.');
    }

    public function resellerAccount(Request $request)
    {
        $query = Reseller::query();

        // Filter pencarian nama/email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter member (plan_type)
        if ($membership = $request->input('membership')) {
            $query->where('plan_type', $membership === 'pro' ? Reseller::PLAN_PRO : Reseller::PLAN_STANDARD);
        }

        // Filter tanggal bergabung
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reseller.index', compact('users'));
    }
}
