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
        $formMapper->add('liturgyDay', TextType::class);
        $formMapper->add('description', TextType::class);
        $formMapper->add('color', TextType::class);
        $formMapper->add('yearType', TextType::class);
        $formMapper->add('alleluiaReference', TextType::class);
        $formMapper->add('alleluiaVerse', TextType::class);
        $formMapper->add('summary', TextType::class);
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
        $showMapper->add('date');
        $showMapper->add('liturgyDay');
        $showMapper->add('description');
        $showMapper->add('color');
        $showMapper->add('isSolemnity', 'boolean');
        $showMapper->add('isSolemnityVFC');
        $showMapper->add('isCelebration');
        $showMapper->add('isCelebrationVFC');
        $showMapper->add('isMemorial');
        $showMapper->add('isMemorialVFC');
        $showMapper->add('isMemorialFree');
        $showMapper->add('yearType');
        $showMapper->add('alleluiaReference');
        $showMapper->add('alleluiaVerse');
        $showMapper->add('summary');
    }

    
}
