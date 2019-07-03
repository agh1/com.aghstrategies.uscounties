<?php

/**
 * Check and load counties
 */
function uscounties_loadcounties() {

  // The counties.json file consists of JSON for one object where each key is
  // the ISO code for a country, and each value is an object.  For each of those
  // inner objects, each key is the name for a state/province and the value is
  // an array of county names.
  $allCounties = json_decode(file_get_contents(__DIR__ . '/counties.json'), TRUE);

  foreach ($allCounties as $countryIso => $counties) {
    // Get array of states.
    try {
      $result = civicrm_api3('Country', 'getsingle', [
        'iso_code' => $countryIso,
        'api.Address.getoptions' => [
          'field' => 'state_province_id',
          'country_id' => '$value.id',
          'sequential' => 0,
        ],
      ]);
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error: %1', [
        'domain' => 'com.aghstrategies.uscounties',
        1 => $error,
      ]));
      return FALSE;
    }

    if (empty($result['api.Address.getoptions']['values'])) {
      return FALSE;
    }
    $states = $result['api.Address.getoptions']['values'];

    // go state-by-state to check existing counties

    foreach ($counties as $stateName => $state) {
      $id = array_search($stateName, $states);
      if ($id === FALSE) {
        continue;
      }

      $check = "SELECT name FROM civicrm_county WHERE state_province_id = $id";
      $results = CRM_Core_DAO::executeQuery($check);
      $existing = [];
      while ($results->fetch()) {
        $existing[] = $results->name;
      }

      // identify counties needing to be loaded
      $add = array_diff($state, $existing);

      $insert = [];
      foreach ($add as $county) {
        $countye = CRM_Core_DAO::escapeString($county);
        $insert[] = "('$countye', $id)";
      }

      // put it into queries of 50 counties each
      for ($i = 0; $i < count($insert); $i = $i + 50) {
        $inserts = array_slice($insert, $i, 50);
        $query = "INSERT INTO civicrm_county (name, state_province_id) VALUES ";
        $query .= implode(', ', $inserts);
        CRM_Core_DAO::executeQuery($query);
      }
    }
  }

  return TRUE;
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function uscounties_civicrm_install() {
  uscounties_loadcounties();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function uscounties_civicrm_enable() {
  uscounties_loadcounties();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function uscounties_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  uscounties_loadcounties();
}
