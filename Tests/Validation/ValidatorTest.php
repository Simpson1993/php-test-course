<?php

namespace Acme\Tests;

use Acme\Http\Request;
use Acme\Http\Response;
use Acme\Validation\Validator;

/**
 * Class ValidatorTest
 * @package Acme\Tests
 */
class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var Validator
     */
    protected $validator;
    protected $testData;

    /**
     * @param array $testData
     */
    protected function setUpRequestResponse(array $testData = [])
    {
        if ($this->testData === null) {
            $this->testData = $testData;
        }

        $this->request = new Request($this->testData);
        $this->response = new Response($this->request);
        $this->validator = new Validator($this->request, $this->response);
    }
    
    public function testGetIsValidReturnsTrue()
    {
        $this->setUpRequestResponse();
        $this->validator->setIsValid(true);
        $this->assertTrue($this->validator->getIsValid());
    }

    public function testGetIsValidReturnsFalse()
    {
        $this->setUpRequestResponse();
        $this->validator->setIsValid(false);
        $this->assertFalse($this->validator->getIsValid());
    }

    public function testCheckForMinStringLenghtWithValidData()
    {
        $this->setUpRequestResponse(['mintype' => 'yellow']);
        $errors = $this->validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLenghtWithInvalidData()
    {
        $this->setUpRequestResponse(['mintype' => 'x']);
        $errors = $this->validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailWithValidData()
    {
        $this->setUpRequestResponse(['mintype' => 'qwer@qwer.ty']);
        $errors = $this->validator->check(['mintype' => 'email']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailWithInvalideData()
    {
        $this->setUpRequestResponse(['mintype' => 'qwerty']);
        $errors = $this->validator->check(['mintype' => 'email']);
        $this->assertCount(1, $errors);
    }

    public function testValidateWithInvalidData()
    {
        $this->setUpRequestResponse(['check_field' => 'x']);
        $this->validator->validate(['check_field' => 'email'], '/register');
    }
}