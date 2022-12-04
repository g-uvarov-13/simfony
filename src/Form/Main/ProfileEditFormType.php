<?php

namespace App\Form\Main;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class,[
                'label' => 'Введите ваше полное имя',
                ])
            ->add('phone',TextType::class,[
                'label' => 'Введите номер телефона',
            ])
            ->add('address',TextType::class,[
                'label' => 'Введите ваш адрес',
            ])
            ->add('zipcode',IntegerType::class,[
                'label' => 'Введите ваш zipcode',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
