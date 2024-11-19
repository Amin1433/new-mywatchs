<?php

namespace App\Form;

use App\Entity\Galerie;
use App\Entity\Member;
use App\Entity\Watch;
use App\Repository\WatchRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        //dump($options);
        // Get the current [object] from 'data' option passed to the form
        $galerie = $options['data'] ?? null;
        // get the [galerie]'s creator
        $member = $galerie->getCreator();
        
        $builder
            ->add('name')
            ->add('description')
            ->add('publiee')
            ->add('creator', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id',
                'disabled' => true,
            ])
            ->add('watchs', EntityType::class, [
                'class' => Watch::class,
                'choice_label' => 'name', // Attribut affiché
                'query_builder' => function (WatchRepository $er) use ($member) {
                return $er->createQueryBuilder('o')
                ->leftJoin('o.rack', 'i')
                ->leftJoin('i.member', 'm')
                ->andWhere('m.id = :memberId')
                ->setParameter('memberId', $member->getId());
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Galerie::class,
        ]);
    }
}
