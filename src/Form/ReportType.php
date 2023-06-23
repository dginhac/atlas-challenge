<?php

namespace App\Form;

use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reportFile', VichFileType::class, [
                'label' => 'Technical report (pdf)',
                'required' => false,
                'invalid_message' => 'This file is not valid.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Report::class,
        ]);
    }
}