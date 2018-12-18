<?php

namespace Acme\Tests;

use Acme\Http\Request;
use Acme\Http\Response;
use Acme\Validation\Validator;
use Kunststube\CSRFP\SignatureGenerator;

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

//    protected $testData;

    protected function setUp()
    {
        $signer = $this->getMockBuilder(SignatureGenerator::class)
            ->setConstructorArgs(['qwe123'])
            ->getMock();

        $this->request = $this->getMockBuilder(Request::class)
            ->getMock();
        $this->response = $this->getMockBuilder(Response::class)
            ->setConstructorArgs([$this->request, $signer])
            ->getMock();
    }

    public function testGetIsValidReturnsTrue()
    {
        $validator = new Validator($this->request, $this->response);
        $validator->setIsValid(true);
        $this->assertTrue($validator->getIsValid());
    }

    public function testGetIsValidReturnsFalse()
    {
        $validator = new Validator($this->request, $this->response);
        $validator->setIsValid(false);
        $this->assertFalse($validator->getIsValid());
    }

    public function testCheckForMinStringLenghtWithValidData()
    {
        $req = $this->getMockBuilder(Request::class)
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this::returnValue('yellow'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLenghtWithInvalidData()
    {
        $req = $this->getMockBuilder(Request::class)
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this::returnValue('x'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailWithValidData()
    {
        $req = $this->getMockBuilder(Request::class)
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this::returnValue('qwer@qwer.ty'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailWithInvalideData()
    {
        $req = $this->getMockBuilder(Request::class)
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this::returnValue('qwerty'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(1, $errors);
    }
//
//    public function testValidateWithInvalidData()
//    {
//        $this->setUpRequestResponse(['check_field' => 'x']);
//        $this->validator->validate(['check_field' => 'email'], '/register');
//    }
}