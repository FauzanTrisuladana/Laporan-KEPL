<?php
// 1. Alur Penyimpanan Data Entitas (PenanggungJawabController, AkunController, TransaksiController)
Log::info('Berhasil menyimpan data penanggung jawab', [
    'who' => [
        'user_id' => $request->user()?->id,
        'role' => $request->user()?->role ?? 'user',
    ],
    'action' => $request->method() . ' ' . $request->getRequestUri(),
    'reason' => 'Penambahan entitas penanggung jawab baru (ID: ' . $pj->id . ')',
]);

// 2. Alur Kegagalan Autentikasi Login (AuthController) - Tanpa mencatat password/token
if (! $user || ! Hash::check($credentials['password'], $user->password)) {
    Log::warning('Percobaan login gagal: kredensial tidak valid', [
        'who' => [
            'type' => 'guest',
            'client_ip' => $request->ip(),
        ],
        'action' => $request->method() . ' ' . $request->getRequestUri(),
        'reason' => 'Email tidak ditemukan atau password tidak cocok',
    ]);
}

// 3. Alur Kegagalan Validasi Input (bootstrap/exceptions.php) - Hanya mencatat nama field
Log::warning('Validasi data gagal', [
    'who' => $who,
    'action' => $request->method() . ' ' . $request->getRequestUri(),
    'reason' => 'Field input tidak memenuhi aturan: ' . implode(', ', array_keys($errors)),
    'invalid_fields' => array_keys($errors),
]);

// 4. Alur Kegagalan Koneksi / Query Database (bootstrap/exceptions.php)
Log::error('Gagal menghubungi atau mengeksekusi kueri basis data', [
    'who' => $who,
    'action' => $request->method() . ' ' . $request->getRequestUri(),
    'reason' => $e->getMessage(),
    'exception' => class_basename($e),
]);
