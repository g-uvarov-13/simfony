<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Model\EditProductModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Title (from class)',
                'required' => true,
                'attr' =>[
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([],'NADO ZAPOLNIT')
                ]

            ])
            ->add('price', NumberType::class,[
                'label' => 'Price (from class)',
                'scale' => 2,
                'html5'=>true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 0.01
                ]
            ])
            ->add('quantity', IntegerType::class,[
                'label' => 'Quantity',
                'required' =>true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            //->add('createdAt')
            ->add('description',TextareaType::class,[
                'label' => 'Description',
                'required' =>true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'overflow: hidden'
                ]
            ])
            ->add('category',EntityType::class,[
                'label' => 'Category',
                'required' =>true,
                'class' => Category::class,
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('newImage', FileType::class,[
                'label' => 'Choose New Image',
                //'required' => true,
                'attr' =>[
                    'class' => 'form-control-file'
                ]
            ])
            ->add('isPublished', CheckboxType::class,[
                'label' => 'Is Published',
                'required' =>false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' =>[
                    'class' =>'form-check-label'
                ]
            ])
            ->add('isDeleted', CheckboxType::class,[
                'label' => 'Is Deleted',
                'required' =>false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' =>[
                    'class' =>'form-check-label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditProductModel::class,
        ]);
    }
}
