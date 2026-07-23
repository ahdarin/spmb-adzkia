<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */
    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */
    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | ✅ FIX: Sebelumnya hardcoded 'UTC' — menyebabkan semua timestamp
    | (now(), Carbon::now(), created_at, log aktivitas, tanggal pendaftaran)
    | terlambat 7 jam dari waktu nyata Indonesia.
    |
    | Sekarang membaca dari .env dengan fallback ke 'Asia/Jakarta' (WIB, UTC+7).
    | Tambahkan baris berikut ke file .env:
    |
    |   APP_TIMEZONE=Asia/Jakarta
    |
    | Referensi timezone Indonesia:
    |   WIB  → Asia/Jakarta   (Sumatera, Jawa, Kalimantan Barat & Tengah)
    |   WITA → Asia/Makassar  (Bali, Sulawesi, Kalimantan Timur)
    |   WIT  → Asia/Jayapura  (Papua, Maluku)
    |
    */
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */
    'locale'          => env('APP_LOCALE', 'id'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'id'),
    'faker_locale'    => env('APP_FAKER_LOCALE', 'id_ID'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */
    'cipher' => 'AES-256-CBC',
    'key'    => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];