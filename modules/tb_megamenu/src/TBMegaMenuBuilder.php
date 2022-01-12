<?php

namespace Drupal\tb_megamenu;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeStorageInterface;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\tb_megamenu\Entity\MegaMenuConfig;

/**
 * Defines a TBMegaMenuBuilder.
 */
class TBMegaMenuBuilder implements TBMegaMenuBuilderInterface {

  use StringTranslationTrait;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  private $logger;

  /**
   * The menu link service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  private $menuTree;

  /**
   * The entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * The path matcher service.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  private $pathMatcher;

  /**
   * The menu tree storage service.
   *
   * @var \Drupal\Core\Menu\MenuTreeStorageInterface
   */
  private $menuStorage;

  /**
   * Constructs a TBMegaMenuBuilder.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree
   *   The menu link service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   The path matcher service.
   * @param \Drupal\Core\Menu\MenuTreeStorageInterface $menu_storage
   *   The menu tree storage service.
   */
  public function __construct(LoggerChannelFactoryInterface $logger_factory, MenuLinkTreeInterface $menu_tree, EntityTypeManagerInterface $entity_manager, PathMatcherInterface $path_matcher, MenuTreeStorageInterface $menu_storage) {
    $this->logger = $logger_factory->get('tb_megamenu');
    $this->menuTree = $menu_tree;
    $this->entityTypeManager = $entity_manager;
    $this->pathMatcher = $path_matcher;
    $this->menuStorage = $menu_storage;
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockConfig(string $menu_name, string $theme) {
    $menu = self::getMenus($menu_name, $theme);
    return ($menu) ? $menu->getBlockConfig() : [];
  }

  /**
   * {@inheritdoc}
   */
  public function getMenus(string $menu_name, string $theme) {
    $config = MegaMenuConfig::loadMenu($menu_name, $theme);
    if ($config === NULL) {
      $this->logger->warning("Could not find TB Megamenu configuration for menu: @menu, theme: @theme", [
        '@menu' => $menu_name,
        '@theme' => $theme,
      ]);
    }
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function getMenuItem(string $menu_name, string $plugin_id) {
    $tree = &drupal_static(__FUNCTION__);
    if (is_null($tree)) {
      $tree = $this->menuTree->load($menu_name, (new MenuTreeParameters())->onlyEnabledLinks());
    }
    $item = self::findMenuItem($tree, $plugin_id);
    return $item;
  }

  /**
   * {@inheritdoc}
   */
  public function findMenuItem(array $tree, string $plugin_id) {
    foreach ($tree as $menu_plugin_id => $item) {
      if ($menu_plugin_id == $plugin_id) {
        return $item;
      }
      elseif ($result = self::findMenuItem($item->subtree, $plugin_id)) {
        return $result;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function loadEntityBlock(string $block_id) {
    $block = $this->entityTypeManager->getStorage('block')->load($block_id);
    // Ensure the current user has permissions to view the block.
    if ($block && $block->access('view')) {
      return $block;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getMenuConfig(string $menu_name, string $theme) {
    $menu = self::getMenus($menu_name, $theme);
    return isset($menu) ? $menu->getMenuConfig() : [];
  }

  /**
   * {@inheritdoc}
   */
  public function editBlockConfig(array &$block_config) {
    $block_config += [
      'animation' => 'none',
      'style' => '',
      'auto-arrow' => TRUE,
      'duration' => 400,
      'delay' => 200,
      'always-show-submenu' => TRUE,
      'off-canvas' => 0,
      'number-columns' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function editSubMenuConfig(array &$submenu_config) {
    $submenu_config += [
      'width' => '',
      'class' => '',
      'group' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function editItemConfig(array &$item_config) {
    $attributes = [
      'xicon' => '',
      'class' => '',
      'caption' => '',
      'alignsub' => '',
      'group' => 0,
      'hidewcol' => 0,
      'hidesub' => 0,
      'label' => '',
    ];
    foreach ($attributes as $attribute => $value) {
      if (!isset($item_config[$attribute])) {
        $item_config[$attribute] = $value;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function editColumnConfig(array &$col_config) {
    $attributes = [
      'width' => 12,
      'class' => '',
      'hidewcol' => 0,
      'showblocktitle' => 0,
    ];
    foreach ($attributes as $attribute => $value) {
      if (!isset($col_config[$attribute])) {
        $col_config[$attribute] = $value;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function renderBlock(string $menu_name, string $theme) {
    return [
      '#theme' => 'tb_megamenu',
      '#menu_name' => $menu_name,
      '#block_theme' => $theme,
      '#section' => 'backend',
      '#post_render' => ['\Drupal\tb_megamenu\Controller\TBMegaMenuController::tbMegamenuAttachNumberColumns'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIdColumn(int $number_columns) {
    $value = &drupal_static('column');
    if (!isset($value)) {
      $value = 1;
    }
    elseif (!$number_columns || $value < $number_columns) {
      $value++;
    }
    return "tb-megamenu-column-$value";
  }

  /**
   * {@inheritdoc}
   */
  public function getAllBlocks(string $theme) {
    static $_blocks_array = [];
    if (empty($_blocks_array)) {
      // Get storage handler of block.
      $block_storage = $this->entityTypeManager->getStorage('block');
      // Get the enabled block in the default theme.
      $entity_ids = $block_storage->getQuery()->condition('theme', $theme)->execute();
      $entities = $block_storage->loadMultiple($entity_ids);
      $_blocks_array = [];
      foreach ($entities as $block_id => $block) {
        // Ensure the current user has access to view the block and the block
        // is not provided by the tb_megamenu module.
        if ($block->get('settings')['provider'] != 'tb_megamenu'
        && $block->access('view')) {
          $_blocks_array[$block_id] = $block->label();
        }
      }
      asort($_blocks_array);
    }
    return $_blocks_array;
  }

  /**
   * {@inheritdoc}
   */
  public function createAnimationOptions(array $block_config) {
    return [
      'none' => $this->t('None'),
      'fading' => $this->t('Fading'),
      'slide' => $this->t('Slide'),
      'zoom' => $this->t('Zoom'),
      'elastic' => $this->t('Elastic'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function createStyleOptions(array $block_config) {
    return [
      '' => $this->t('Default'),
      'black' => $this->t('Black'),
      'blue' => $this->t('Blue'),
      'green' => $this->t('Green'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildPageTrail(array $menu_items) {
    $trail = [];
    foreach ($menu_items as $pluginId => $item) {
      $is_front = $this->pathMatcher->isFrontPage();
      $route_name = $item->link->getPluginDefinition()['route_name'];
      if ($item->inActiveTrail || ($route_name == '<front>' && $is_front)) {
        $trail[$pluginId] = $item;
      }

      if ($item->subtree) {
        $trail += self::buildPageTrail($item->subtree);
      }
    }
    return $trail;
  }

  /**
   * {@inheritdoc}
   */
  public function syncConfigAll(array $menu_items, array &$menu_config, string $section) {
    foreach ($menu_items as $id => $menu_item) {
      $item_config = isset($menu_config[$id]) ? $menu_config[$id] : [];
      if ($menu_item->hasChildren || $item_config) {
        self::syncConfig($menu_item->subtree, $item_config, $section);
        $menu_config[$id] = $item_config;
        self::syncConfigAll($menu_item->subtree, $menu_config, $section);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function syncConfig(array $items, array &$item_config, string $section) {
    if (empty($item_config['rows_content'])) {
      $item_config['rows_content'][0][0] = [
        'col_content' => [],
        'col_config' => [],
      ];
      // Add menu items to the configuration.
      self::addColContent($items, $item_config);
      // If the item in the first posision is empty, unset it.
      if (empty($item_config['rows_content'][0][0]['col_content'])) {
        unset($item_config['rows_content'][0]);
      }
    }
    else {
      $hash = [];
      foreach ($item_config['rows_content'] as $row_delta => $row) {
        foreach ($row as $col_delta => $col) {
          foreach ($col['col_content'] as $item_delta => $tb_item) {
            if (!empty($tb_item) && is_array($tb_item)) {
              // Add a menu item to the config.
              if ($tb_item['type'] == 'menu_item') {
                self::syncMenuItem($hash, $tb_item, $row_delta, $col_delta, $item_delta, $items, $item_config);
              }
              // Add a block to the config.
              elseif ($tb_item['type'] == 'block' && !empty($tb_item['block_id'])) {
                self::syncBlock($tb_item, $row_delta, $col_delta, $item_delta, $section, $item_config);
              }
              // Remove an invalid column from the config.
              else {
                self::removeColumn($tb_item, $row_delta, $col_delta, $item_delta, $item_config);
              }
            }
          }
        }
      }
      // Add all enabled links to the configuration.
      self::insertEnabledLinks($items, $hash, $item_config);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addColContent(array $items, array &$item_config) {
    foreach ($items as $plugin_id => $item) {
      if ($item->link->isEnabled()) {
        $item_config['rows_content'][0][0]['col_content'][] = [
          'type' => 'menu_item',
          'plugin_id' => $plugin_id,
          'tb_item_config' => [],
          'weight' => $item->link->getWeight(),
        ];
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function syncMenuItem(array &$hash, array $tb_item, $row_delta, $col_delta, $item_delta, array $items, array &$item_config) {
    $hash[$tb_item['plugin_id']] = [
      'row' => $row_delta,
      'col' => $col_delta,
    ];
    $existed = FALSE;
    foreach ($items as $plugin_id => $item) {
      if ($item->link->isEnabled() && $tb_item['plugin_id'] == $plugin_id) {
        $item_config['rows_content'][$row_delta][$col_delta]['col_content'][$item_delta]['weight'] = $item->link->getWeight();
        $existed = TRUE;
        break;
      }
    }
    if (!$existed) {
      unset($item_config['rows_content'][$row_delta][$col_delta]['col_content'][$item_delta]);
      if (empty($item_config['rows_content'][$row_delta][$col_delta]['col_content'])) {
        unset($item_config['rows_content'][$row_delta][$col_delta]);
      }
      if (empty($item_config['rows_content'][$row_delta])) {
        unset($item_config['rows_content'][$row_delta]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function syncBlock(array $tb_item, $row_delta, $col_delta, $item_delta, string $section, array &$item_config) {
    if (!self::isBlockContentEmpty($tb_item['block_id'], $section)) {
      unset($item_config['rows_content'][$row_delta][$col_delta]['col_content'][$item_delta]);
      if (empty($item_config['rows_content'][$row_delta][$col_delta]['col_content'])) {
        unset($item_config['rows_content'][$row_delta][$col_delta]);
      }
      if (empty($item_config['rows_content'][$row_delta])) {
        unset($item_config['rows_content'][$row_delta]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function removeColumn(array $tb_item, $row_delta, $col_delta, $item_delta, array &$item_config) {
    if (empty($tb_item)) {
      unset($item_config['rows_content'][$row_delta][$col_delta]['col_content'][$item_delta]);
    }
    $this->logger->warning("Unknown / invalid column content: <pre>@content</pre>", [
      '@content' => print_r($tb_item, TRUE),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function insertEnabledLinks(array $items, array $hash, array &$item_config) {
    $row = -1;
    $col = -1;
    foreach ($items as $plugin_id => $item) {
      if ($item->link->isEnabled()) {
        if (isset($hash[$plugin_id])) {
          $row = $hash[$plugin_id]['row'];
          $col = $hash[$plugin_id]['col'];
          continue;
        }
        if ($row > -1) {
          self::insertTbMenuItem($item_config, $row, $col, $item);
        }
        else {
          $row = $col = 0;
          while (isset($item_config['rows_content'][$row][$col]['col_content'][0]['type']) &&
          $item_config['rows_content'][$row][$col]['col_content'][0]['type'] == 'block') {

            $row++;
          }
          self::insertTbMenuItem($item_config, $row, $col, $item);
          $item_config['rows_content'][$row][$col]['col_config'] = [];
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function syncOrderMenus(array &$menu_config) {
    foreach ($menu_config as $mlid => $config) {
      foreach ($config['rows_content'] as $rows_id => $row) {
        $item_sorted = [];
        // Get weight from items.
        foreach ($row as $col) {
          foreach ($col['col_content'] as $menu_item) {
            if ($menu_item['type'] == 'menu_item') {
              $item_sorted[$menu_item['weight']][] = $menu_item;
            }
          }
        }
        // Sort menu items by weight.
        $item_sorted = self::sortByWeight($item_sorted);
        // Update $menu_config to reflect new sort order.
        foreach ($row as $rid => $col) {
          foreach ($col['col_content'] as $menu_item_id => $menu_item) {
            if ($menu_item['type'] == 'menu_item') {
              $menu_config[$mlid]['rows_content'][$rows_id][$rid]['col_content'][$menu_item_id] = array_shift($item_sorted);
            }
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function sortByWeight(array $item_sorted) {
    ksort($item_sorted);
    $new_list = [];
    foreach ($item_sorted as $weight_group) {
      foreach ($weight_group as $item) {
        $new_list[] = $item;
      }
    }

    return $new_list;
  }

  /**
   * {@inheritdoc}
   */
  public function isBlockContentEmpty(string $block_id, string $section) {
    $entity_block = self::loadEntityBlock($block_id);
    if ($entity_block && ($entity_block->getPlugin()->build() || $section == 'backend')) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function insertTbMenuItem(array &$item_config, $row, $col, $item) {
    $idx = 0;
    $col_content = isset($item_config['rows_content'][$row][$col]['col_content']) ? array_values($item_config['rows_content'][$row][$col]['col_content']) : [];
    current($col_content);
    foreach ($col_content as $value) {
      if (!empty($value['weight']) && $value['weight'] < $item->link->getWeight()) {
        next($col_content);
        $idx = key($col_content);
      }
    }
    for ($col = count($col_content); $col > $idx; $col--) {
      $col_content[$col] = $col_content[$col - 1];
    }
    $col_content[$idx] = [
      'plugin_id' => $item->link->getPluginId(),
      'type' => 'menu_item',
      'weight' => $item->link->getWeight(),
      'tb_item_config' => [],
    ];
    $item_config['rows_content'][$row][$col]['col_content'] = $col_content;
  }

}
