<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Categoria;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class PostagemTypeEdit extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add(child:'titulo')
            ->add(child:'author')
            ->add(child:'slug')
            ->add('categoriaId', EntityType::class, [
                'class' => Categoria::class,
                'choice_label' => 'name'
            ])
            ->add(child:'descricao')
            ->add(child:'conteudo')
            ->add(child:'tag')
            ->add('save', SubmitType::class, ['label' => 'Editar Postagem'])
        ;
    }


}