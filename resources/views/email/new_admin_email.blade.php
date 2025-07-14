<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun Admin Anda di Yaladzanhub</title>
</head>
<body style="margin: 0; padding: 40px 0; background-color: #f7fafc; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <h2 style="font-size: 24px; color: #2b6cb0; font-weight: bold; margin-bottom: 20px;">
            Akun Admin Telah Dibuat
        </h2>

        <p style="color: #4a5568; font-size: 16px; margin-bottom: 20px;">
            Halo! Akun Anda telah <strong>dibuat sebagai admin</strong> di platform <strong>Yaladzanhub</strong> oleh tim kami.
        </p>

        <div style="background-color: #f1f5f9; padding: 15px; border-radius: 8px; border: 1px solid #cbd5e0; margin-bottom: 20px;">
            <p style="margin: 0 0 10px;"><strong>Email:</strong> <span style="color: #2b6cb0;">{{ $email }}</span></p>
            <p style="margin: 0;"><strong>Password Sementara:</strong> <span style="color: #2b6cb0;">{{ $password }}</span></p>
        </div>

        <p style="color: #4a5568; font-size: 16px; margin-bottom: 30px;">
            Silakan gunakan informasi di atas untuk <strong>login ke sistem</strong>. Demi keamanan, kami menyarankan Anda segera mengganti password setelah berhasil masuk.
        </p>

        <div style="text-align: center; margin-bottom: 30px;">
            <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #3182ce; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Masuk ke Yaladzanhub
            </a>
        </div>

        <p style="text-align: center; font-size: 14px; color: #a0aec0;">
            Jika Anda merasa tidak seharusnya menerima email ini, silakan hubungi administrator sistem kami.
        </p>
    </div>
</body>
</html>
