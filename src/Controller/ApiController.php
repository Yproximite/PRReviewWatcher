<?php

namespace PRReviewWatcher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class ApiController
{
    public function createCheckListAction(Request $request, Application $app)
    {
        $repoHook      = $request->request->get('pull_request')['head']['repo']['full_name'];
        $branchHook    = $request->request->get('pull_request')['base']['ref'];
        $userHook      = $request->request->get('pull_request')['user']['login'];
        $numberHook    = $request->request->get('number');
        $testOpened    = $request->request->get('action');
        $testStateOpen = $request->request->get('pull_request')['state'];

        $id = $app['project_repository']->findId($repoHook, $userHook);

        //Creation of a pull request and not an issue.
        if (($testOpened == 'opened') && ($testStateOpen == 'open')) {
            $branch = $app['project_repository']->findBranch($repoHook);
            $token  = $app['credential_repository']->findToken($userHook);

            if ($branch == 'all') {
                $comment = $app['project_repository']->findComment($repoHook, null);
            } else {
                $comment = $app['project_repository']->findComment($repoHook, $branchHook);
            }
            if (($comment != null) && ($token != null)) {
                $content = [
                    'auth' => [
                        'token',
                        $token,
                    ]
                    ,
                    'json' => [
                        'body' => $comment,
                    ],
                ];

                $url    = 'https://api.github.com/repos/' . $repoHook . '/issues/' . $numberHook . '/comments';
                $client = new Client();
                $client->post($url, $content);

                $app['project_repository']->incrementNumber($id);

                return $app->json($content, 201);
            }
        }
    }
}
