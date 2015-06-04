<?php
/**
 * @contains Drupal\Test\hugs\Unit\HugTrackerTest.
 */

namespace Drupal\Tests\hugs\Unit;

use Drupal\hugs\HugTracker;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\State\StateInterface;

/**
 * Tests the Hug Tracker service.
 *
 * @group Hug
 */
class HugTrackerTest extends UnitTestCase {

  public function testAddHug() {
    // Note: The ::class syntax is PHP 5.5. You can also specify the full class
    // name as a string literal.
    $state = $this->prophesize(StateInterface::class);
    $state->set('hugs.last_recipient', 'Dries')->shouldBeCalled();

    $tracker = new HugTracker($state->reveal());
    $tracker->addHug('Dries');
  }

  public function testGetLastRecipient() {
    $state = $this->prophesize(StateInterface::class);
    $state->get('hugs.last_recipient')->willReturn('Dries');

    $tracker = new HugTracker($state->reveal());
    $this->assertEquals('Dries', $tracker->getLastRecipient());
  }

}
