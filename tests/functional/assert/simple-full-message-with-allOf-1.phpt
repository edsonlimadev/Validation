--TEST--
Should throw validator exception when asserting and display full message
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->allOf(v::match('/^[a-z]+$/'))
        ->assert(123456);
} catch (ValidationException $exception) {
    echo $exception->getFullMessage().PHP_EOL;
}
?>
--EXPECTF--
- All rules must pass for 123456
  - 123456 must match `/^[a-z]+$/`
