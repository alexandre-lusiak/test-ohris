<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\City;
use App\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker' , 'min' => (new \DateTime())->format('Y-m-d')],
                "label" => false
            ])
            ->add('artist',EntityType::class , [
                'class' => Artist::class,
                'choice_label' => 'name',
                "placeholder" => 'Choissiez un Artiste',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('a')
                        ->orderby('a.name','ASC');
                
                },
                'label' => false
            ])
            ->add('city',EntityType::class , [
                'class' => City::class,
                'choice_label' => 'name',
                "placeholder" => 'Choisissez une Ville',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('a')
                        ->orderby('a.name','ASC');
                
                },
                'label' => false
            ]);
         
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
