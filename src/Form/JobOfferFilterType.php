<?php

namespace App\Form;

use App\Entity\JobBranch;
use App\Filter\JobOfferFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobOfferFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobBranch', EntityType::class, [
                "class" => JobBranch::class,
                "choice_label" => "name",
                "label" => false,
                "required" => false,
                "attr" => [
                    "class" => "select-two-branch",
                ]
            ])
            ->add('minSalary', TextType::class, [
                "required" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Entrer un salaire minimum"
                ]
            ])
            ->add('maxSalary', TextType::class, [
                "required" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Entrer un salaire maximum"
                ]
            ])
            ->add('orderingCity', ChoiceType::class, [
                "required" => false,
                "label" => false,
                "choices" => ["desc" => "desc", "asc" => "asc"],
                "attr" => [
                    "class" => "select-two-ordering-city", 
                ]
            ])
            ->add('orderingJobOffer', ChoiceType::class, [
                "required" => false,
                "label" => false,
                "choices" => ["desc" => "desc", "asc" => "asc"],
                "attr" => [
                    "class" => "select-two-ordering-job-offer",
                ]
            ])
            ->add('filter', SubmitType::class, [
                'label' => "Filtrer",
                'attr' => [
                    'class' => "btn btn-md btn-secondary"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOfferFilter::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }
}
