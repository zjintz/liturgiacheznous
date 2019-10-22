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
            'choices'  => ["Diaria"=> '1',
                           'Semanal'=> '7',
                           'Quinzenal'=>'14'] ,
            'label' => 'email_subscription.label.periodicity',
            'expanded' => true,
            'multiple' => false,
            'required' => true,
        ]);

        $formMapper->add('source', ChoiceType::class, [
            'choices'  => ['CNBB'=> 'CNBB',
                           'Igreja Santa Ines'=>'Igreja_Santa_Ines'] ,
            'label' => 'form.label.source',
            'expanded' => true,
            'multiple' => true,
            'required' => true,
        ]);
        $formMapper->add('format', ChoiceType::class, [
            'choices'  => ["DOCX"=> 'DOCX',
                           'PDF'=> 'PDF',],

            'label' => 'form.label.text_format',
            'expanded' => true,
            'multiple' => true,
            'required' => true,
        ]);


    }

}
