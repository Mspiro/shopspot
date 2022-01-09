<?php

namespace Drupal\menu_manipulator\Form;

use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\language\ConfigurableLanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure custom settings for Menu Manipulators.
 */
class MenuManipulatorSettingsForm extends ConfigFormBase {

  use StringTranslationTrait;

  /**
   * The list of existing Menus (config entities).
   *
   * @var array
   */
  protected $menus;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Cache backend instance for the extracted tree data.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $menuCacheBackend;

  /**
   * Block manager service.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface|null
   */
  protected $blockManager;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_manipulator_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'menu_manipulator.settings',
    ];
  }

  /**
   * Constructs a MenuManipulatorSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityStorageInterface $menu_storage
   *   The menu storage service.
   * @param \Drupal\language\ConfigurableLanguageManagerInterface $language_manager
   *   The language manager handler.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    ConfigurableLanguageManagerInterface $language_manager,
    CacheBackendInterface $cache_menu,
    ModuleHandlerInterface $module_handler,
    EntityStorageInterface $menu_storage
  ) {
    parent::__construct($config_factory);

    $this->languageManager = $language_manager;
    $this->menuCacheBackend = $cache_menu;
    $this->menus = $menu_storage->loadMultiple();

    if ($module_handler->moduleExists('block')) {
      $this->blockManager = \Drupal::service('plugin.manager.block');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('language_manager'),
      $container->get('cache.menu'),
      $container->get('module_handler'),
      $container->get('entity_type.manager')->getStorage('menu')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('menu_manipulator.settings');

    $menus_options = [];
    foreach ($this->menus as $menu) {
      $menus_options[$menu->id()] = $menu->label();
    }

    // Quick intro.
    $form['intro'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('Configure custom Menu Manipulator settings here.') . '</p>',
    ];

    // Language settings.
    $form['language'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('Language'),
    ];
    $form['language']['preprocess_menus_language'] = [
      '#type' => 'checkbox',
      '#title' => $this->t("Automatically filter menus by current's user language"),
      '#default_value' => $config->get('preprocess_menus_language'),
    ];
    $form['language']['preprocess_menus_language_use_entity'] = [
      '#type' => 'checkbox',
      '#title' => $this->t("Use referenced entity's language to filter menus by current's user language"),
      '#default_value' => $config->get('preprocess_menus_language_use_entity') ?? 1,
    ];
    $form['language']['preprocess_menus_language_list'] = [
      '#type' => 'checkboxes',
      '#options' => $menus_options,
      '#title' => $this->t("Select menus to be filtered by language"),
      '#description' => $this->t("If none selected, all menus will be filtered by language."),
      '#default_value' => !empty($config->get('preprocess_menus_language_list')) ? $config->get('preprocess_menus_language_list') : [],
      '#states' => [
        'visible' => [
          ':input[name="preprocess_menus_language"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Custom icons.
    $form['theming'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('Theming'),
    ];
    $form['theming']['preprocess_menus_icon'] = [
      '#type' => 'checkbox',
      '#title' => $this->t("Process menu items to display icons"),
      '#default_value' => $config->get('preprocess_menus_icon') ?? 1,
    ];
    $form['theming']['menu_link_icon_list'] = [
      '#type' => 'textarea',
      '#title' => $this->t("List of available icons"),
      '#default_value' => !empty($config->get('menu_link_icon_list')) ? $config->get('menu_link_icon_list') : '',
      '#states' => [
        'visible' => [
          ':input[name="preprocess_menus_icon"]' => ['checked' => TRUE],
        ],
      ],
      '#description' => $this->t("One value per line (e.g `facebook|Facebook`)."),
    ];
    $form['theming']['preprocess_menus_icon_list'] = [
      '#type' => 'checkboxes',
      '#options' => $menus_options,
      '#title' => $this->t("Select menus with icons"),
      '#default_value' => !empty($config->get('preprocess_menus_icon_list')) ? $config->get('preprocess_menus_icon_list') : [],
      '#states' => [
        'visible' => [
          ':input[name="preprocess_menus_icon"]' => ['checked' => TRUE],
        ],
      ],
      '#description' => $this->t("If none selected, all menus will be processed to expose icon in Twig."),
    ];

    $form['examples'] = [
      '#type' => 'details',
      '#title' => $this->t('Examples'),
      '#open' => TRUE,
    ];

    $form['examples']['available_variables'] = [
      '#theme' => 'item_list',
      '#prefix' => '<p class="description">' . $this->t('You can use the following variables in your in menu.html.twig templates:') . '</p>',
      '#items' => [
        ['#markup' => '<code>{{ menu_title }}</code>'],
        ['#markup' => '<code>{{ menu_description }}</code>'],
        ['#markup' => "<code>{% for item in items $} {% set icon = item['#icon'] ?: item.original_link.getOptions().icon %}</code>"],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('menu_manipulator.settings');

    $exclude = ['submit', 'form_build_id', 'form_token', 'form_id', 'op'];

    foreach ($form_state->getValues() as $key => $data) {
      if (!in_array($key, $exclude)) {
        $config->set($key, $data);
      }
    }
    $config->save();

    parent::submitForm($form, $form_state);

    // Invalidate the menu cache.
    $this->menuCacheBackend->invalidateAll();

    // Invalidate the block cache to update menu-based derivatives.
    if ($this->blockManager instanceof BlockManagerInterface) {
      $this->blockManager->clearCachedDefinitions();
    }
  }

}
