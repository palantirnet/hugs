<?php

namespace Drupal\hugs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigForm extends ConfigFormBase {
  public function getFormId() {
    return 'hug_config';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('hugs.settings');

    $form['default_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Default hug count'),
      '#default_value' => $config->get('default_count'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('hugs.settings');
    $config->set('default_count', $form_state->getValue('default_count'));
    $config->save();
  }

  public function getEditableConfigNames() {
    return ['hugs.settings'];
  }
}
