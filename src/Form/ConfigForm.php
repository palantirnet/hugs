<?php

namespace Drupal\hugs\Form;

use Drupal\Core\Form\ConfigFormBase;

class ConfigForm extends ConfigFormBase {
  public function getFormId() {
    return 'hug_config';
  }

  public function buildForm(array $form, array &$form_state) {
    $config = $this->config('hugs.settings');

    $form['default_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Default hug count'),
      '#default_value' => $config->get('default_count'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, array &$form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('hug.settings');
    $config->set('default_count', $form_state['values']['default_count']);
    $config->save();
  }
}
