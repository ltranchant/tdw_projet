<?php

namespace ContainerGTyAiZn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getListProduitTypeService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'App\Form\ListProduitType' shared autowired service.
     *
     * @return \App\Form\ListProduitType
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/form/FormTypeInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/form/AbstractType.php';
        include_once \dirname(__DIR__, 4).'/src/Form/ListProduitType.php';

        return $container->privates['App\\Form\\ListProduitType'] = new \App\Form\ListProduitType();
    }
}
