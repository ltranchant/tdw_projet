<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\UtilisateurProduit;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/panier", name="panier")
 * @IsGranted("ROLE_CLIENT")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/list", name="_list"),
     */
    public function listPanierAction(ManagerRegistry $doctrine,Security  $security): Response
    {
        $er = $doctrine->getRepository(UtilisateurProduit::class);
        $user = $security->getUser();

        $userProduit = $er->findBy(['utilisateur' => $user]);

        return $this->render('Panier/listPanierView.html.twig',[
            'userProduit' => $userProduit,
        ]);
    }

    /**
     * @Route(
     *     "/supp/{id}",
     *     name="_supp",
     *     requirements = { "id" : "0|[1-9]\d*" },
     * ),
     */
    public function suppPanierAction(int $id,ManagerRegistry $doctrine): Response
    {

        $er = $doctrine->getRepository(UtilisateurProduit::class);
        $userProduit = $er->find($id);

        if($userProduit !== null){
            $em = $doctrine->getManager();
            $em->remove($userProduit);
            $em->flush();
        }

        return $this->redirectToRoute('panier_list');
    }

    /**
     * @Route("/acheter", name="_acheter"),
     */
    public function acheterPanierAction(ManagerRegistry $doctrine): Response
    {
        $er = $doctrine->getRepository(UtilisateurProduit::class);
        $em = $doctrine->getManager();
        $userProduits = $er->findAll();

        foreach ($userProduits as $p){
            $em->remove($p);
        }

        $em->flush();

        return $this->redirectToRoute('panier_list');
    }

    /**
     * @Route("/vider", name="_vider"),
     */
    public function viderPanierAction(ManagerRegistry $doctrine): Response
    {
        $er = $doctrine->getRepository(UtilisateurProduit::class);
        $em = $doctrine->getManager();
        $userProduits = $er->findAll();

        foreach ($userProduits as $p){
            $produit = $p->getProduit();
            $produit->setQuantite($produit->getQuantite()+$p->getQuantite());
            $em->remove($p);
            $em->persist($produit);
        }

        $em->flush();

        return $this->redirectToRoute('panier_list');
    }
}
