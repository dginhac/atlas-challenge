<?php

namespace App\Form;

use App\Entity\Submission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SubmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('zipFile', VichFileType::class, [
                'label' => 'Docker Archive (zip)',
                'required' => $options['required_file'],
                'invalid_message' => 'This file is not valid.',
            ])
            ->add('reportFile', VichFileType::class, [
                'label' => 'Technical report (pdf)',
                'required' => $options['required_file'],
                'invalid_message' => 'This file is not valid.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Submission::class,
            'required_file' => true
        ]);
        $resolver->setAllowedTypes('required_file', 'bool');
    }
}