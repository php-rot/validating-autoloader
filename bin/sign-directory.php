#!/usr/bin/env php
<?php
if (count($argv) == 1) {
  fwrite(STDERR, "Please pass a path containing files to sign.");
  exit(1);
}

$key = file_get_contents('private_key');
if (strlen($key) != SODIUM_CRYPTO_SIGN_SECRETKEYBYTES) {
  die('Failed to read proper key file. Run this from a directory you have run bin/generate-keys.php in.');
}

array_shift($argv);
$iter = new RecursiveDirectoryIterator(implode(' ', $argv), FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS);
foreach ($iter as $filename => $fileInfo) {
  if (substr($filename, -4) == '.php') {
    $sig = sodium_crypto_sign_detached(file_get_contents($filename), $key);
    file_put_contents("${filename}.sig", $sig);
  }
}
