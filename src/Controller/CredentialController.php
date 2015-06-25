<?php

namespace PRReviewWatcher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use PRReviewWatcher\Entity\Credential;
use PRReviewWatcher\Form\Type\CredentialType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CredentialController
{
    public function indexAction(Application $app)
    {
        $credentials = $app['credential_repository']->findAll();

        return $app['twig']->render('credList.html.twig', array('credentials' => $credentials));
    }

    public function addCredentialAction(Request $request, Application $app)
    {
        $credential     = new Credential();
        $credentialForm = $app['form.factory']->create(new CredentialType(), $credential, ['mapped' => true, 'disable' => false]);
        $credentialForm->handleRequest($request);

        if ($credentialForm->isSubmitted() && $credentialForm->isValid()) {
            $token      = $credential->getToken();
            $name       = $credential->getNameCred();

            try {
                $client   = new Client();
                $res      = $client->get('https://api.github.com/user', [
                    'auth' => [
                        'token',
                        $token
                    ]
                ]);
                $status   = $res->getStatusCode();
                $testName = json_decode($res->getBody()->getContents(), true);

            } catch (ClientException $e) {
                $status = $e->getResponse()->getStatusCode();
            }
            if ($status == 200) {
                if ($name == $testName['login']) {
                    $app['credential_repository']->save($credential);
                    $app['session']->getFlashBag()->add('success', 'The credential was successfully created.');
                } else {
                    $app['session']->getFlashBag()->add('danger',
                        'This is not the user assigned at this token.');
                }
            } else {
                $app['session']->getFlashBag()->add('danger',
                    'The token is incorect. (GitHub API error ' . $status . ')');
            }
        }

        return $app['twig']->render('credList_form.html.twig', array(
            'title'          => 'New credential',
            'legend'         => 'New credential',
            'credentialForm' => $credentialForm->createView(),
        ));
    }

    public function editCredentialAction($id, Request $request, Application $app)
    {
        $credential     = $app['credential_repository']->find($id);
        $credentialForm = $app['form.factory']->create(new CredentialType(), $credential, ['mapped' => false, 'disable' => true]);
        $credentialForm->handleRequest($request);

        if ($credentialForm->isSubmitted() && $credentialForm->isValid()) {
            $token      = $credential->getToken();
            $name       = $credential->getNameCred();

            try {
                $client   = new Client();
                $res      = $client->get('https://api.github.com/user', [
                    'auth' => [
                        'token',
                        $token
                    ]
                ]);
                $status   = $res->getStatusCode();
                $testName = json_decode($res->getBody()->getContents(), true);

            } catch (ClientException $e) {
                $status = $e->getResponse()->getStatusCode();
            }
            if ($status == 200) {
                if ($name == $testName['login']) {
                    $app['credential_repository']->save($credential);
                    $app['session']->getFlashBag()->add('success', 'The credential was successfully updated.');
                } else {
                    $app['session']->getFlashBag()->add('danger',
                        'This is not the user assigned at this token.');
                }
            } else {
                $app['session']->getFlashBag()->add('danger',
                    'The token is incorect. (GitHub API error ' . $status . ')');
            }
        }

        return $app['twig']->render('credList_form.html.twig', array(
            'title'          => 'Edit credential',
            'legend'         => 'Edit credential',
            'credentialForm' => $credentialForm->createView(),
        ));
    }

    public function deleteCredentialAction($id, Application $app)
    {
        $app['credential_repository']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The credential was successfully removed.');

        return $app->redirect('/admin/credential');
    }
}
