<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Generates an RSA keypair.
 *
 * @return array Array containing the public and private keys.
 */
function generateKeypair()
{
    $config = array(
        "digest_alg"       => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // create the private and public key
    $res = openssl_pkey_new($config);

    // extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];

    return [$pubKey, $privKey];
}

/**
 * Encrypts data.
 * @param string $data   Data to be encrypted.
 * @param string $pubKey Public key.
 *
 * @return string Encrypted data.
 */
function encrypt($data, $pubKey)
{
    $password = sha1(microtime(true));
    $encryptedPassword = "";
    openssl_public_encrypt($password, $encryptedPassword, $pubKey);

    $encryptedData = mb_strlen($encryptedPassword) . '|' . $encryptedPassword . openssl_encrypt($data, 'aes128', $password, 0, '1234567812345678');

    return $encryptedData;
}

/**
 * Decrypts data.
 * @param string $encryptedData   Data to be decrypted.
 * @param string $privKey         Private key.
 *
 * @return string Decrypted data.
 */
function decrypt($encryptedData, $privKey)
{
    $encryptedPasswordSeparator = strpos($encryptedData, '|');
    $encryptedPasswordLen = (int) substr($encryptedData, 0, $encryptedPasswordSeparator);
    $encryptedPassword = substr($encryptedData, $encryptedPasswordSeparator + 1, $encryptedPasswordLen);

    $decryptedPassword = '';
    openssl_private_decrypt($encryptedPassword, $decryptedPassword, $privKey);

    return openssl_decrypt(substr($encryptedData, $encryptedPasswordSeparator + 1 + $encryptedPasswordLen), 'aes128', $decryptedPassword, 0, '1234567812345678');
}

$str = 'PHP Summer Camp 2014';

$keys = generateKeypair();

$encrypted = encrypt($str, $keys[0]);
$decrypted = decrypt($encrypted, $keys[1]);

echo "Encrypted data:\n$encrypted\n\n";
echo "Decrypted data:\n$decrypted\n";



