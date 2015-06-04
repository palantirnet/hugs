<?php
/**
 * @contains Drupal\Test\hugs\Functional\HugPageTest.
 */

namespace Drupal\Tests\hugs\Functional;

use Drupal\simpletest\BrowserTestBase;

/**
 * Tests the Hug page.
 *
 * @group Hug
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class HugPageTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * (We need node module because it defines the 'access content' permission.)
   *
   * @var array
   */
  public static $modules = ['node', 'hugs'];

  /**
   * Tests the main hug page with default count.
   *
   * @dataProvider hugAttemptProvider
   */
  public function testHugPage($from = NULL, $to = NULL, $count = NULL, $expectedStatus = 200, $expectedString = '') {
    $account = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($account);

    $pathFragments[] = 'hug';
    foreach ([$from, $to, $count] as $elm) {
      if (!is_null($elm)) {
        $pathFragments[] = $elm;
      }
    }

    $path = '/' . implode('/', $pathFragments);

    // Visit a Drupal page that requires login.
    $this->drupalGet($path);
    $this->assertSession()->statusCodeEquals($expectedStatus);

    // If we got back a valid page, and expected to, then check for the right message.
    if ($expectedStatus == 200) {
      $this->assertSession()->pageTextContains($expectedString);
    }
  }

  /**
   * @see testHugPage
   */
  public function hugAttemptProvider() {
    return [
      ['Larry', 'Dries', NULL, 200, 'Larry hugs Dries 3 times'],
      ['Larry', 'Dries', 2, 200, 'Larry hugs Dries 2 times'],
      ['Larry', 'Dries', 1, 200, 'Larry hugs Dries 1 time'],
      ['Larry', NULL, NULL, 404],
      [NULL, NULL, NULL, 404],
      ['Larry', 'Dries', 'many', 404],
    ];
  }

}
