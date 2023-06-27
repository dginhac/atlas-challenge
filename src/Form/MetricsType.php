<?php

namespace App\Form;

use App\Entity\Metrics;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MetricsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('metricsFile', VichFileType::class, [
                'label' => 'Metrics file (csv)',
                'required' => true,
                'invalid_message' => 'This file is not valid.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Metrics::class,
        ]);
    }
}
