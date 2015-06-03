<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

$app = new Application();

$app['debug'] = true;

$app->get('/', function () {
    return 'Hello world';
});

$app->get('/lo', function () {
    return 'Hello world';
});


$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
	$data =json_decode($request->getContent(), true);
	$request->request->replace(is_array($data) ? $data : array());
    }
});


$app->post('/api', function(Request $request) use ($app) {

    $testOpened = $request->request->get('action');
    $testStateOpen = $request->request->get('pull_request')['state'];

    if(($testOpened == 'opened') && ($testStateOpen == 'open')) {

        $pullRequest = array(
            'action' => $request->request->get('action'),
            'state' => $request->request->get('pull_request')['state'],
            'number' => $request->request->get('number'),
            'pull_request' => $request->request->get('pull_request')
        );
        return $app->json($pullRequest,201);
    }
    return "Nop";
});




$app->run();
