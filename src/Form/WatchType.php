<?php

namespace App\Form;

use App\Entity\Galerie;
use App\Entity\Rack;
use App\Entity\Watch;
use Doctrine\ORM\EntityRepository;
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
        // Récupération du membre via les options
        $member = $options['member'];
        
        $builder
        ->add('Name')
        ->add('description')
        ->add('rack', EntityType::class, [
            'class' => Rack::class,
            'choice_label' => 'id',
            'disabled' => true,
        ])
        ->add('galeries', EntityType::class, [
            'class' => Galerie::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
            'query_builder' => function (EntityRepository $er) use ($member) {
            return $er->createQueryBuilder('g')
            ->join('g.creator', 'm')
            ->where('m.id = :memberId')
            ->setParameter('memberId', $member->getId());
            },
            ])
            ->add('imageName', TextType::class, ['disabled' => true])
            ->add('imageFile', VichImageType::class, ['required' => false]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Watch::class,
            'member' => null, // Ajout de l'option personnalisée
        ]);
    }
}
