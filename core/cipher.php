<?php

function encrypt($plaintext, $key){
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $ciphertext = sodium_crypto_secretbox($plaintext, $nonce, $key);

    $result = sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
    return $result;
}

function decrypt($ciphertext, $key){
    $c1 = sodium_base642bin($ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
    $nonce = mb_substr($c1, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
    $ciphertext = mb_substr($c1, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

    $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
    return $plaintext;
}