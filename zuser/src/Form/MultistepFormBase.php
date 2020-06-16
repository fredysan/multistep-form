<?php

namespace Drupal\zuser\Form;

use Drupal\Component\Utility\Random;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\user\Entity\User;
use Drupal\zuser\Service\ListManagerInterface;
use Drupal\zuser\Service\StringGeneratorInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Multistep Form Base.
 */
abstract class MultistepFormBase extends FormBase {

  /**
   * Temporal Storage Instance.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * Language Manager Instance.
   *
   * @var \Drupal\Core\Language\LanguageManager
   */
  private $languageManager;

  /**
   * Current User Instance.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * Temporal Storage Instance.
   *
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;

  /**
   * List Manager Instance.
   *
   * @var \Drupal\zuser\Service\ListManagerInterface
   */
  protected $listManager;

  /**
   * String Generator Instance.
   *
   * @var \Drupal\zuser\Service\StringGeneratorInterface
   */
  protected $stringGenerator;

  /**
   * Constructs a \Drupal\demo\Form\Multistep\MultistepFormBase.
   */
  public function __construct(
    AccountInterface $current_user,
    MessengerInterface $messenger,
    LanguageManagerInterface $language_manager,
    PrivateTempStoreFactory $temp_store_factory,
    StringGeneratorInterface $string_generator,
    ListManagerInterface $list_manager
  ) {
    $this->currentUser = $current_user;
    $this->messenger = $messenger;
    $this->languageManager = $language_manager;
    $this->stringGenerator = $string_generator;
    $this->tempStoreFactory = $temp_store_factory;
    $this->listManager = $list_manager;

    $this->store = $this->tempStoreFactory->get('multistep_data');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('messenger'),
      $container->get('language_manager'),
      $container->get('tempstore.private'),
      $container->get('zuser.string_generator'),
      $container->get('zuser.list_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#weight' => 10,
    ];

    return $form;
  }

  /**
   * Saves the data from the multistep form.
   */
  protected function saveData(array &$form, FormStateInterface $form_state) {
    $new_user = $this->createUser();
    if ($new_user instanceof AccountInterface) {
      $this->messenger->addMessage($this->t('The form has been submitted and a new user was created: @user', [
        '@user' => $new_user->toLink()->toString(),
      ]));
    }
    else {
      $this->messenger->addMessage($this->t('It was not possible to create the user'), MessengerInterface::TYPE_ERROR);
    }

    $this->deleteStore();
    $form_state->setRedirect('zuser.step_one');
  }

  /**
   * Removes all the keys from the store collection.
   */
  protected function deleteStore() {
    $keys = [
      'first_name',
      'last_name',
      'gender',
      'birth_date',
      'city',
      'phone',
      'address',
    ];
    foreach ($keys as $key) {
      $this->store->delete($key);
    }
  }

  /**
   * Creates a new User entity.
   *
   * @return \Drupal\Core\Session\AccountInterface
   *   A new user account.
   */
  protected function createUser() {
    $user = User::create();

    // Generate a new username from the name parts.
    $username = $this->stringGenerator->getUsername([
      $this->store->get('first_name')[0],
      $this->store->get('last_name'),
    ]);

    $domain = $this->configFactory()
      ->get('zuser.settings')->get('emails_domain');
    $email = $username . '@' . $domain;

    // Assign a random password.
    $random = new Random();
    $pass = $random->string();
    $user->setPassword($pass);

    $user->enforceIsNew();
    $user->setEmail($email);
    $user->setUsername($username);
    $user->activate();
    $user->set('init', $email);
    // Set Language.
    $language_id = $this->languageManager->getCurrentLanguage()->getId();
    $user->set('langcode', $language_id);
    $user->set('preferred_langcode', $language_id);
    $user->set('preferred_admin_langcode', $language_id);

    $saved = FALSE;
    try {
      $saved = $user->save();
    }
    catch (Exception $e) {
      $this->logger('user')->alert('An user with the same email already exists. The user was not created');
    }
    return $saved ? $user : NULL;
  }

}
