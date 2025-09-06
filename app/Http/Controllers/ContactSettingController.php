<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ResiSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactSettingController extends Controller
{
    public function index()
    {
        // Ambil data dari table settings
        $whatsapp = Setting::where('key', 'whatsapp')->first()->value ?? '';

        $bankAccounts = json_decode(Setting::where('key', 'bank_accounts')->first()->value ?? '[]', true);

        $ewallets = json_decode(Setting::where('key', 'ewallets')->first()->value ?? '[]', true);
        $resiSources = ResiSource::all()->toArray();

        return view('admin.settings.index', compact('whatsapp', 'bankAccounts', 'ewallets', 'resiSources'));
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
        if ($request->has('resi_sources')) {
            $ids = []; // untuk nyimpan id yang masih dipakai

            foreach ($request->resi_sources as $resi) {
                if (!empty($resi['name'])) {
                    if (!empty($resi['id'])) {
                        // Update
                        ResiSource::where('id', $resi['id'])->update([
                            'name' => $resi['name'],
                        ]);
                        $ids[] = $resi['id'];
                    } else {
                        // Create
                        $new = ResiSource::create([
                            'name' => $resi['name'],
                        ]);
                        $ids[] = $new->id;
                    }
                }
            }

            // Hapus semua resi_sources yang tidak ada di request
            ResiSource::whereNotIn('id', $ids)->delete();
        }

        // Simpan ke table settings, kita pakai key unik
        Setting::updateOrCreate(['key' => 'whatsapp'], ['value' => $data['whatsapp']]);

        Setting::updateOrCreate(['key' => 'bank_accounts'], ['value' => json_encode($data['bank_accounts'] ?? [])]);

        Setting::updateOrCreate(['key' => 'ewallets'], ['value' => json_encode($data['ewallets'] ?? [])]);

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
