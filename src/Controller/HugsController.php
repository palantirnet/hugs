<?php

namespace Drupal\hugs\Controller;

use Drupal\Core\Controller\ControllerBase;

class HugsController extends ControllerBase {

  public function hug($to, $from, $count) {
    if (!$count) {
      $count = $this->config('hugs.settings')->get('default_count');
    }
    return [
      '#theme' => 'hug_page',
      '#from' => $from,
      '#to' => $to,
      '#count' => $count
    ];
  }

  public function hug2($to, $from) {
    return [
      '#theme' => 'hug_page',
      '#from' => $from,
      '#to' => $to,
    ];
  }

  public function hug1($to, $from) {
    $message = $this->t('%from sends hugs to %to', [
      '%from' => $from,
      '%to' => $to,
    ]);

    return $message;
  }
}
