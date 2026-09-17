<?php

declare(strict_types=1);

namespace Drupal\helfi_path_classes;

/**
 * Provides the style override options available in the settings form.
 *
 * Add new style overrides to self::OPTIONS. The array key is used as the
 * CSS class added to the html body and must be a valid CSS identifier.
 */
final class StyleOverrideOptions {

  /**
   * The available style overrides, keyed by machine name.
   *
   * @phpstan-var array<string, string>
   */
  private const OPTIONS = [
    'style-1' => 'Style 1',
    'style-2' => 'Style 2',
  ];

  /**
   * Gets the available style override options.
   *
   * @return array<string, string>
   *   The style override human readable labels, keyed by machine name.
   */
  public function getOptions(): array {
    return self::OPTIONS;
  }

}
