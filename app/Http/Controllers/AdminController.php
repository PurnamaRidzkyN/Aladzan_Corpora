<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to retrieve and display a list of admins
        $admins = Admin::all(); // Assuming you have an Admin model
        return view('admin.management_admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email',
    ]);

    $plainPassword = Str::random(8);
    $data['password'] = bcrypt($plainPassword);

    Admin::create($data);

    Mail::send('email.new_admin_email', [
        'email' => $data['email'],
        'password' => $plainPassword, 
        'loginUrl' => route('login.admin'),
    ], function ($message) use ($data) {
        $message->to($data['email'])
                ->subject('Akun Admin Anda di Yaladzanhub');
    });

    return redirect()->route('admins.index')->with('success', 'Admin berhasil dibuat dan email sudah dikirim.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
