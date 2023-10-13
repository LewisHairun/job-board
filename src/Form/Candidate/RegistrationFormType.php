<?php

namespace App\Form\Candidate;

use App\Entity\Candidate;
use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('lastname', TextType::class, [
                "label" => "Nom dd famille"
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
            ->add('skills', EntityType::class, [
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte les conditions",
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit être au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
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
