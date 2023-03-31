<?php
namespace App\Form;

use App\Entity\Artist;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\SearchFilter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'du',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker' , 'min' => (new \DateTime())->format('Y-m-d')],
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'au',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker' , 'min' => (new \DateTime())->format('Y-m-d')],
                'required' => false,
            ])
            ->add('city',EntityType::class , [
                'class' => City::class,
                'choice_label' => 'name',
                "placeholder" => 'Choisissez une Ville',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('a')
                        ->orderby('a.name','ASC');
                
                },
                'required' => false,
                'label' => false
            ])
            ->add('artist',EntityType::class , [
                'class' => Artist::class,
                'choice_label' => 'name',
                "placeholder" => 'Choissiez un Artiste',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('a')
                        ->orderby('a.name','ASC');
                },
                'required' => false,
                'label' => false
               
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchFilter::class,
        ]);
    }
}

