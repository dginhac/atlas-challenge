<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the administrator
            $this->emailVerifier->sendEmailConfirmation('app_verify', $user,
                (new TemplatedEmail())
                    ->from(new Address('dginhac@u-bourgogne.fr', 'Atlas Challenge'))
                    ->to('atlas-challenge-l@u-bourgogne.fr')
                    ->subject('Atlas Challenge: Please confirm the new account.')
                    ->textTemplate('emails/admin/confirm-account.txt.twig')
                    ->htmlTemplate('emails/admin/confirm-account.html.twig')
                    ->context(['user' => $user])
            );

            // Send an email to the user
            $email = (new TemplatedEmail())
                ->from(new Address('dginhac@u-bourgogne.fr', 'Atlas Challenge'))
                ->to($user->getEmail())
                ->subject('Atlas Challenge: Your account is awaiting validation.')
                ->textTemplate('emails/account-is-waiting-for-validation.txt.twig')
                ->htmlTemplate('emails/account-is-waiting-for-validation.html.twig')
                ->context(['user' => $user]);
            $mailer->send($email);

            $this->addFlash(
                'success',
                'Your account ' . $user->getEmail() . ' has been created. As soon as it is validated by 
                one of the challenge organizers, you will receive an email indicating that you can login.'
            );
            //return $this->redirectToRoute('app_under_construction');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify', name: 'app_verify')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $id = $request->get('id');
        if (null === $id) {
            return $this->render('errors/registration.html.twig', [
                'error_msg' => 'No user id is provided. Unable to validate the account.',
            ]);
        }

        $user = $userRepository->find($id);
        if (null === $user) {
            return $this->render('errors/registration.html.twig', [
                'error_msg' => 'The account does not exist. Unable to validate it.',
            ]);
        }

        if ($user->isVerified()) {
            return $this->render('errors/registration.html.twig', [
                'error_msg' => 'The account is already validated.',
            ]);
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->render('errors/registration.html.twig', [
                'error_msg' => $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'),
            ]);
        }
        // Send an email to the user
        $email = (new TemplatedEmail())
            ->from(new Address('dginhac@u-bourgogne.fr', 'Atlas Challenge'))
            ->to($user->getEmail())
            ->addCc('atlas-challenge-l@u-bourgogne.fr')
            ->subject('Atlas Challenge: Your account is validated.')
            ->textTemplate('emails/account-is-validated.txt.twig')
            ->htmlTemplate('emails/account-is-validated.html.twig')
            ->context(['user' => $user]);
        $mailer->send($email);

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->render('registration/verified.html.twig', [
            'user' => $user,
        ]);
    }
}