<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ConnexionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/utilisateur", name="utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/connexion", name="_connexion")
     */
    public function connexionAction(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(ConnexionType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $er = $doctrine->getRepository(Utilisateur::class);
            $user = $er->findOneBy([
                'username' => $user->getUsername(),
                'password' => $user->getPassword()
            ]);

            if($user == null){
                return $this->redirectToRoute('utilisateur_connexion');
            }


            return $this->redirectToRoute('utilisateur_accueil');
        }
        return $this->renderForm("utilisateur/connexion.html.twig",[
            'form' => $form,
        ]);
    }

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
}
