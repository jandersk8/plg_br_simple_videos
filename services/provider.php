<?php
/**
 * @package     BR Simple Videos
 * @author      Janderson Moreira
 * @copyright   Copyright (C) 2026 Janderson Moreira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use BrSimple\Plugin\Content\Videos\Extension\BrSimpleVideos;

/**
 * Service provider for the BrSimpleVideos plugin
 */
return new class implements ServiceProviderInterface {
    /**
     * Registers the service provider with a container.
     *
     * @param   Container  $container  The container.
     * @return  void
     */
    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                // Carrega as traduções do plugin
                $dispatcher = $container->get(\Joomla\Event\DispatcherInterface::class);
                $plugin     = PluginHelper::getPlugin('content', 'br_simple_videos');

                // Instancia a classe principal do plugin
                $extension = new BrSimpleVideos(
                    $dispatcher,
                    (array) $plugin
                );

                // Injeta a aplicação no plugin (útil para pegar caminhos do site)
                $extension->setApplication(Factory::getApplication());

                return $extension;
            }
        );
    }
};