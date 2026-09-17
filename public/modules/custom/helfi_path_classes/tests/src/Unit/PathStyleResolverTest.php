<?php

declare(strict_types=1);

namespace Drupal\Tests\helfi_path_classes\Unit;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\helfi_path_classes\PathStyleResolver;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Unit tests for PathStyleResolver.
 */
#[Group('helfi_path_classes')]
#[CoversClass(PathStyleResolver::class)]
class PathStyleResolverTest extends UnitTestCase {

  /**
   * Builds a resolver with the given overrides, current path and alias.
   *
   * @param array<int, array<string, mixed>> $overrides
   *   The configured style overrides.
   * @param string $currentPath
   *   The current system path.
   * @param string $alias
   *   The alias resolved for the current path.
   *
   * @return \Drupal\helfi_path_classes\PathStyleResolver
   *   Resolver under test.
   */
  private function createResolver(
    array $overrides,
    string $currentPath = '/node/1',
    string $alias = '/node/1',
  ): PathStyleResolver {
    $config = $this->createMock(ImmutableConfig::class);
    $config->method('get')
      ->with('overrides')
      ->willReturn($overrides);

    $configFactory = $this->createMock(ConfigFactoryInterface::class);
    $configFactory->method('get')
      ->with('helfi_path_classes.settings')
      ->willReturn($config);

    $currentPathStack = $this->createMock(CurrentPathStack::class);
    $currentPathStack->method('getPath')->willReturn($currentPath);

    $aliasManager = $this->createMock(AliasManagerInterface::class);
    $aliasManager->method('getAliasByPath')
      ->with($currentPath)
      ->willReturn($alias);

    return new PathStyleResolver($configFactory, $currentPathStack, $aliasManager);
  }

  /**
   * Returns NULL when no overrides are configured.
   */
  public function testReturnsNullWithoutOverrides(): void {
    $resolver = $this->createResolver([]);
    $this->assertNull($resolver->getStyleClassForCurrentPath());
  }

  /**
   * Returns NULL when the current path does not match any override.
   */
  public function testReturnsNullWhenNoMatch(): void {
    $resolver = $this->createResolver(
      [['path' => '/about-us', 'style' => 'style-1', 'weight' => 0]],
      alias: '/other-page',
    );
    $this->assertNull($resolver->getStyleClassForCurrentPath());
  }

  /**
   * Returns the style class of the first matching override.
   */
  public function testReturnsFirstMatchingStyle(): void {
    $resolver = $this->createResolver(
      [
        ['path' => '/about-us', 'style' => 'style-1', 'weight' => 0],
        ['path' => '/about-us', 'style' => 'style-2', 'weight' => 1],
      ],
      alias: '/about-us',
    );
    $this->assertSame('style-1', $resolver->getStyleClassForCurrentPath());
  }

  /**
   * Matches paths regardless of a trailing slash.
   */
  public function testNormalizesTrailingSlash(): void {
    $resolver = $this->createResolver(
      [['path' => '/about-us/', 'style' => 'style-2', 'weight' => 0]],
      alias: '/about-us',
    );
    $this->assertSame('style-2', $resolver->getStyleClassForCurrentPath());
  }

}
