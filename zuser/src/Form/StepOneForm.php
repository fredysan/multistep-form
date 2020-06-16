<?php

namespace Drupal\zuser\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormStateInterface;

/**
 * Step One Form.
 */
class StepOneForm extends MultistepFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multistep_form_one';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#default_value' => $this->store->get('first_name') ? $this->store->get('first_name') : '',
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#default_value' => $this->store->get('last_name') ? $this->store->get('last_name') : '',
      '#required' => TRUE,
    ];

    $genders = [
      '' => $this->t('- Select one -'),
      'male' => $this->t('Male'),
      'female' => $this->t('Female'),
    ];
    $form['gender'] = [
      '#type' => 'select',
      '#options' => $genders,
      '#title' => $this->t('Gender'),
      '#default_value' => $this->store->get('gender') ? $this->store->get('gender') : '',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::getGenderImage',
        'disable-refocus' => FALSE,
        'event' => 'change',
        'wrapper' => 'gender-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
      '#suffix' => '<div id="gender-wrapper"></div>',
    ];

    $form['birth_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Birth Date'),
      '#default_value' => $this->store->get('birth_date') ? $this->store->get('birth_date') : '',
      '#required' => TRUE,
    ];
    $form['#attached']['library'][] = 'zuser/zuser.js';

    $form['actions']['submit']['#value'] = $this->t('Next');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation is optional.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('first_name', $form_state->getValue('first_name'));
    $this->store->set('last_name', $form_state->getValue('last_name'));
    $this->store->set('gender', $form_state->getValue('gender'));
    $this->store->set('birth_date', $form_state->getValue('birth_date'));
    $form_state->setRedirect('zuser.step_two');
  }

  /**
   * Gets an Image based on the gender.
   */
  public static function getGenderImage(array &$form, FormStateInterface $form_state) {
    $gender = $form_state->getValue('gender');

    $img = '';
    if (!empty($gender)) {
      $img_path = '/' . drupal_get_path('module', 'zuser') . '/assets/' . $gender . '.png';
      $img = '<img src="' . $img_path . '" />';
    }

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#gender-wrapper', $img));
    return $response;
  }

}
