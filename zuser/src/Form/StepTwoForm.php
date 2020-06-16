<?php

namespace Drupal\zuser\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Step Two Form.
 */
class StepTwoForm extends MultistepFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multistep_form_two';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $cities = $this->listManager->getCities(TRUE);
    $form['city'] = [
      '#type' => 'select',
      '#options' => $cities,
      '#title' => $this->t('City'),
      '#default_value' => $this->store->get('city') ? $this->store->get('city') : '',
      '#required' => TRUE,
    ];

    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      '#default_value' => $this->store->get('phone') ? $this->store->get('phone') : '',
    ];

    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
      '#default_value' => $this->store->get('address') ? $this->store->get('address') : '',
    ];

    $form['actions']['previous'] = [
      '#type' => 'link',
      '#title' => $this->t('Previous'),
      '#attributes' => [
        'class' => ['button'],
      ],
      '#weight' => 0,
      '#url' => Url::fromRoute('zuser.step_one'),
    ];

    $form['actions']['submit']['#value'] = $this->t('Next');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('city', $form_state->getValue('city'));
    $this->store->set('phone', $form_state->getValue('phone'));
    $this->store->set('address', $form_state->getValue('address'));
    $form_state->setRedirect('zuser.step_three');
  }

}
