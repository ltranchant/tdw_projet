<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['produits'] as  $p){
            $builder->add('produit_quantite_'.$p->getId(),ChoiceType::class, [
                'choices' => range(0,$p->getQuantite(),1),
                'empty_data' => 0,
            ]);
        }
//        $builder->add('produits',CollectionType::class,[
//            'entry_type' => ProduitType::class,
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'produits' => array(),
        ]);
    }
}
