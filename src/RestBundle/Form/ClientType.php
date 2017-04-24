<?php

namespace RestBundle\Form;

use RestBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('token', TextType::class, [
                'label' => 'Token',
                'disabled' => true,
            ])
            ->add('resources', ChoiceType::class, [
                'label' => 'Available Resources',
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'multiple' => true,
                'expanded' => false,
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
    }
}
