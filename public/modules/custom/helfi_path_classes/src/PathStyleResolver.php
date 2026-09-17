<?php

declare(strict_types=1);

namespace Drupal\helfi_path_classes;

use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\path_alias\AliasManagerInterface;

/**
 * Resolves the style override CSS class for the currently active path.
 */
final class PathStyleResolver {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly CurrentPathStack $currentPath,
    private readonly AliasManagerInterface $aliasManager,
  ) {}

  /**
   * Gets the style override CSS class for the currently active path.
   *
   * Configured paths are matched against the path alias of the current
   * request. The alias manager resolves the alias for the active interface
   * language, so a single configured item matches every translation of the
   * given path.
   *
   * @return string|null
   *   The style override CSS class, or NULL if no override matches.
   */
  public function getStyleClassForCurrentPath(): ?string {
    $overrides = $this->configFactory->get('helfi_path_classes.settings')->get('overrides') ?? [];

    if (!$overrides) {
      return NULL;
    }

    $alias = $this->aliasManager->getAliasByPath($this->currentPath->getPath());
    $current_path = $this->normalizePath($alias);

    foreach ($overrides as $override) {
      if (empty($override['path']) || empty($override['style'])) {
        continue;
      }
      if ($this->normalizePath($override['path']) === $current_path) {
        return Html::getClass($override['style']);
      }
    }

    return NULL;
  }

  /**
   * Normalizes a path for comparison.
   *
   * @param string $path
   *   The path to normalize.
   *
   * @return string
   *   The normalized path.
   */
  private function normalizePath(string $path): string {
    $path = trim($path);
    if ($path === '' || $path === '/') {
      return '/';
    }
    return '/' . trim($path, '/');
  }

}
