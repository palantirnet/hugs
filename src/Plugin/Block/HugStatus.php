<?php

namespace Drupal\hugs\Plugin\Block;

use Drupal\block\BlockBase;

/**
 * Reports on hugability status.
 *
 * @Block(
 *   id = "hugs_status",
 *   admin_label = @Translation("Hug status"),
 *   category = @Translation("System")
 * )
 */
class HugStatus extends BlockBase {

  public function defaultConfiguration() {
    return array('enabled' => 1);
  }

  public function blockForm($form, &$form_state) {
    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hugging enabled'),
      '#default_value' => $this->configuration['enabled'],
    ];

    return $form;
  }

  public function blockSubmit($form, &$form_state) {
    $this->configuration['enabled'] = (bool)$form_state['values']['enabled'];
  }

  public function build() {
    $message = $this->configuration['enabled']
      ? $this->t('Now accepting hugs')
      : $this->t('No hugs :-(');
    return [
      '#markup' => $message,
    ];
  }
}
