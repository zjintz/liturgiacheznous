<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\Form\Type\EqualType;
use Sonata\Form\Type\BooleanType;
use Sonata\Form\Type\DateRangeType;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\Form\Type\DateRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;

/**
 * Sonata Admin for the Liturgy.
 *
 *
 */
final class LiturgyAdmin extends AbstractAdmin
{
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with(
                'General',
                ['class' => 'col-md-7', 'label' => 'liturgy.title.general']
            )
            ->end()
            ->with('Special', ['class' => 'col-md-5'])->end()
            ->with('Text', ['class' => 'col-md-7'])->end()
            ;

        $formMapper
            ->with('General')
            ->add('Summary', TextType::class, ['label' => 'liturgy.label.summary',])
            ->add('date', DateType::class, ['label' => 'liturgy.label.date'])
            ->add('liturgyDay', TextType::class, ['label' => 'liturgy.label.liturgy_day',])
            ->add(
                'description',
                TextType::class,
                ['label' => 'liturgy.label.description',]
            )
            ->add(
                'color',
                ChoiceType::class,
                ['choices'  => [
                          'Azul' => 'Azul',
                          'Branco' => 'Branco',
                          'Dourado' => 'Dourado',
                          'Preto' => 'Preto',
                          'Rosa' => 'Rosa',
                          'Roxo' => 'Roxo',
                          'Vermelho' => 'Vermelho',
                         'Verde' => 'Verde',
                      ],
                 'expanded' => false,
                 'multiple' => false,
                 'required' => true,
                 'label' => 'liturgy.label.color',
                ]
            )
            ->add(
                'yearType',
                ChoiceType::class,
                ['choices'  => [
                          'A' => 'A',
                          'B' => 'B',
                          'C' => 'C',
                      ],
                 'expanded' => false,
                 'multiple' => false,
                 'required' => true,
                 'label' => 'liturgy.label.year_type',
                ]
            )
            ->end()
            ->with('Special', ['label' => 'liturgy.title.special'])
            ->add(
                'isSolemnity',
                null,
                ['label' => 'liturgy.label.is_solemnity']
            )
            ->add(
                'isSolemnityVFC',
                null,
                ['label' => 'liturgy.label.is_solemnity_vfc']
            )
            ->add(
                'isCelebration',
                null,
                ['label' => 'liturgy.label.is_celebration']
            )
            ->add(
                'isCelebrationVFC',
                null,
                ['label' => 'liturgy.label.is_celebration_vfc']
            )
            ->add('isMemorial', null, ['label' => 'liturgy.label.is_memorial'])
            ->add(
                'isMemorialVFC',
                null,
                ['label' => 'liturgy.label.is_memorial_vfc']
            )
            ->add(
                'isMemorialFree',
                null,
                ['label' => 'liturgy.label.is_memorial_free']
            )
            ->end()
            ->with('Text', ['label' => 'liturgy.title.text'])
            ->add(
                'alleluiaReference',
                TextType::class,
                ['label' => 'liturgy.label.alleluia_reference']
            )
            ->add(
                'alleluiaVerse',
                TextType::class,
                ['label' => 'liturgy.label.alleluia_verse']
            )
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('date', DateRangeFilter::class, [
            'field_type' => DateRangePickerType::class,
            'advanced_filter' => false,
        ]);

        $datagridMapper->add('liturgyDay', null , [
            'operator_type' => 'sonata_type_equal',
            'advanced_filter' => false
        ]);
        
        $datagridMapper->add('color' ,  null, [
            'operator_type' => 'sonata_type_equal',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isSolemnity', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isSolemnityVFC', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isCelebration', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isCelebrationVFC', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isMemorial', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isMemorialVFC', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('isMemorialFree', null , [
            'operator_type' => 'sonata_type_boolean',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('yearType', null, [
            'operator_type' => 'sonata_type_equal',
            'advanced_filter' => false
        ]);
        $datagridMapper->add('alleluiaReference', null, [
            'operator_type' => 'sonata_type_equal',
            'advanced_filter' => false
        ]);
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier(
            'date',
            null,
            ['label' => 'liturgy.label.date']
        );
        $listMapper->addIdentifier(
            'liturgyDay',
            null,
            ['label' => 'liturgy.label.liturgy_day']
        );
        $listMapper->addIdentifier(
            'description',
            null,
            ['label' => 'liturgy.label.description']
        );
        $listMapper->add('color', null, ['label' => 'liturgy.label.color']);
        $listMapper->add(
            'isSolemnity',
            null,
            ['label' => 'liturgy.label.is_solemnity']);
        $listMapper->add(
            'isSolemnityVFC', null, ['label' => 'liturgy.label.is_solemnity_vfc']);
        $listMapper->add('isCelebration', null, ['label' => 'liturgy.label.is_celebration']);
        $listMapper->add('isCelebrationVFC', null, ['label' => 'liturgy.label.is_celebration_vfc']);
        $listMapper->add(
            'isMemorial',
            null,
            ['label' => 'liturgy.label.is_memorial']);
        $listMapper->add(
            'isMemorialVFC',
            null,
            ['label' => 'liturgy.label.is_memorial_vfc']
        );
        $listMapper->add(
            'isMemorialFree',
            null,
            ['label' => 'liturgy.label.is_memorial_free']
        );
        $listMapper->add(
            'yearType',
            null,
            ['label' => 'liturgy.label.year_type']
        );
        $listMapper->add('alleluiaReference', null, ['label' => 'liturgy.label.alleluia_reference']);
        $listMapper->add('alleluiaVerse', null, ['label' => 'liturgy.label.alleluia_verse']);
        $listMapper->addIdentifier('summary', null, ['label' => 'liturgy.label.summary']);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->with('General', ['class' => 'col-md-7'])->end()
                ->with('Special', ['class' => 'col-md-5'])->end()
                ->with('Text', ['class' => 'col-md-12'])->end()
        ;

        $showMapper
            ->with('General', ['label' => 'liturgy.title.general'])
            ->add('summary', null, ['label' => 'liturgy.label.summary'])
            ->add('date', null, ['label' => 'liturgy.label.date'])
            ->add('liturgyDay', null, ['label' => 'liturgy.label.liturgy_day'])
            ->add('description', null, ['label' => 'liturgy.label.description'])
            ->add('color', null, ['label' => 'liturgy.label.color'])
            ->add('yearType', null, ['label' => 'liturgy.label.year_type'])
            ->end()
            ->with('Special', ['label' => 'liturgy.title.special'])
            ->add('isSolemnity', null, ['label' => 'liturgy.label.is_solemnity'])
            ->add('isSolemnityVFC', null, ['label' => 'liturgy.label.is_solemnity_vfc'])
            ->add('isCelebration', null, ['label' => 'liturgy.label.is_celebration'])
            ->add('isCelebrationVFC', null, ['label' => 'liturgy.label.is_celebration_vfc'])
            ->add('isMemorial', null, ['label' => 'liturgy.label.is_memorial'])
            ->add('isMemorialVFC', null, ['label' => 'liturgy.label.is_memorial_vfc'])
            ->add('isMemorialFree', null, ['label' => 'liturgy.label.is_memorial_free'])
            ->end()
            ->with('Text',  ['label' => 'liturgy.title.text'])
            ->add('alleluiaReference', null, ['label' => 'liturgy.label.alleluia_reference'])
            ->add('alleluiaVerse', null, ['label' => 'liturgy.label.alleluia_verse'])

            ->end()
            ;
    }

    public function toString($object)
    {
        $title = "";
        if(!is_null($object->getDate()))
        {
            $title = $object->getDate()->format("Y-m-d");
            $title = 'do '.$title;
        }
        return $object instanceof Liturgy
            ? $object->getTitle()
            : 'Liturgia '.$title; // shown in the breadcrumb on the create view
    }
}
