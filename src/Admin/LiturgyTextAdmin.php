<?php
namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Route\RouteCollection;

class LiturgyTextAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'liturgy_text';
    protected $baseRouteName = 'liturgy_text';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}
