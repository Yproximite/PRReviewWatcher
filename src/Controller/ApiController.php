<?php

namespace PRReviewWatcher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class ApiController
{
    public function createCheckListAction(Request $request, Application $app)
    {
        $repoHook = $request->request->get('pull_request')['head']['repo']['full_name'];
        $branchHook = $request->request->get('pull_request')['base']['ref'];
        $userHook = $request->request->get('pull_request')['user']['login'];
        $numberHook = $request->request->get('number');
        $testOpened = $request->request->get('action');
        $testStateOpen = $request->request->get('pull_request')['state'];
        $resultId = $app['project_repository']->findId($repoHook, $userHook);
        $resultCredential = $app['credential_repository']->findNameCredential($repoHook);

        if (($testOpened == 'opened') && ($testStateOpen == 'open')) {
            $resultBranch = $app['project_repository']->findBranch($repoHook);
            $resultToken = $app['credential_repository']->findToken($userHook);

            if ($resultBranch == 'all') {
                $resultComment = $app['project_repository']->findComment($repoHook, null);
            } else {
                $resultComment = $app['project_repository']->findComment($repoHook, $branchHook);
            }

            $content = [
                'headers' => [
                    'User-Agent' => $resultCredential,
                ],
                'auth' => [
                    'token',
                    $resultToken,
                ]
                ,
                'json' => [
                    'body' => $resultComment,
                ],
            ];
            $url = 'https://api.github.com/repos/'.$repoHook.'/issues/'.$numberHook.'/comments';
            $client = new Client();
            $client->post($url, $content);
            $app['project_repository']->incrementNumber($resultId);

            return $app->json($content, 201);
        }
    }
}
