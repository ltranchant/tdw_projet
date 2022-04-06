<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\UtilisateurProduit;
use App\Form\ListProduitType;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/produit", name="produit")
 */

class ProduitController extends AbstractController
{
    /**
     * @Route("/list", name="_list"),
     * @IsGranted("ROLE_CLIENT")
     */
    public function listAction(Request $request,ManagerRegistry $doctrine,Security $security): Response
    {
        $er = $doctrine->getRepository(Produit::class);
        $produits = $er->findAll();

        $form = $this->createForm(ListProduitType::class,null,[
            'produits' => $produits,
        ]);
        $form->add('Commander',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $erUserProduit = $doctrine->getRepository(UtilisateurProduit::class);
            $user = $security->getUser();
            foreach ($form->getData() as $name=>$data){
                if($data > 0 ) {
                    $id = substr($name, 17);
                    $p = $er->find($id);

                    $userProduit = $erUserProduit->findOneBy([
                        'produit' => $p,
                        'utilisateur' => $user,
                    ]);

                    if ($userProduit === null) {
                        $userProduit = new UtilisateurProduit();
                        $userProduit->setProduit($p)
                            ->setUtilisateur($user)
                            ->setQuantite($data);
                    } else {
                        $userProduit->setQuantite($userProduit->getQuantite() + $data);
                    }

                    $p->setQuantite($p->getQuantite() - $data);
                    $em->persist($userProduit);
                    $em->persist($p);
                }
            }
            $em->flush();
            return $this->redirectToRoute('produit_list');
        }
        return $this->renderForm("Produit/listProduitView.html.twig",[
            'form' => $form,
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addProduitAction(Request $request,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class,$produit);

        $form->add('submit',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('utilisateur_accueil');
        }
        return $this->renderForm("Produit/addProduitView.html.twig",[
            'form' => $form,
        ]);
    }

}
