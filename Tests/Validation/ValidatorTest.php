<?php

namespace Acme\Tests;

use Acme\Http\Request;
use Acme\Http\Response;
use Acme\Http\Session;
use Acme\Validation\Validator;
use duncan3dc\Laravel\BladeInstance;
use Kunststube\CSRFP\SignatureGenerator;
use Dotenv;

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
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var BladeInstance
     */
    protected $blade;

    protected function setUp()
    {
        $signer = $this->getMockBuilder(SignatureGenerator::class)
            ->setConstructorArgs(['qwe123'])
            ->getMock();

        $this->request = $this->getMockBuilder(Request::class)->getMock();
        $this->session = $this->getMockBuilder(Session::class)->getMock();
        $this->blade = $this->getMockBuilder(BladeInstance::class)
            ->setConstructorArgs(['qwe123', 'qwe'])
            ->getMock();
        $this->response = $this->getMockBuilder(Response::class)
            ->setConstructorArgs([$this->request, $signer, $this->blade, $this->session])
            ->getMock();
    }

    public function getReq($input = '')
    {
        $req = $this->getMockBuilder(Request::class)
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this::returnValue($input));

        return $req;
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
        $req = $this->getReq('yellow');
        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLenghtWithInvalidData()
    {
        $req = $this->getReq('x');
        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailWithValidData()
    {
        $req = $this->getReq('qwer@qwer.ty');
        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailWithInvalideData()
    {
        $req = $this->getReq('qwerty');

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEqualToWithValidData()
    {
        //All methods are stubs, all methods return NULL, all methods can be overridden.
        $req = $this->getMockBuilder(Request::class)->getMock();
        $req->expects($this->at(0))
            ->method('input')
            ->willReturn('jack');
        $req->expects($this->at(1))
            ->method('input')
            ->willReturn('jack');

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['my_field' => 'equalTo:another_field']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEqualToWithInvalidData()
    {
        $req = $this->getMockBuilder(Request::class)->getMock();
        $req->expects($this->at(0))
            ->method('input')
            ->willReturn('jack');
        $req->expects($this->at(1))
            ->method('input')
            ->willReturn('john');

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['my_field' => 'equalTo:another_field']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForUniqueWithValidData()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['getRows'])
            ->getMock();

        $validator->method('getRows')->willReturn([]);
        $errors = $validator->check(['my_field' => 'unique:User']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForUniqueWithInalidData()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['getRows'])
            ->getMock();

        $validator->method('getRows')->willReturn(['a']);
        $errors = $validator->check(['my_field' => 'unique:User']);
        $this->assertCount(1, $errors);
    }
}
