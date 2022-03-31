<?php

namespace ContainerGTyAiZn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getMaker_GeneratorService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'maker.generator' shared service.
     *
     * @return \Symfony\Bundle\MakerBundle\Generator
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/maker-bundle/src/Generator.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/maker-bundle/src/Util/TemplateComponentGenerator.php';

        $a = ($container->privates['maker.php_compat_util'] ?? $container->load('getMaker_PhpCompatUtilService'));

        return $container->privates['maker.generator'] = new \Symfony\Bundle\MakerBundle\Generator(($container->privates['maker.file_manager'] ?? $container->load('getMaker_FileManagerService')), 'App', $a, new \Symfony\Bundle\MakerBundle\Util\TemplateComponentGenerator($a));
    }
}
