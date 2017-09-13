<?php

namespace Drupal\flag_ip;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Altering existing services, providing dynamic services.
 */
class FlagIpServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('flag');
    $definition->setClass('Drupal\flag_ip\FlagIpFlagService');
  }

}
