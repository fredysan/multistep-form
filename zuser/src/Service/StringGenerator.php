<?php

namespace Drupal\zuser\Service;

use Drupal\Component\Transliteration\TransliterationInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Mail Generator Service.
 */
class StringGenerator implements StringGeneratorInterface {

  /**
   * Transliteration Instance.
   *
   * @var \Drupal\Component\Transliteration\TransliterationInterface
   */
  private $transliteration;

  /**
   * Constructor.
   */
  public function __construct(TransliterationInterface $transliteration) {
    $this->transliteration = $transliteration;
  }

  /**
   * Gets a valid email address.
   */
  public function getUsername(array $source) {
    $value = array_reduce($source, function ($carry, $key) use ($source) {
      return $carry . $key;
    });
    $new_value = $this->transliteration
      ->transliterate($value, LanguageInterface::LANGCODE_DEFAULT, '_');
    $new_value = strtolower($new_value);
    $new_value = preg_replace('/[^a-z0-9_]+/', '_', $new_value);
    $new_value = preg_replace('/_+/', '_', $new_value);
    return $new_value;
  }

}
