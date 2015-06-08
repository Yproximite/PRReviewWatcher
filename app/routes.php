<?php

require ('../web/Data.php');
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
});
$app->get('/depot/add', function () use ($app) {
    return $app['twig']->render('createCheckList.html.twig');
});
$app->get('/token', function () use ($app) {
    return $app['twig']->render('userToken.html.twig');
});
$app->get('/token/add', function () use ($app) {
    return $app['twig']->render('createUserToken.html.twig');
});


$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
	$data =json_decode($request->getContent(), true);
	$request->request->replace(is_array($data) ? $data : array());
    }
});

$app->post('/api', function(Request $request) use($app,$content) {
    $branch = $request->request->get('pull_request')['base']['ref'];
    $user = $request->request->get('pull_request')['user']['login'];
    $number = $request->request->get('number');
    $repo = $request->request->get('pull_request')['head']['repo']['full_name'];
    $url ='https://api.github.com/repos/'.$repo.'/issues/'.$number.'/comments';

    $testOpened = $request->request->get('action');
    $testStateOpen = $request->request->get('pull_request')['state'];

    if(($testOpened == 'opened') && ($testStateOpen == 'open')) {
        $client = new GuzzleHttp\Client();
        $client->post($url, $content);
        return $app->json($content,201);
    }
    return "Nop";
});

