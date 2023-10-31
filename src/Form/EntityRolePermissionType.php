<?php

namespace App\Form;

use App\Entity\EntityRolePermission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityRolePermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('canAdd', CheckboxType::class, [
                "required" => false
            ])
            ->add('canEdit')
            ->add('canView')
            ->add('canDelete')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntityRolePermission::class,
        ]);
    }
}
