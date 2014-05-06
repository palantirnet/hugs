<?php

namespace Drupal\hugs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hugs\HugTracker;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HugsController extends ControllerBase {

  /**
   * @var \Drupal\hugs\HugTracker
   */
  protected $hugTracker;

  public function __construct(HugTracker $tracker) {
    $this->hugTracker = $tracker;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('hugs.hug_tracker'));
  }

  public function hug($to, $from, $count) {
    $this->hugTracker->addHug($to);
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

  public function hug3($to, $from, $count) {
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
