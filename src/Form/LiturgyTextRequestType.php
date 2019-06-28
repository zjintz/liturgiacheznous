<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @brief      A Form used to request the liturgy texts to be generated in PDF/RTF.
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
                          'pdf'=>'pdf',
                          'rtf'=> 'rtf'
                      ]
                  ]
              )
              ->add(
                  'liturgy_date',
                  DateType::class,
                  ['label' => $this->translator->trans('form.label.liturgy_date'),
                   'format' => 'yyy-MM-dd',
                   'data' => new \DateTime()
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
                  ]
              ])
              ->add(
                  'submit',
                  SubmitType::class,
                  ['label' => $this->translator->trans('form.button.get_text')]
              );
    }
}
