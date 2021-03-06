<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 07/01/2017
 * Time: 00:53.
 */

namespace VinylStore\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class CreateNewReleaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('catno', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 3,
                        'max' => 15,
                    )),
                ),
                'attr' => array(
                    'placeholder' => 'eg 12 brw',
                ),
            ))
            ->add('artist', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                    )),
                ),
                'attr' => array(
                    'placeholder' => 'e.g. Tricky',
                ),
            ))
            ->add('title', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                    )),
                ),
                'attr' => array(
                    'placeholder' => 'eg.tricky kid',
                ),
            ))
            ->add('label', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'attr' => array(
                    'placeholder' => 'e.g. sony',
                ),
            ))
            ->add('format', ChoiceType::class, array(
                'choices' => array(
                    '12" Lp' => '12" Lp',
                    '12" Ep' => '12" Ep',
                    '12" Double Lp' => '12" Double Lp',
                    '12" Single' => '12" single',
                    '7" single' => '7" single',
                ),

            ))
            ->add('released_on', DateType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'widget' => 'single_text',
                'html5' => false,
                'input' => 'string',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'data-toggle' => 'datepicker'
                ),
            ))
            ->add('date_added', DateType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'widget' => 'single_text',
                'html5' => false,
                'input' => 'string',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'data-toggle' => 'datepicker',
                ),
            ))
            ->add('media_condition', ChoiceType::class, array(
                'choices' => array(
                    'new' => 'new',
                    'mint' => 'mint',
                    'ex' => 'ex',
                    'vg+' => 'vg+',
                    'vg' => 'vg',
                    'good' => 'good',
                    'fair' => 'fair',
                ), ))
            ->add('sleeve_condition', ChoiceType::class, array(
                'choices' => array(
                    'new' => 'new',
                    'mint' => 'mint',
                    'ex' => 'ex',
                    'vg+' => 'vg+',
                    'vg' => 'vg',
                    'good' => 'good',
                    'fair' => 'fair',
                ), ))
            ->add('notes', TextareaType::class, array(
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'attr' => array(
                    'class' => 'materialize-textarea',
                    'placeholder' => 'e.g. great album etc',
                ),
            ))
            ->add('genre', ChoiceType::class, array(
                'choices' => array(
                    'rock' => 'rock',
                    'classic rock' => 'classic-rock',
                    'electronic' => 'electronic',
                ),
            ))
            ->add('quantity', ChoiceType::class, array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2
                )
            ))
            ->add('barcode', TextType::class, array(
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ));
    }
}
