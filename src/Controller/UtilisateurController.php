<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ConnexionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/utilisateur", name="utilisateur")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @Route("/accueil", name="_accueil")
     */
    public function accueilAction(Request $request, ManagerRegistry $doctrine,Security $security): Response
    {
        $user = $security->getUser();
        if($user !== null){
            $r = $user->getRoles();
        }else{
            $r = 'Anonyme';
        }
        return $this->render('utilisateur/accueil.html.twig',[
            'roles' => $r,
        ]);
    }

    /**
     * @Route("/addRoleTest", name="_addRole")
     */
    public function addRoleAction(ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();

        $user = new Utilisateur();
        $user->setUsername('leo')
            ->setNom('tranchant')
            ->setPrenom('leo')
            ->setRoles(['ROLE_CLIENT','ROLE_ADMIN'])
            ->setDateNaissance(\DateTime::createFromFormat('d/m/Y h:s', date('d/m/Y h:s')));
        $hashedPassword = $passwordHasher->hashPassword($user,'aaa');
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('utilisateur_login');
    }

    /**
     * @Route("/login", name="_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('utilisateur_accueil');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
