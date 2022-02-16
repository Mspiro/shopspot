<?php

namespace Drupal\cheeseburger_menu\Plugin\Menu;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Menu\MenuLinkBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Language swithcher link - dynamically changes based on langcode.
 */
class LanguageSwitchMenuLink extends MenuLinkBase implements ContainerFactoryPluginInterface {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatcher;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new MenuLinkContent.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   Path matcher.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LanguageManagerInterface $language_manager, PathMatcherInterface $path_matcher, EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->languageManager = $language_manager;
    $this->pathMatcher = $path_matcher;
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('language_manager'),
      $container->get('path.matcher'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getTitle() {
    $language_menu = $this->entityTypeManager->getStorage('menu')->load('language');
    $langcode = $this->getPluginDefinition()['options']['langcode'];
    return $language_menu->getThirdPartySetting('cheeseburger_menu', 'use_langcode', TRUE) ? $langcode : $this->languageManager
      ->getLanguageName($langcode);
  }

  /**
   * {@inheritDoc}
   */
  public function getUrlObject($title_attribute = TRUE) {
    if ($this->pathMatcher->isFrontPage()) {
      $url = Url::fromRoute('<front>');
    }
    else {
      $current_route = $this->routeMatch->getRouteObject();
      $options = $current_route->getOptions();
      $url = Url::fromRoute($this->routeMatch->getRouteName(), $this->routeMatch->getRawParameters()->all(), $options);
    }

    $language = $this->languageManager->getLanguage($this->getPluginDefinition()['options']['langcode']);
    return $url->setOption('language', $language);
  }

  /**
   * {@inheritDoc}
   */
  public function getDescription() {
    return $this->t('Menu link to current page on certain language');
  }

  /**
   * {@inheritDoc}
   */
  public function updateLink(array $new_definition_values, $persist) {
    return $new_definition_values;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return [
      'url.path',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheTags() {
    return [
      'config:system.menu.language',
    ];
  }

}
