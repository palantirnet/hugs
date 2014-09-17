<?php

namespace Drupal\hugs\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\hugs\HugTracker;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Reports on hugability status.
 *
 * @Block(
 *   id = "hugs_status",
 *   admin_label = @Translation("Hug status"),
 *   category = @Translation("System")
 * )
 */
class HugStatus extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\hugs\HugTracker
   */
  protected $hugTracker;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, HugTracker $hugTracker) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->hugTracker = $hugTracker;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('hugs.hug_tracker')
    );
  }

  public function defaultConfiguration() {
    return ['enabled' => 1];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hugging enabled'),
      '#default_value' => $this->configuration['enabled'],
    ];

    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['enabled'] = (bool)$form_state->getValue('enabled');
  }

  public function build() {
    if ($this->configuration['enabled']) {
      $message = $this->t('@to was the last person hugged', [
        '@to' => $this->hugTracker->getLastRecipient()
      ]);
    }
    else {
      $message = $this->t('No hugs :-(');
    }
    return [
      '#markup' => $message,
    ];
  }
}
