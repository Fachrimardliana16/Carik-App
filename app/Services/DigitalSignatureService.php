<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class DigitalSignatureService
{
    /**
     * Generate RSA Key Pair for a user
     */
    public static function generateKeyPair(User $user): void
    {
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Create the keypair
        $res = openssl_pkey_new($config);

        // Get private key
        openssl_pkey_export($res, $privKey);

        // Get public key
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];

        // Save to user (encrypt private key)
        $user->signature_private_key = Crypt::encryptString($privKey);
        $user->signature_public_key = $pubKey;
        $user->save();
    }

    /**
     * Sign data with user's private key
     */
    public static function sign(User $user, string $data): ?string
    {
        if (!$user->signature_private_key) {
            return null;
        }

        try {
            $privateKey = Crypt::decryptString($user->signature_private_key);
            
            openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

            return base64_encode($signature);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Verify signature
     */
    public static function verify(string $data, string $signature, string $publicKey): bool
    {
        $binarySignature = base64_decode($signature);
        return openssl_verify($data, $binarySignature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }
}
