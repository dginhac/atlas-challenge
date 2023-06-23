<?php

namespace App\Form;

use App\Entity\Docker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DockerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dockerFile', VichFileType::class, [
                'label' => 'Docker Archive (zip)',
                'required' => true,
                'invalid_message' => 'This file is not valid.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Docker::class,
        ]);
    }
}