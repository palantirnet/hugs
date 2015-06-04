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
   *
   * Note: dataProvider methods in PHPUnit are super-duper-useful and can
   * greatly reduce test maintenance.  However, in BrowserTestBase each provider
   * line below will result in its own isolated test, and therefore isolated
   * install of Drupal. Beware of the performance impact of too many browser
   * tests.
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

    $this->drupalGet('/admin/config/system/hugs');
    $this->assertSession()->statusCodeEquals(200);

    // This is the fancier way of checking and manipulating the result.
    // The $session_checks object is pure Mink, no Drupal.
    $session_checks = $this->assertSession();
    $session_checks->statusCodeEquals(200);
    $session_checks->elementExists('css', 'form#hug-config');
    $session_checks->fieldExists('default_count');

    // We could use the $page object to "click" links, and do anything else
    // supported by Mink: http://mink.behat.org/en/latest/guides/manipulating-pages.html
    // $page = $this->getSession()->getPage();

    // Now submit the form.
    $edit = ['default_count' => 5];
    $this->submitForm($edit, 'Save configuration', 'hug-config');

    // Sadly we have to hit the container directly in order to verify that
    // the data was saved to configuration. Ah well.
    $config_factory = $this->container->get('config.factory');
    $value = $config_factory->get('hugs.settings')->get('default_count');
    $this->assertSame(5, $value);
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
