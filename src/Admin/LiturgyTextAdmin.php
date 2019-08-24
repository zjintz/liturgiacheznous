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

        $collection->add('assemble');
        $collection->clearExcept(['assemble']);
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        $list['assemble'] = [
            'label' => 'assemble_action',
            'translation_domain' => 'SonataAdminBundle',
            'url' => $this->generateUrl('assemble'),
            'icon' => 'level-up',
        ];

        return $list;
    }

    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['assemble'] = [
            'label' => 'assemble_action',
            'translation_domain' => 'SonataAdminBundle',
            'url' => $this->generateUrl('assemble'),
            'icon' => 'level-up',
        ];

        return $actions;
    }

    public function getActionButtons($action, $object = null)
    {
        $list = parent::getActionButtons($action, $object);

        $list['assemble'] = [
            'label' => 'assemble_action',
            'translation_domain' => 'SonataAdminBundle',
            'url' => $this->generateUrl('assemble'),
            'icon' => 'level-up',
        ];

        return $list;
    }
}
