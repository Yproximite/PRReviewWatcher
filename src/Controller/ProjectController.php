<?php

namespace PRReviewWatcher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use PRReviewWatcher\Entity\Project;
use PRReviewWatcher\Form\Type\ProjectType;

class ProjectController
{
    public function indexAction(Application $app)
    {
        $projects = $app['project_repository']->findAll();

        return $app['twig']->render('index.html.twig', array('projects' => $projects));
    }

    public function addProjectAction(Request $request, Application $app)
    {
        $credentials = $app['credential_repository']->findAllAsArray();
        $project     = new Project();
        $projectForm = $app['form.factory']->create(new ProjectType(), $project, ['credentialChoices' => $credentials]);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $app['project_repository']->save($project);
            $app['session']->getFlashBag()->add('success', 'The project was successfully created.');
        }

        return $app['twig']->render('projectList_form.html.twig', array(
            'title'       => 'New project',
            'legend'      => 'New project',
            'projectForm' => $projectForm->createView(),
        ));
    }

    public function editProjectAction($id, Request $request, Application $app)
    {
        $credentials = $app['credential_repository']->findAllAsArray();
        $project     = $app['project_repository']->find($id);
        $projectForm = $app['form.factory']->create(new ProjectType(), $project, ['credentialChoices' => $credentials]);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $app['project_repository']->save($project);
            $app['session']->getFlashBag()->add('success', 'The project was successfully updated.');
        }

        return $app['twig']->render('projectList_form.html.twig', array(
            'title'       => 'Edit project',
            'legend'      => 'Edit project',
            'projectForm' => $projectForm->createView(),
        ));
    }

    public function deleteProjectAction($id, Application $app)
    {
        $app['project_repository']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The project was successfully removed.');

        return $app->redirect('/admin/project');
    }
}
