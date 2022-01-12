<?php

namespace Drupal\tb_megamenu\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\system\Entity\Menu;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides blocks which belong to TB Mega Menu.
 */
class TBMegaMenuBlock extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Entity type manager.
   *
   * @var \Drupal\core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * TBMegaMenuBlock constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   Config factory interface.
   */
  public function __construct(ConfigFactoryInterface $configFactory, EntityTypeManagerInterface $entityTypeManager) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $menus = $this->entityTypeManager->getStorage('menu')->loadMultiple();
    asort($menus);
    foreach ($this->configFactory->listAll('tb_megamenu.menu_config.') as $index_id) {
      $info = $this->configFactory->get($index_id);
      $menu = $info->get('menu');
      if (isset($menus[$menu])) {
        $this->derivatives[$menu] = $base_plugin_definition;
        $this->derivatives[$menu]['admin_label'] = $menus[$menu]->label();
      }
    }
    return $this->derivatives;
  }

}
