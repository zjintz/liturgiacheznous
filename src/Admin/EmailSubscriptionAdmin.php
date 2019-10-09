<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelListType;

final class EmailSubscriptionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('isActive', CheckboxType::class,[
            'label' => 'email_subscription.label.is_active',
            'required' => false
        ]);
        $formMapper->add('periodicity', ChoiceType::class, [
            'choices'  => ["Diariamente"=> '1',
                           'Semanal'=> '7',
                           'Quinzenal'=>'14'] ,
            'label' => 'email_subscription.label.periodicity',
            'expanded' => false,
            'multiple' => false,
            'required' => true,
        ]);
        $formMapper->add('daysAhead', ChoiceType::class, [
            'choices'  => ["1"=> 1,
                           '2'=> 2,
                           '3'=> 3] ,
            'label' => 'email_subscription.label.days_ahead',
            'expanded' => false,
            'multiple' => false,
            'required' => true,
        ]);

    }

}
