<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('doctors',  EntityType::class, array(
        'class' => 'App\Entity\Doctor',
        'choice_label' => 'doctorFirstName',
        'placeholder' => 'Select a doctor',
        'required' => true,
     ))

        ->add('firstName', TextType::class, [
            'label' => 'First Name',
            'label_attr' => [
                'class' => 'col-sm-2 control-label',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your name',
                ]),
                new Length([
                    'min' => 1,
                    'minMessage' => 'Name is too short',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                    'maxMessage' => 'Name is too long',
                ])
            ]
        ])

        ->add('selectedTime', DateTimeType::class, [
            'placeholder' => [
                'year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute'
            ]

        ])

        ->add('search', SubmitType::class, [
            'label' => 'Register',

        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
