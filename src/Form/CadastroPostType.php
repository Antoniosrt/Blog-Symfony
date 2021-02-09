<?php

namespace App\Form;

use App\Entity\Categorias;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CadastroPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Coloque o titulo aqui',
                    'class'=>'form-control col-sm-12 ',

                ]
                ]
            )
            ->add('Descricao',TextareaType::class,[
                'attr'=>[
                    'placeholder'=>'Descrição',
                    'class'=>'form-control col-sm-12'
                ]
            ])
            ->add('cat',EntityType::class,[
                'attr'=>[
                    'class'=>'form-control mb-3'
                ],
                'class'=>'App\Entity\Categorias'

            ])
            ->add('Imagem',FileType::class, array('data_class' => null,'required' => false)
            )

            ->add('Enviar',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-success mt-3 '
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
