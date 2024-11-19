<?php

namespace App\Form;

use App\Entity\Galerie;
use App\Entity\Rack;
use App\Entity\Watch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class WatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('Name')
            ->add('rack', EntityType::class, [
                'class' => Rack::class,
                'choice_label' => 'id',
                'disabled' => true,
            ])
            ->add('galeries', EntityType::class, [
                'class' => Galerie::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
            ])
            ->add('imageName', TextType::class,  ['disabled' => true])
            ->add('imageFile', VichImageType::class, ['required' => false])
        ;
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Watch::class,
        ]);
    }
}
