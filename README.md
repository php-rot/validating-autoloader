# Validating autoloader w/ opcache experiments

This uses libsodium to sign / verify scripts, and has a very simple autoloader whose purpose
is to test the viability of efficiently verifying script files before they are executed by
trusting that their presence in PHP's opcache indicates they are trusted, and only including
scripts after their contents have been verified.

## Usage:
  1. `cd bin/ && php generate-keys.php`. This will create a public and private key for you in files
     `public_key` and `private_key`.
  2. `php sign-directory.php ../Package`. This will compute the signature of each php file in `../Package` and
      drop them in an adjacent file. `Package` files are autoloaded next.
  3.  Copy the `public_key` into the same directory as `autoloader.php`; this just seemed a sensible place to put it.
  4.  In the project root, run `php test.php`.
      * Correct operation results in the output "This is the desired implementation of CoolService!"
      * A modified file that is not available in the opcache results in "Modified file detected!"
      * Try to modify `Package/CoolService.php` and have it executed undetected!

## Limitations:
  * You have to be really careful not to include or directly execute scripts in any way that 
    doesn't verify them first. The entrypoint/"`index.php`" script must be read-only.
  * There's a race condition between verifying a file's contents and compiling it. It's probably
    challenging to exploit but it's there.

A php extension explicitly designed for using opcache as validated storaage could solve these issues.

https://github.com/paragonie/halite may be a more civilized way to deal with the php wrapper
to libsodium.