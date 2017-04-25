<?php

namespace RestBundle\Form;

use RestBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('token', TextareaType::class, [
                'label' => 'Token',
                'attr' => [
                    'readonly' => 'readonly',
                    'class' => 'js-token-input',
                ]
            ])
            ->add('resources', ChoiceType::class, [
                'label' => 'Available Resources',
                'multiple' => true,
                'expanded' => false,
                'choices' => [
                    'test' => 'test',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'multiple' => true,
                'expanded' => false,
                'choices' => array_combine($options['roles'], $options['roles']),
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active ?',
                'attr' => [
                    'class' => 'js-switch',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);

        $resolver->setRequired('roles');
        $resolver->setAllowedTypes('roles', ['array']);
    }
}
