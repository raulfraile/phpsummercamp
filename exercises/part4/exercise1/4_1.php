<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$finder = new Finder();
$finder->in(__DIR__ . '/')
    ->files()
    ->name('*.php');

$hash = hash_init('sha512');
foreach ($finder as $file) {
    hash_update($hash, $file->getContents());
}

echo hash_final($hash);