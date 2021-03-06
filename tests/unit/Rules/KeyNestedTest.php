<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use ArrayObject;

/**
 * @group  rule
 * @covers Respect\Validation\Rules\KeyNested
 * @covers Respect\Validation\Exceptions\KeyNestedException
 */
class KeyNestedTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayWithPresentKeysWillReturnTrueForFullPathValidator()
    {
        $array = array(
            'bar' => array(
                'foo'   => array(
                    'baz' => 'hello world!',
                ),
                'foooo' => array(
                    'boooo' => 321,
                ),
            ),
        );

        $rule = new KeyNested('bar.foo.baz');

        $this->assertTrue($rule->validate($array));
    }

    public function testArrayWithPresentKeysWillReturnTrueForHalfPathValidator()
    {
        $array = array(
            'bar' => array(
                'foo'   => array(
                    'baz' => 'hello world!',
                ),
                'foooo' => array(
                    'boooo' => 321,
                ),
            ),
        );

        $rule = new KeyNested('bar.foo');

        $this->assertTrue($rule->validate($array));
    }

    public function testOnjectWithPresentPropertiesWillReturnTrueForDirtyPathValidator()
    {
        $object = (object) array(
            'bar' => (object) array(
                'foo'   => (object) array(
                    'baz' => 'hello world!',
                ),
                'foooo' => (object) array(
                    'boooo' => 321,
                ),
            ),
        );

        $rule = new KeyNested('bar.foooo.');

        $this->assertTrue($rule->validate($object));
    }

    public function testEmptyInputMustReturnFalse()
    {
        $rule = new KeyNested('bar.foo.baz');

        $this->assertFalse($rule->validate(''));
    }

    /**
     * @expectedException Respect\Validation\Exceptions\KeyNestedException
     */
    public function testEmptyInputMustNotAssert()
    {
        $rule = new KeyNested('bar.foo.baz');
        $rule->assert('');
    }

    /**
     * @expectedException Respect\Validation\Exceptions\KeyNestedException
     */
    public function testEmptyInputMustNotCheck()
    {
        $rule = new KeyNested('bar.foo.baz');
        $rule->check('');
    }

    public function testArrayWithEmptyKeyShouldReturnTrue()
    {
        $rule = new KeyNested('emptyKey');
        $input = array('emptyKey' => '');

        $this->assertTrue($rule->validate($input));
    }

    /**
     * @expectedException Respect\Validation\Exceptions\KeyNestedException
     */
    public function testArrayWithAbsentKeyShouldThrowNestedKeyException()
    {
        $validator = new KeyNested('bar.bar');
        $object = array(
            'baraaaaaa' => array(
                'bar' => 'foo',
            ),
        );
        $this->assertTrue($validator->assert($object));
    }

    /**
     * @expectedException Respect\Validation\Exceptions\KeyNestedException
     */
    public function testNotArrayShouldThrowKeyException()
    {
        $validator = new KeyNested('baz.bar');
        $object = 123;
        $this->assertFalse($validator->assert($object));
    }

    public function testExtraValidatorShouldValidateKey()
    {
        $subValidator = new Length(3, 7);
        $validator = new KeyNested('bar.foo.baz', $subValidator);
        $object = array(
            'bar' => array(
                'foo'   => array(
                    'baz' => 'example',
                ),
            ),
        );
        $this->assertTrue($validator->assert($object));
    }

    public function testNotMandatoryExtraValidatorShouldPassWithAbsentKey()
    {
        $subValidator = new Length(1, 3);
        $validator = new KeyNested('bar.rab', $subValidator, false);
        $object = new \stdClass();
        $this->assertTrue($validator->validate($object));
    }

    public function testArrayAccessWithPresentKeysWillReturnTrue()
    {
        $arrayAccess = new ArrayObject(array(
            'bar' => array(
                'foo'   => array(
                    'baz' => 'hello world!',
                ),
                'foooo' => array(
                    'boooo' => 321,
                ),
            ),
        ));

        $rule = new KeyNested('bar.foo.baz');

        $this->assertTrue($rule->validate($arrayAccess));
    }
}
