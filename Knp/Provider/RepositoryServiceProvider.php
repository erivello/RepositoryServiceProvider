<?php

namespace Knp\Provider;

use Silex\ServiceProviderInterface;
use Silex\Application;

class RepositoryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        foreach ($app['repository.repositories'] as $db => $repositories) {
            foreach ($repositories as $label => $class) {
                $app[$db . '_' . $label] = $app->share(function($app) use ($class, $db) {
                   return new $class($app['dbs'][$db]);
                });
            }
        }
    }
}
