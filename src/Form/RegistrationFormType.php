<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'attr' => [
                    'placeholder' => 'Firstname',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a firstname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your first name should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'attr' => [
                    'placeholder' => 'Lastname',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a lastname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your last name should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            */
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'Be sure to check the validity of your email address because you will need it to connect later. ',
                'invalid_message' => 'This email is not valid.',
            ])
            /*
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            */
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,

                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])\S{8,}$/',
                        'message' => 'Your password must contain at least 1 number, 1 capital letter, and more than 8 characters.',
                    ])
                ],
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'label' => 'Password',
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                    'help' => 'Your password must contain at least 1 number, 1 capital letter, and more than 8 characters.',
                    'invalid_message' => 'The password is not valid.',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                    'invalid_message' => 'The password is not valid.',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}