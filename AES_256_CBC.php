<?php

define('CIPHER_METHOD', 'AES-256-CBC');

function getDerivedKey() {
    $secret = getenv('ENCRYPTION_SECRET'); 
    return hash_pbkdf2('sha256', $secret, 'some_salt_value', 100000, 32, true); 
}

function encrypt($plaintext) {
    $encryptionKey = getDerivedKey();
    $authKey = hash_hmac('sha256', 'auth', $encryptionKey, true); 
    $iv = random_bytes(openssl_cipher_iv_length(CIPHER_METHOD));
    $ciphertext = openssl_encrypt($plaintext, CIPHER_METHOD, $encryptionKey, 0, $iv);
    $hmac = hash_hmac('sha256', $ciphertext, $authKey, true); 
    return base64_encode($iv . $hmac . $ciphertext);
}

function decrypt($ciphertext) {
    $encryptionKey = getDerivedKey();
    $authKey = hash_hmac('sha256', 'auth', $encryptionKey, true);
    $data = base64_decode($ciphertext);
    $ivLength = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = substr($data, 0, $ivLength);
    $hmac = substr($data, $ivLength, 32);
    $ciphertext = substr($data, $ivLength + 32);

    if (!hash_equals($hmac, hash_hmac('sha256', $ciphertext, $authKey, true))) {
        return "Invalid authentication!";
    }
    return openssl_decrypt($ciphertext, CIPHER_METHOD, $encryptionKey, 0, $iv);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'];
    if (isset($_POST['encrypt'])) {
        echo "<p class='output'><strong>Encrypted:</strong> " . encrypt($text) . "</p>";
    } elseif (isset($_POST['decrypt'])) {
        echo "<p class='output'><strong>Decrypted:</strong> " . decrypt($text) . "</p>";
    }
}
