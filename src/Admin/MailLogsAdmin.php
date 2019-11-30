<?php
namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Route\RouteCollection;

class MailLogsAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'admin_sonata_logs_mail';
    protected $baseRouteName = 'admin_sonata_logs_mail';
    
    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->add('list');
        $collection->clearExcept(['list']);
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        $list['list'] ['template'] = ['list_button.html.twig'  ];

        return $list;
    }

    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['list'] = [
            'label' => 'list_action',
            'translation_domain' => 'App',
            'url' => $this->generateUrl('list'),
            'icon' => 'level-up',
        ];

        return $actions;
    }


}
