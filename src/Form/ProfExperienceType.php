<?php

namespace App\Form;

use App\Entity\ProfExperience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre"
            ])
            ->add('description', TextareaType::class)
            ->add('company', TextType::class, [
                "label" => "Entreprise",
                "required" => false
            ])
            ->add('startDate', DateType::class, [
                "label" => "Date de debut",
                "widget" => "single_text"
            ])
            ->add('endDate', DateType::class, [
                "label" => "Date de fin",
                "widget" => "single_text"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfExperience::class,
        ]);
    }
}
