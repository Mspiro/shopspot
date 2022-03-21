<?php

namespace Drupal\login_popup\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'LoginAndRegisterPopup' block.
 *
 * @Block(
 *  id = "login_register_form_popup",
 *  admin_label = @Translation("Login and Registration Form Popup"),
 * )
 */
class LoginAndRegisterPopup extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form_builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The current_user service.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new WelcomeUserNameBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form_builder service.
   * @param \Drupal\Core\Session\AccountProxy $currentUser
   *   The current_user service.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder, AccountProxy $currentUser, Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->currentUser = $currentUser;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('current_user'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $url_register = Url::fromRoute('user.register');
    $url_login = Url::fromRoute('user.login');
    $link_options = [
      'attributes' => [
        'class' => [
          'use-ajax',
          'login-popup-form',
        ],
        'data-dialog-type' => 'modal',
      ],
    ];
    $url_register->setOptions($link_options);
    $url_login->setOptions($link_options);
    $link_register = Link::fromTextAndUrl($this->t('Register'), $url_register)->toString();
    $link_login = Link::fromTextAndUrl($this->t('Log in'), $url_login)->toString();
    $build = [];
    if ($this->currentUser->isAnonymous()) {
      $build['login_register_popup_block']['#markup'] = '<div class="Login-Register-popup-link"><span>' . $link_login . '</span> | <span>' . $link_register . '</span></div>';
    }
    $build['login_register_popup_block']['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $build;
  }

}
