<?php

namespace Drupal\tb_megamenu\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\tb_megamenu\MegaMenuConfigInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;

/**
 * Defines the Mega Menu Configuration entity.
 *
 * @ConfigEntityType(
 *   id = "tb_megamenu",
 *   label = @Translation("TB Mega Menu"),
 *   handlers = {
 *     "list_builder" = "Drupal\tb_megamenu\Controller\MegaMenuList",
 *     "form" = {
 *       "add" = "Drupal\tb_megamenu\Form\MegaMenuAdd",
 *       "edit" = "Drupal\tb_megamenu\Form\MegaMenuAdd",
 *       "delete" = "Drupal\tb_megamenu\Form\MegaMenuDelete",
 *     }
 *   },
 *   config_prefix = "menu_config",
 *   admin_permission = "administer tb_megamenu",
 *   entity_keys = {
 *     "id" = "id",
 *     "menu" = "menu",
 *   },
 *   config_export = {
 *     "id",
 *     "menu",
 *     "theme",
 *     "block_config",
 *     "menu_config",
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/tb-megamenu/{tb_megamenu}",
 *     "delete-form" = "/admin/structure/tb-megamenu/{tb_megamenu}/delete",
 *   }
 * )
 */
class MegaMenuConfig extends ConfigEntityBase implements MegaMenuConfigInterface {

  /**
   * The MegaMenu ID.
   *
   * @var string
   */
  public $id;

  /**
   * The related menu machine name.
   *
   * @var string
   */
  public $menu;

  /**
   * The related theme name.
   *
   * @var string
   */
  public $theme;

  /**
   * The json encoded string of block settings.
   *
   * @var string
   */
  public $block_config;

  /**
   * The json encoded string of menu settings.
   *
   * @var string
   */
  public $menu_config;

  /**
   * {@inheritdoc}
   */
  public static function create(array $values = []) {
    if (!isset($values['id']) && isset($values['menu']) && isset($values['theme'])) {
      $values['id'] = "{$values['menu']}__{$values['theme']}";
    }
    return parent::create($values);
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::setMenu()
   */
  public function setMenu($menuName) {
    if (!isset($this->id)) {
      $this->id = $menuName;
    }
    $this->menu = $menuName;
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::setTheme()
   */
  public function setTheme($themeName) {
    if (isset($this->id) && isset($this->menu) && $this->id == $this - menu) {
      $this->id = $this->id . '__' . $themeName;
    }
    $this->theme = $themeName;
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::getBlockConfig()
   */
  public function getBlockConfig() {
    if (is_array($this->block_config)) {
      $this->setBlockConfig($this->block_config);
      \Drupal::logger('tb_megamenu')->info("Converted block config array to json string.");
    }
    $config = isset($this->block_config) ? Json::decode($this->block_config) : [];

    // Ensure that the delay and duration values are only integers.
    if (isset($config['delay']) && !is_int($config['delay'])) {
      $config['delay'] = '';
    }
    if (isset($config['duration']) && !is_int($config['duration'])) {
      $config['duration'] = '';
    }

    if ($config === NULL) {
      \Drupal::logger('tb_megamenu')->warning("Could not decode block config json string for @id", ['@id' => $this->id]);
      $config = [];
    }
    return $config;
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::getMenuConfig()
   */
  public function getMenuConfig() {
    if (is_array($this->menu_config)) {
      $this->setMenuConfig($this->menu_config);
      \Drupal::logger('tb_megamenu')->info("Converted menu config array to json string.");
    }

    $config = isset($this->menu_config) ? Json::decode($this->menu_config) : [];

    // Iterate through config in order to santitize items that could be
    // vulnerable to XSS attacks.
    foreach ($config as $key => $value) {
      $config[$key]['submenu_config']['class'] = isset($value['submenu_config']['class']) ? Html::escape($value['submenu_config']['class']) : '';
      $config[$key]['item_config']['caption'] = isset($value['item_config']['caption']) ? Html::escape($value['item_config']['caption']) : '';
      $config[$key]['item_config']['class'] = isset($value['item_config']['class']) ? Html::escape($value['item_config']['class']) : '';
      $config[$key]['item_config']['xicon'] = isset($value['item_config']['xicon']) ? Html::escape($value['item_config']['xicon']) : '';
      $config[$key]['item_config']['label'] = isset($value['item_config']['label']) ? Html::escape($value['item_config']['label']) : '';
    }

    if ($config === NULL) {
      \Drupal::logger('tb_megamenu')->warning("Could not decode menu config json string for @id", ['@id' => $this->id]);
      $config = [];
    }

    return $config;
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::setBlockConfig()
   */
  public function setBlockConfig($blockConfig) {
    $this->block_config = Json::encode($blockConfig);
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::setMenuConfig()
   */
  public function setMenuConfig($menuConfig) {
    $this->menu_config = Json::encode($menuConfig);
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\tb_megamenu\MegaMenuConfigInterface::loadMenu()
   */
  public static function loadMenu($menu, $theme) {
    $id = "{$menu}__{$theme}";
    $config = self::load($id);
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    parent::calculateDependencies();
    $this->addDependency('menu', $this->menu);
    $this->addDependency('theme', $this->theme);
    return $this;
  }

}
