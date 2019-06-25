<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * @brief      A Form used to request the liturgy texts to be generated in PDF/RTF.
 *
 */

class LiturgyTextRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add(
                  'text_format',
                  ChoiceType::class,
                  [
                      'label' => 'Format :',
                      'expanded' => false,
                      'multiple' => false,
                      'choices' => [
                          'pdf'=>'pdf',
                          'rtf'=> 'rtf'
                      ]
                  ]
              )
              ->add(
                  'liturgy_date',
                  DateType::class,
                  ['label' => 'Liturgy Date : ', 'format' => 'yyy-MM-dd', 'data' => new \DateTime()]
              )
              ->add('source', ChoiceType::class, [
                  'expanded' => false,
                  'multiple' => false,
                  'label' => 'Source :',
                  'choices' => [
                      'CNBB'=>'CNBB',
                      'Igreja Santa Ines'=> 'Igreja_Santa_Ines'
                  ]
              ])
              ->add('submit', SubmitType::class, ['label' => 'Get Text'])
              ;
    }
}
