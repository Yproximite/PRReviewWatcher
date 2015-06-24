<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

ErrorHandler::register();
ExceptionHandler::register();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));
$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/../web/users.yml'));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/admin',
            'logout'  => array('logout_path' => '/admin/logout'),
            'form'    => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'users' => $app['config']
        ),
    ),
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path'   => __DIR__ . '/../db/PRReviewer.db',
    ),
));
$schema = $app['db']->getSchemaManager();
if (!$schema->tablesExist('project')) {
    $project = new Table('project');
    $project->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $project->setPrimaryKey(array('id'));
    $project->addColumn('name', 'string', array('length' => 32));
    $project->addColumn('branch', 'string', array('length' => 32));
    $project->addColumn('credential', 'string', array('length' => 32));
    $project->addColumn('comment', 'text');
    $project->addColumn('alive', 'integer');
    $project->addColumn('numberTaskList', 'integer');

    $schema->createTable($project);
}
if (!$schema->tablesExist('credential')) {
    $credential = new Table('credential');
    $credential->addColumn('idCred', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $credential->setPrimaryKey(array('idCred'));
    $credential->addColumn('nameCred', 'string', array('length' => 32));
    $credential->addColumn('token', 'string', array('length' => 32));

    $schema->createTable($credential);
};

$app['security.encoder.digest'] = $app->share(function () {
    return new MessageDigestPasswordEncoder('sha1', false, 1);
});
$app['project_repository']      = $app->share(function ($app) {
    return new PRReviewWatcher\Entity\ProjectRepository($app['db']);
});
$app['credential_repository']   = $app->share(function ($app) {
    return new PRReviewWatcher\Entity\CredentialRepository($app['db']);
});

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
