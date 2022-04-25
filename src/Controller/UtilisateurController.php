<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurProduit;
use App\Form\UtilisateurType;
use App\Service\ReverseString;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function accueilAction(ReverseString $reverseString): Response
    {
        $reversed = $reverseString->reverse("Bonjour bienvenue sur la page d'accueil du site");
        dump($reversed);
        return $this->render('Utilisateur/accueilView.html.twig',[
            'reversed' => $reversed,
        ]);
    }

    /**
     * @Route("/menu", name="_menu")
     */
    public function menuAction(ManagerRegistry $doctrine,Security $security): Response
    {
        $er = $doctrine->getRepository(UtilisateurProduit::class);
        $user = $security->getUser();
        $nbArticle = $er->countNbArticlesPanier($user);

        $liens = "";
        if($this->isGranted('ROLE_CLIENT')){
            $liens = array(
                'Se déconnecter' => 'utilisateur_logout',
                'Aller à l\'accueil' => 'utilisateur_accueil',
                'Modifier votre profil' => 'utilisateur_modif_profil',
                'Lister les articles' => 'produit_list',
                'Gérer votre panier' => 'panier_list',
            );
        }
        if($this->isGranted('ROLE_ADMIN')){
            $liens = $liens + array(
                'Gérer les utilisateurs' => 'utilisateur_list',
                'Ajouter un produit' => 'produit_add',
            );
        }
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            $liens = array(
                'Aller à l\'accueil' => 'utilisateur_accueil',
                'Modifier votre profil' => 'utilisateur_modif_profil',
                'Se déconnecter' => 'utilisateur_logout',
                'Créer un administrateur' => 'utilisateur_add_admin',
            );
        }
        if($user === null){
            $liens = array(
                'Se connecter' => 'utilisateur_login',
                'Créer un compte' => 'utilisateur_inscription',
                'Aller à l\'accueil' => 'utilisateur_accueil',
            );
        }



        return $this->render('Squelette/menu.html.twig',[
            'nbArticle' => $nbArticle,
            'liens' => $liens,
        ]);
    }

    /**
     * @Route("/list", name="_list"),
     * @IsGranted("ROLE_ADMIN")
     */
    public function listUtilisateurAction(ManagerRegistry $doctrine): Response
    {
        $er = $doctrine->getRepository(Utilisateur::class);
        $users = $er->findAll();

        return $this->render('Utilisateur/listUtilisateurView.html.twig',[
            'users' => $users,
        ]);
    }

    /**
     * @Route(
     *     "/delete/{id}",
     *     name="_delete",
     *     requirements = { "id" : "0|[1-9]\d*" },
     * ),
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUtilisateurAction(int $id,ManagerRegistry $doctrine,Security $security): Response
    {
        $er = $doctrine->getRepository(Utilisateur::class);
        $user = $er->find($id);

        if($user === null || $user === $security->getUser() || $user->is_granted('ROLE_SUPER_ADMIN')){
            return $this->redirectToRoute('utilisateur_list');
        }

        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('utilisateur_list');
    }

    /**
     * @Route(
     *     "/add/admin",
     *     name="_add_admin",
     * ),
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function addAdminAction(Request  $request,UserPasswordHasherInterface $passwordHasher,ManagerRegistry $doctrine): Response
    {
        $user = new Utilisateur();
        $em = $doctrine->getManager();

        $form = $this->createForm(UtilisateurType::class, $user);
        $form->add('submit',SubmitType::class,["label" => "Ajouter"]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_ADMIN']);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('utilisateur_accueil');
        }

        return $this->render('Utilisateur/inscriptionView.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajouter un administrateur',
        ]);
    }


    /**
     * @Route("/addRoleTest", name="_addRole")
     */
    public function addRoleAction(ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();

        $user = new Utilisateur();
        $user->setUsername('simon')
            ->setNom('Simon')
            ->setPrenom('Simon')
            ->setRoles(['ROLE_CLIENT'])
            ->setDateNaissance(\DateTime::createFromFormat('d/m/Y h:s', date('d/m/Y h:s')));
        $hashedPassword = $passwordHasher->hashPassword($user,'nomis');
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

        return $this->render('Utilisateur/loginView.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/modifProfil", name="_modif_profil")
     */
    public function modifProfilUpdate(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,Security $security): Response
    {
        $user = $security->getUser();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->add('submit',SubmitType::class,["label" => "Sauvegarder"]);
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
            // do anything else you need here, like send an email

            return $this->redirectToRoute('utilisateur_accueil');
        }

        return $this->render('Utilisateur/inscriptionView.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier le profil',
        ]);
    }

    /**
     * @Route("/inscription", name="_inscription")
     */
    public function inscriptionAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->add('submit',SubmitType::class,["label" => "S'inscrire"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_CLIENT']);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('utilisateur_accueil');
        }

        return $this->render('Utilisateur/inscriptionView.html.twig', [
            'form' => $form->createView(),
            'title' => 'Inscription',
        ]);
    }
}
