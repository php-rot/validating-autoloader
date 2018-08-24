#!/usr/bin/env php
<?php

// I don't know if this is the "right" way to do it; the c crypto_sign_keypair function
// populates two separate buffers but the php wrapper appends them into a single string.
// It seems really weird?

$keys = sodium_crypto_sign_keypair();
file_put_contents('private_key', substr($keys, 0, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES));
file_put_contents('public_key', substr($keys, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES));

