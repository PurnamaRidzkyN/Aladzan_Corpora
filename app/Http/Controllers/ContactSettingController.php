<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactSettingController extends Controller
{
    public function index()
    {
        // Ambil data dari table settings
        $whatsapp = Setting::where('key', 'whatsapp')->first()->value ?? '';
        
        $bankAccounts = json_decode(
            Setting::where('key', 'bank_accounts')->first()->value ?? '[]',
            true
        );

        $ewallets = json_decode(
            Setting::where('key', 'ewallets')->first()->value ?? '[]',
            true
        );

        return view('admin.settings.index', compact('whatsapp', 'bankAccounts', 'ewallets'));
    }

    public function store(Request $request)
    {

        // Validasi sederhana
        $data = $request->validate([
            'whatsapp' => 'nullable|string',
            'bank_accounts' => 'nullable|array',
            'bank_accounts.*.name' => 'nullable|string',
            'bank_accounts.*.number' => 'nullable|string',
            'ewallets' => 'nullable|array',
            'ewallets.*.provider' => 'nullable|string',
            'ewallets.*.number' => 'nullable|string',
        ]);

        // Simpan ke table settings, kita pakai key unik
        Setting::updateOrCreate(['key' => 'whatsapp'], ['value' => $data['whatsapp']]);

        Setting::updateOrCreate(['key' => 'bank_accounts'], ['value' => json_encode($data['bank_accounts'] ?? [])]);

        Setting::updateOrCreate(['key' => 'ewallets'], ['value' => json_encode($data['ewallets'] ?? [])]);

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
