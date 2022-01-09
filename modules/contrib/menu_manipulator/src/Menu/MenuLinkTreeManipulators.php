<?php

namespace Drupal\menu_manipulator\Menu;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Menu\InaccessibleMenuLink;
use Drupal\Core\Menu\MenuLinkBase;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;

/**
 * Provides a menu link tree manipulators.
 *
 * This class provides a menu link tree manipulators to:
 * - filter by current language.
 *
 * @see menu_manipulator_get_multilingual_menu() to see example of use.
 */
class MenuLinkTreeManipulators {

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepository
   */
  protected $entityRepository;

  /**
   * The current language ID.
   *
   * @var string
   */
  protected $langcode;

  /**
   * The menu_link_content storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $menuLinkContentStorage;

  /**
   * Our custom configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a \Drupal\Core\Menu\DefaultMenuLinkTreeManipulators object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Language\LanguageManager $language_manager
   *   The language manager.
   * @param \Drupal\Core\Entity\EntityRepository $entity_repository
   *   The entity repository.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(
    EntityRepository $entity_repository,
    EntityTypeManagerInterface $entity_type_manager,
    LanguageManager $language_manager,
    ConfigFactoryInterface $config_factory
  ) {
    $this->entityRepository = $entity_repository;
    $this->menuLinkContentStorage = $entity_type_manager->getStorage('menu_link_content');
    $this->langcode = $language_manager->getCurrentLanguage()->getId();
    $this->config = $config_factory->get('menu_manipulator.settings');
  }

  /**
   * Filter a menu tree by current language.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeElement[] $tree
   *   The menu link tree to manipulate.
   *
   * @return \Drupal\Core\Menu\MenuLinkTreeElement[]
   *   The manipulated menu link tree.
   */
  public function filterTreeByCurrentLanguage(array $tree) {
    foreach ($tree as $key => $element) {
      if (!$element->link instanceof MenuLinkBase) {
        continue;
      }

      $access = $this->checkLinkAccess($element->link);

      if (!$access) {
        // Deny access and hide children items.
        $tree[$key]->link = new InaccessibleMenuLink($tree[$key]->link);
        $tree[$key]->access = AccessResult::forbidden();
        $tree[$key]->subtree = [];
      }

      // Filter children items recursively.
      if ($element->hasChildren && !empty($tree[$key]->subtree)) {
        $element->subtree = $this->filterTreeByCurrentLanguage($element->subtree);
      }

    }
    return $tree;
  }

  /**
   * Filter a list of menu items by current language.
   *
   * @param array $items
   *   Generally, the $variables['items'] in menu preprocess.
   *   Passed by reference.
   */
  public function filterItemsByCurrentLanguage(array &$items) {
    foreach (Element::children($items) as $i) {
      if (($link = $items[$i]['original_link'] ?? NULL) instanceof MenuLinkBase) {
        if (!$this->checkLinkAccess($link)) {
          // Deny access and hide children items.
          unset($items[$i]);
        }
      }

      // Filter children items recursively.
      $children = $items[$i]['below'] ?? [];
      if (!empty($children)) {
        $this->filterItemsByCurrentLanguage($items[$i]['below']);
      }
    }
  }

  /**
   *
   */
  public function checkLinkAccess(MenuLinkBase $link) {
    $langcode = $this->getLinkLanguage($link);

    $not_applicable_langcodes = [
      LanguageInterface::LANGCODE_NOT_APPLICABLE,
      LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ];

    // Allow unspecified languages.
    if (in_array($langcode, $not_applicable_langcodes)) {
      return TRUE;
    }

    // Check if referenced entity can be used. Yes by default.
    $options = $link->getOptions() ?: [];
    $language_use_entity_default = $this->config->get('preprocess_menus_language_use_entity') ?? 1;
    $language_use_entity = $options['language_use_entity'] ?? $language_use_entity_default;
    if ($language_use_entity) {
      $entity = $this->getLinkEntity($link);
      // Allow if targeted entity is translated, no matter menu item's language.
      if ($entity instanceof ContentEntityInterface && method_exists($entity, 'hasTranslation')) {
        return $entity->hasTranslation($this->langcode);
      }
    }

    // Allow by the menu item's language itself.
    return $this->langcode == $langcode;
  }

  /**
   * Force the MenuLinkBase to tell us its language code.
   *
   * @param \Drupal\Core\Menu\MenuLinkBase $link
   *   `The Menu Link Content entity.
   *
   * @return string
   *   The menu Link language ID or a default value.
   */
  protected function getLinkLanguage(MenuLinkBase $link) {
    $metadata = $link->getMetaData();
    if (!isset($metadata['entity_id'])) {
      return LanguageInterface::LANGCODE_NOT_APPLICABLE;
    }

    $loaded_link = $this->menuLinkContentStorage->load($metadata['entity_id']);
    $loaded_lang_link = $this->entityRepository->getTranslationFromContext($loaded_link);

    return $loaded_lang_link->language()->getId();
  }

  /**
   * Get targeted entity for a given MenuLinkBase.
   *
   * @param \Drupal\Core\Menu\MenuLinkBase $link
   *   `The Menu Link Content entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null|bool
   *   FALSE if Url is unrouted. Otherwise, an entity object variant or NULL.
   */
  protected function getLinkEntity(MenuLinkBase $link) {
    $metadata = $link->getMetaData();
    if (!isset($metadata['entity_id'])) {
      return NULL;
    }

    $loaded_link = $this->menuLinkContentStorage->load($metadata['entity_id']);

    $url = Url::fromUri($loaded_link->get('link')->getString());
    if (!$url instanceof Url || !$url->isRouted()) {
      return FALSE;
    }

    // Get entity info from route.
    $parts = explode('/', $url->getInternalPath());
    if ((empty($parts[0] ?? '')) || (empty($parts[1] ?? ''))) {
      return FALSE;
    }

    // Get core route parameters (e.g. /node/1).
    $entity_type = $parts[0] ?? NULL;
    $entity_id = $parts[1] ?? NULL;
    if (!\is_string($entity_type) || !$entity_id) {
      return FALSE;
    }

    return $this->entityRepository->getActive($entity_type, $entity_id);
  }

}
