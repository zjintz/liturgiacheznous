<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @brief      A Form used to request the liturgy texts to be generated in PDF/docx.
 *
 */

class LiturgyTextRequestType extends AbstractType
{
    private $translator; 
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add(
                  'text_format',
                  ChoiceType::class,
                  [
                      'label' => $this->translator->trans(
                          'form.label.text_format'
                      ),
                      'expanded' => false,
                      'multiple' => false,
                      'choices' => [
                          'docx (MS Word)'=> 'DOCX',
                          'PDF'=>'PDF'        
                      ],
                      'attr' => [
                          'data-sonata-select2' => 'false'
                      ]
                  ]
              )
              ->add(
                  'liturgy_date',
                  DatePickerType::class,
                  ['label' => $this->translator->trans('form.label.liturgy_date'),
                   'format' => 'yyyy-MM-dd',
                   'data' => new \DateTime(),
                   'attr' => ['data-sonata-select2' => 'false']
                  ]
              )
              ->add('source', ChoiceType::class, [
                  'expanded' => false,
                  'multiple' => false,
                  'label' => $this->translator->trans(
                      'form.label.source'
                  ),
                  'choices' => [
                      'CNBB'=>'CNBB',
                      'Igreja Santa Ines'=> 'Igreja_Santa_Ines'
                  ],
                  'attr' => ['data-sonata-select2' => 'false']
              ])
              ->add(
                  'submit',
                  SubmitType::class,
                  ['label' => $this->translator->trans('form.button.get_text')]
              );
    }
}
