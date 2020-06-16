<?php

namespace Drupal\zuser\Service;

/**
 * Mail Generator Service Interface.
 */
interface StringGeneratorInterface {

  /**
   * Gets a valid email address.
   */
  public function getUsername(array $source);

}
