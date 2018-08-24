<?php

// only run everything this file does once, in case of sloppy excessive include()s
if(! function_exists('autoload_function')) {
  function autoload_function($name) {
    static $pubkey = '';
    if ($pubkey == '') {
      $pubkey = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'public_key');
    }

    $subdir = str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';
    $ourLocation = dirname(__FILE__);
    $file = $ourLocation . DIRECTORY_SEPARATOR . $subdir;
    ini_set('opcache.validate_timestamps', false);
    if (opcache_is_script_cached($file) && empty(getenv('ALWAYS_VERIFY'))) {
      require $file;
    } else if (sodium_crypto_sign_verify_detached(file_get_contents("${file}.sig"), file_get_contents($file), $pubkey)) {
      require $file;
    } else {
      die("Modified file detected!\n");
    }
    ini_set('opcache.validate_timestamps', true);
  }

  spl_autoload_register('autoload_function');
}

