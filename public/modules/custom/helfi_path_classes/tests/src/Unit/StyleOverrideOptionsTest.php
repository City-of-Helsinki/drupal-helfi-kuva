<?php

declare(strict_types=1);

namespace Drupal\Tests\helfi_path_classes\Unit;

use Drupal\helfi_path_classes\StyleOverrideOptions;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Unit tests for StyleOverrideOptions.
 */
#[Group('helfi_path_classes')]
#[CoversClass(StyleOverrideOptions::class)]
class StyleOverrideOptionsTest extends UnitTestCase {

  /**
   * Tests that the example styles are available as options.
   */
  public function testGetOptions(): void {
    $options = (new StyleOverrideOptions())->getOptions();

    $this->assertSame([
      'style-1' => 'Style 1',
      'style-2' => 'Style 2',
    ], $options);
  }

}
