<?php

function encrypt($string, $salt = SECURITY_SALT) 
{
    $encrypt_method = 'AES-256-CBC';
    $secret_key = $salt;
    $secret_iv = 'chiper';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));;
}

function decrypt($string, $salt = SECURITY_SALT) 
{
    $encrypt_method = 'AES-256-CBC';
    $secret_key = $salt;
    $secret_iv = 'chiper';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}

function encryptID($string, $salt = SEC_SALT_ID)
{
    return encrypt($string, $salt);
}

function decryptID($string, $salt = SEC_SALT_ID)
{
    return decrypt($string, $salt);
}