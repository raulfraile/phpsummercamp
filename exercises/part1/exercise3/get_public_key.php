<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

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

$keys = generateKeypair();

// save the private key
$id = sha1($keys[0]);
$filename = __DIR__ . '/keys/' . $id . '.key';
file_put_contents($filename, $keys[1]);

// return public key
$response = array(
    'id'  => $id,
    'key' => $keys[0]
);

echo json_encode($response);