<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pergantian Email</title>
</head>
<body style="margin: 0; padding: 40px 0; background-color: #f9fafb; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
        
        <h2 style="font-size: 22px; color: #1d4ed8; font-weight: bold; margin-bottom: 20px;">
            Konfirmasi Pergantian Email
        </h2>

        <p style="font-size: 16px; color: #374151; margin-bottom: 16px;">
            Halo <strong>{{ $name }}</strong>, kami menerima permintaan untuk mengganti alamat email akun <strong>Yaladzanhub</strong> Dengan nama <strong>{{ $name }}</strong> ke alamat email baru ini.
        </p>

        <p style="font-size: 15px; color: #4b5563; margin-bottom: 16px;">
            Berikut detail pergantian:
        </p>

        <ul style="font-size: 15px; color: #374151; margin-bottom: 24px; list-style: none; padding: 0;">
            <li><strong>Email Lama:</strong> {{ $old_email }}</li>
            <li><strong>Email Baru:</strong> {{ $new_email }}</li>
        </ul>

        <p style="font-size: 15px; color: #4b5563; margin-bottom: 24px;">
            Jika Anda benar-benar ingin mengganti email ke alamat baru di atas, silakan klik tombol berikut:
        </p>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $url }}" style="background-color: #1d4ed8; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Konfirmasi Perubahan Email
            </a>
        </div>

        <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
            Link ini hanya berlaku selama <strong>30 menit</strong>. Jika Anda tidak merasa meminta perubahan ini, abaikan email ini dan akun Anda tidak akan berubah.
        </p>

        <p style="font-size: 14px; color: #9ca3af;">
            Salam hangat,<br>
            Tim Yaladzanhub
        </p>
    </div>
</body>
</html>
