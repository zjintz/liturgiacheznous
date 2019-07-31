<?php
// src/Admin/CategoryAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractAdmin
{

    protected $baseRouteName = 'app_'; 
    protected $baseRoutePattern = 'app_';
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('firstname', TextType::class);
        $formMapper->add('lastname', TextType::class);
        $formMapper->add('email', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstname');
        $datagridMapper->add('lastname');
        $datagridMapper->add('email');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('firstname');
        $listMapper->addIdentifier('lastname');
        $listMapper->addIdentifier('email');
    }
}
