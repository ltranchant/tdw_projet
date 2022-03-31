<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ListProduitType;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit", name="produit")
 */

class ProduitController extends AbstractController
{
    /**
     * @Route("/list", name="_list")
     */
    public function listAction(Request $request,ManagerRegistry $doctrine): Response
    {
        $er = $doctrine->getRepository(Produit::class);
        $produits = $er->findAll();

        $form = $this->createForm(ListProduitType::class,null,[
            'produits' => $produits,
        ]);
        $form->add('Commander',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('produit_list');
        }
        return $this->renderForm("produit/listView.html.twig",[
            'form' => $form,
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/add", name="_add")
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
        return $this->renderForm("produit/addView.html.twig",[
            'form' => $form,
        ]);
    }

}
