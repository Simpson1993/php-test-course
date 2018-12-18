<?php

$injector = new \Auryn\Injector;

$signer = new Kunststube\CSRFP\SignatureGenerator(getenv('CSRF_SECRET'));
$injector->share($signer);
$blade = new duncan3dc\Laravel\BladeInstance(getenv('VIEWS_DIRECTORY'), getenv('CACHE_DIRECTORY'));
$injector->share($blade);

$injector->make(Acme\Http\Request::class);
$injector->make(Acme\Http\Response::class);
$injector->make(Acme\Http\Session::class);

$injector->share(Acme\Http\Request::class);
$injector->share(Acme\Http\Response::class);
$injector->share(Acme\Http\Session::class);

return $injector;