<?php

$app->get('/', function () use ($app) {
    return $app->redirect('/admin/project');
});

$app->get('/admin/project', 'PRReviewWatcher\Controller\ProjectController::indexAction')
    ->bind('listProject');
$app->match('/admin/project/add', 'PRReviewWatcher\Controller\ProjectController::addProjectAction')
    ->bind('addProject');
$app->match('/admin/project/{id}/edit', 'PRReviewWatcher\Controller\ProjectController::editProjectAction')
    ->bind('editProject');
$app->get('/admin/project/{id}/delete', 'PRReviewWatcher\Controller\ProjectController::deleteProjectAction')
    ->bind('deleteProject');

$app->get('/admin/credential', 'PRReviewWatcher\Controller\CredentialController::indexAction')
    ->bind('listCred');
$app->match('/admin/credential/add', 'PRReviewWatcher\Controller\CredentialController::addCredentialAction')
    ->bind('addCred');
$app->match('/admin/credential/{id}/edit', 'PRReviewWatcher\Controller\CredentialController::editCredentialAction')
    ->bind('editCred');
$app->get('/admin/credential/{id}/delete', 'PRReviewWatcher\Controller\CredentialController::deleteCredentialAction')
    ->bind('deleteCred');

$app->post('/api', 'PRReviewWatcher\Controller\ApiController::createCheckListAction');

$app->get('/login', 'PRReviewWatcher\Controller\LoginController::loginAction')
    ->bind('login');
