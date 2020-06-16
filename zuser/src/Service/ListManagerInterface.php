<?php

namespace Drupal\zuser\Service;

/**
 * List Manager Interface.
 */
interface ListManagerInterface {

  /**
   * Gets a list of Cities.
   *
   * @return array
   *   Cities associative array
   */
  public function getCities() : array;

}
