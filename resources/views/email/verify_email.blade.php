<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pendaftaran</title>
</head>
<body style="margin: 0; padding: 40px 0; background-color: #f9fafb; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
        
        <h2 style="font-size: 22px; color: #1d4ed8; font-weight: bold; margin-bottom: 20px;">
            Verifikasi Pendaftaran Anda
        </h2>

        <p style="font-size: 16px; color: #374151; margin-bottom: 16px;">
            Halo <strong>{{ $name }}</strong>, terima kasih telah mendaftar di <strong>Yaladzanhub</strong>.
        </p>

        <p style="font-size: 15px; color: #4b5563; margin-bottom: 24px;">
            Untuk menyelesaikan proses pendaftaran Anda, silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:
        </p>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $url }}" style="background-color: #1d4ed8; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Verifikasi Sekarang
            </a>
        </div>

        <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
            Link ini hanya berlaku selama <strong>30 menit</strong>. Jika Anda tidak merasa mendaftar di platform kami, abaikan email ini.
        </p>

        <p style="font-size: 14px; color: #9ca3af;">
            Salam hangat,<br>
            Tim Yaladzanhub
        </p>
    </div>
</body>
</html>
