<?php

namespace PRReviewWatcher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use PRReviewWatcher\Entity\Credential;
use PRReviewWatcher\Form\Type\CredentialType;

class CredentialController
{
    public function indexAction(Application $app)
    {
        $credentials = $app['credential_repository']->findAll();

        return $app['twig']->render('credList.html.twig', array('credentials' => $credentials));
    }

    public function addCredentialAction(Request $request, Application $app)
    {
        $credential = new Credential();
        $credentialForm = $app['form.factory']->create(new CredentialType(), $credential);
        $credentialForm->handleRequest($request);
        if ($credentialForm->isSubmitted() && $credentialForm->isValid()) {
            $app['credential_repository']->save($credential);
            $app['session']->getFlashBag()->add('success', 'The credential was successfully created.');
        }

        return $app['twig']->render('credList_form.html.twig', array(
            'title' => 'New credential',
            'legend' => 'New credential',
            'credentialForm' => $credentialForm->createView(),
        ));
    }

    public function editCredentialAction($id, Request $request, Application $app)
    {
        $credential = $app['credential_repository']->find($id);
        $credentialForm = $app['form.factory']->create(new CredentialType(), $credential);
        $credentialForm->handleRequest($request);
        if ($credentialForm->isSubmitted() && $credentialForm->isValid()) {
            $app['credential_repository']->save($credential);
            $app['session']->getFlashBag()->add('success', 'The credential was successfully updated.');
        }

        return $app['twig']->render('credList_form.html.twig', array(
            'title' => 'Edit credential',
            'legend' => 'Edit credential',
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
