<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserArticleViewRepository;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends AbstractController
{
    #[Route('/user/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));

            $user->setRoles(['ROLE_AUTHOR']);
            
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/user/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/user/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/user/{username}/delete', name: 'app_delete_user', methods: ['POST'])]
public function deleteUser(User $user, EntityManagerInterface $em): Response
{
    if ($this->getUser() !== $user) {
        throw new AccessDeniedException('You are not authorized to delete this user.');
    }

    $em->remove($user);
    $em->flush();

    // You might want to redirect to a different page after deletion
    return $this->redirectToRoute('app_home');
}


    #[Route('/user/{username}', name: 'app_profile')]
    public function index(User $user, UserArticleViewRepository $userArticleViewRepository): Response
    {
        if ($user !== $this->getUser()) {
            
        }

        $recentlyViewedArticles = $userArticleViewRepository->findRecentlyViewedArticles($user);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'recentlyViewedArticles' => $recentlyViewedArticles,
        ]);
}
}