<?php

namespace Drupal\zuser\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Step Three Form.
 */
class StepThreeForm extends MultistepFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multistep_form_three';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $summary = $this->getSummary();
    $form['summary'] = [
      '#markup' => $summary,
      '#allowed_tags' => ['div', 'table', 'tr', 'th', 'td'],
    ];

    $form['actions']['finish'] = [
      '#type' => 'link',
      '#title' => $this->t('Previous'),
      '#attributes' => [
        'class' => ['button'],
      ],
      '#weight' => 0,
      '#url' => Url::fromRoute('zuser.step_two'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('zuser.step_one');

    // Save the data.
    parent::saveData($form, $form_state);
  }

  /**
   * Gets the User data summary.
   */
  protected function getSummary() {
    $fields = [
      'first_name' => $this->t('First Name'),
      'last_name' => $this->t('Last Name'),
      'gender' => $this->t('Gender'),
      'birth_date' => $this->t('Birth Date'),
      'city' => $this->t('City'),
      'phone' => $this->t('Phone'),
      'address' => $this->t('Address'),
    ];
    $html = '<div id="form-summary">';
    $html .= '  <table>';
    $html .= '    <tr><th>Field</th><th>Value</th></tr>';

    foreach ($fields as $key => $field) {
      $html .= '<tr><td>' . $field . '</td>';
      $html .= '<td>' . $this->store->get($key) . '</td></tr>';
    }

    $html .= '  </table>';
    $html .= '</div>';
    return $html;
  }

}
