<?php

namespace App\Services;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;

class CredencialesEncryptionService
{
    private $encrypter;

    public function __construct()
    {
        $key = env('CREDENTIALS_ENCRYPTION_KEY', env('APP_KEY'));
        $this->encrypter = new Encrypter(base64_decode(substr($key, 7)), Config::get('app.cipher'));
    }

    /**
     * Cifra un valor
     */
    public function encrypt(string $value): string
    {
        return $this->encrypter->encryptString($value);
    }

    /**
     * Descifra un valor
     */
    public function decrypt(string $encryptedValue): string
    {
        return $this->encrypter->decryptString($encryptedValue);
    }
}