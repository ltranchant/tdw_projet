<?php

namespace App\Entity;

use App\Repository\UtilisateurProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (
 *     name="im22_utilisateur_produit",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="up_idx",columns={"id_utilisateur","id_produit","quantite"})
 *      }
 * )
 *
 * @ORM\Entity(repositoryClass=UtilisateurProduitRepository::class)
 */
class UtilisateurProduit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Utilisateur
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="produits")
     * @ORM\JoinColumn(name="id_utilisateur", nullable=false)
     */
    private $utilisateur;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity=Produit::class)
     * @ORM\JoinColumn(name="id_produit", nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
