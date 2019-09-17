<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;


final class LiturgyAdmin extends AbstractAdmin
{
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', ['class' => 'col-md-7'])->end()
            ->with('Special', ['class' => 'col-md-5'])->end()
            ->with('Text',['class' => 'col-md-12'])->end()
            ;

        $formMapper
            ->with('General')
                ->add('date')
                ->add('liturgyDay')
            ->add('description',  TextType::class)
                ->add('color')
            ->add('yearType')
            ->end()
            ->with('Special')
                ->add('isSolemnity')
                ->add('isSolemnityVFC')
                ->add('isCelebration')
                ->add('isCelebrationVFC')
                ->add('isMemorial')
                ->add('isMemorialVFC')
                ->add('isMemorialFree')
            ->end()
            ->with('Text')
            ->add('alleluiaReference',  TextType::class)
            ->add('alleluiaVerse',  TextType::class)
            ->add('summary',  TextType::class)
            ->end()
            ;

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('date');
        $datagridMapper->add('liturgyDay');
        $datagridMapper->add('color');
        $datagridMapper->add('isSolemnity');
        $datagridMapper->add('isSolemnityVFC');
        $datagridMapper->add('isCelebration');
        $datagridMapper->add('isCelebrationVFC');
        $datagridMapper->add('isMemorial');
        $datagridMapper->add('isMemorialVFC');
        $datagridMapper->add('isMemorialFree');
        $datagridMapper->add('yearType');
        $datagridMapper->add('alleluiaReference');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('date');
        $listMapper->addIdentifier('liturgyDay');
        $listMapper->addIdentifier('description');
        $listMapper->addIdentifier('color');
        $listMapper->addIdentifier('isSolemnity');
        $listMapper->addIdentifier('isSolemnityVFC');
        $listMapper->addIdentifier('isCelebration');
        $listMapper->addIdentifier('isCelebrationVFC');
        $listMapper->addIdentifier('isMemorial');
        $listMapper->addIdentifier('isMemorialVFC');
        $listMapper->addIdentifier('isMemorialFree');
        $listMapper->addIdentifier('yearType');
        $listMapper->addIdentifier('alleluiaReference');
        $listMapper->addIdentifier('alleluiaVerse');
        $listMapper->addIdentifier('summary');

    }

     protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->with('General', ['class' => 'col-md-7'])->end()
                ->with('Special', ['class' => 'col-md-5'])->end()
                ->with('Text',['class' => 'col-md-12'])->end()
        ;

        $showMapper
            ->with('General')
                ->add('date')
                ->add('liturgyDay')
                ->add('description')
                ->add('color')
                ->add('yearType')
            ->end()
            ->with('Special')
                ->add('isSolemnity')
                ->add('isSolemnityVFC')
                ->add('isCelebration')
                ->add('isCelebrationVFC')
                ->add('isMemorial')
                ->add('isMemorialVFC')
                ->add('isMemorialFree')
            ->end()
            ->with('Text')
                ->add('alleluiaReference')
                ->add('alleluiaVerse')
                ->add('summary')
            ->end()
            ;
    }

    
}
