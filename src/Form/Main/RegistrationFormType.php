<?php

namespace App\Form\Main;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => 'Введите ваш email',
                'required' =>true,
                'attr' =>[
                    'class' =>'form-control',
                    'autofocus' => 'autofocus',
                    'placeholder' => 'email'
                ],
                'constraints' =>[
                    new NotBlank([
                        'message' =>'Заполните поле'
                    ]),
                    new  Email([
                        'message' =>'Please enter a valid email address'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Я согласен с <a href="#"> Правилами </a>*',
                'required' =>true,
                'label_html' => true,
                'mapped' => false,
                'attr' =>[
                  'class'=>'custom-control-input'
                ],
                'label_attr' =>[
                  'class'=>'custom-control-label'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
              'label' => 'Введите пароль',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' =>'form-control',
                    'autocomplete' => 'new-password',
                    'placeholder' => 'password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста введите пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
