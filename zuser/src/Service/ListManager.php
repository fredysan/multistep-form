<?php

namespace Drupal\zuser\Service;

/**
 * List Manager class.
 */
class ListManager implements ListManagerInterface {

  /**
   * Gets a list of Cities.
   *
   * @return array
   *   Cities associative array
   */
  public function getCities(bool $add_empty = FALSE) : array {
    $cities = [
      'BOGOTA' => 'BOGOTA',
      'MEDELLIN' => 'MEDELLIN',
      'CALI' => 'CALI',
      'BARRANQUILLA' => 'BARRANQUILLA',
      'CARTAGENA' => 'CARTAGENA',
      'CUCUTA' => 'CUCUTA',
      'SOACHA' => 'SOACHA',
      'SOLEDAD' => 'SOLEDAD',
      'BUCARAMANGA' => 'BUCARAMANGA',
      'BELLO' => 'BELLO',
      'VILLAVICENCIO' => 'VILLAVICENCIO',
      'IBAGUE' => 'IBAGUE',
      'SANTA' => 'SANTA',
      'VALLEDUPAR' => 'VALLEDUPAR',
      'MONTERIA' => 'MONTERIA',
      'PEREIRA' => 'PEREIRA',
      'MANIZALES' => 'MANIZALES',
      'PASTO' => 'PASTO',
      'NEIVA' => 'NEIVA',
      'PALMIRA' => 'PALMIRA',
      'POPAYAN' => 'POPAYAN',
      'BUENAVENTURA' => 'BUENAVENTURA',
      'FLORIDABLANCA' => 'FLORIDABLANCA',
      'ARMENIA' => 'ARMENIA',
      'SINCELEJO' => 'SINCELEJO',
      'ITAGUI' => 'ITAGUI',
      'TUMACO' => 'TUMACO',
      'ENVIGADO' => 'ENVIGADO',
      'DOSQUEBRADAS' => 'DOSQUEBRADAS',
      'TULUA' => 'TULUA',
      'BARRANCABERMEJA' => 'BARRANCABERMEJA',
      'RIOHACHA' => 'RIOHACHA',
    ];
    if ($add_empty) {
      $cities = ['' => '- Select One -'] + $cities;
    }
    return $cities;
  }

}
