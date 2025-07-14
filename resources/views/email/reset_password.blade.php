<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Yaladzanhub</title>
</head>
<body style="margin: 0; padding: 40px 0; background-color: #f7fafc; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <h2 style="font-size: 24px; color: #2b6cb0; font-weight: bold; margin-bottom: 20px;">
            Reset Password Anda
        </h2>

        <p style="color: #4a5568; font-size: 16px; margin-bottom: 20px;">
            Kami menerima permintaan untuk mengatur ulang password akun Anda. Silakan klik tombol di bawah ini untuk melanjutkan proses reset password. Tautan ini hanya berlaku selama <strong>5 menit</strong>.
        </p>

        <div style="text-align: center; margin-bottom: 30px;">
            <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #3182ce; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Reset Password Sekarang
            </a>
        </div>

        <p style="color: #718096; font-size: 14px;">
            Jika Anda tidak meminta reset password, abaikan email ini. Tidak ada tindakan yang akan dilakukan tanpa interaksi dari Anda.
        </p>

        <p style="text-align: center; font-size: 14px; color: #a0aec0; margin-top: 40px;">
            &copy; {{ date('Y') }} Yaladzanhub. Semua hak dilindungi.
        </p>
    </div>
</body>
</html>
