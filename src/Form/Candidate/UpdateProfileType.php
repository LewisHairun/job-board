<?php

namespace App\Form\Candidate;

use App\Entity\Candidate;
use App\Entity\PositionType;
use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "L'email est obligatoire"
                    ])
                ] 
            ])
            ->add('lastname', TextType::class, [
                "label" => "Nom de famille"
            ])
            ->add('firstname', TextType::class, [
                "label" => "Prénom"
            ])
            ->add('degree', ChoiceType::class, [
                "label" => "Diplôme",
                "label_attr" => [
                    "class" => "align-self-start"
                ],
                "choices" => [
                    "DTS" => "DTS",
                    "Licence" => "Licence",
                    "Master" => "Master",
                ],
                "attr" => [
                    "class" => "select-two",
                    "data-placeholder" => "Selectionner votre diplôme"
                ]
            ])
            ->add('skill', EntityType::class, [
                "label" => "Compétence(s)",
                "class" => Skill::class,
                "choice_label" => "name",
                "query_builder" => function(EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                "attr" => [
                    "class" => "select-two",
                    "data-placeholder" => "Selectionner votre compétence"
                ],
                "multiple" => true,
                "by_reference" => false
            ])
            ->add('positionType', EntityType::class, [
                'label' => 'Type de poste',
                'class' => PositionType::class,
                'choice_label' => 'type',
                "label_attr" => [
                    "class" => "align-self-start"
                ],
                'attr' => [
                    'class' => 'select-two',
                    'data-placeholder' => "Selectionner un type de poste"
                ]
            ])
            ->add('cvAttachment', FileType::class, [
                'required' => false,
                'label' => 'Uploader un CV (pdf, jpeg)',
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
