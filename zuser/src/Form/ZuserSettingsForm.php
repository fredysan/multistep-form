<?php

namespace Drupal\zuser\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ZuserSettingsForm.
 */
class ZuserSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'zuser.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'zuser_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('zuser.settings');
    $form['emails_domain'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Emails Domain'),
      '#description' => $this->t('Domain name that new users will be assigned to.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('emails_domain'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('zuser.settings')
      ->set('emails_domain', $form_state->getValue('emails_domain'))
      ->save();
  }

}
