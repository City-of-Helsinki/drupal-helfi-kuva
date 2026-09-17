<?php

declare(strict_types=1);

namespace Drupal\helfi_path_classes\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\helfi_path_classes\PathStyleResolver;

/**
 * Adds the configured style override CSS class to the html body.
 */
final readonly class PathClassesHook {

  public function __construct(
    private PathStyleResolver $pathStyleResolver,
  ) {}

  /**
   * Implements hook_preprocess_HOOK() for html templates.
   *
   * @param array<string, mixed> $variables
   *   Template variables.
   */
  #[Hook('preprocess_html')]
  public function preprocessHtml(array &$variables): void {
    $class = $this->pathStyleResolver->getStyleClassForCurrentPath();

    if ($class === NULL) {
      return;
    }

    $variables['attributes']['class'][] = $class;
  }

}
