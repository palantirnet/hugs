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
 *
 * // These two tags are apparently required to make BrowserTests run correctly.
 *
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

  /**
   * Verify that a user with the right permission can access the config form.
   */
  public function testAdminFormAccess() {
    $account = $this->drupalCreateUser(['configure_hugs']);
    $this->drupalLogin($account);

    // Visit a Drupal page that requires login.
    $this->drupalGet('/admin/config/system/hugs');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Verify that a user without the right permission cannot access the form.
   */
  public function testAdminFormNoAccess() {
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);

    // Without the right permission, this should 403.
    $this->drupalGet('/admin/config/system/hugs');
    $this->assertSession()->statusCodeEquals(403);
  }

}
