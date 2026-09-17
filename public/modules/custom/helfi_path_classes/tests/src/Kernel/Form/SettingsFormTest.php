<?php

declare(strict_types=1);

namespace Drupal\Tests\helfi_path_classes\Kernel\Form;

use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\helfi_path_classes\Form\SettingsForm;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the style overrides by path settings form.
 */
#[RunTestsInSeparateProcesses]
#[Group('helfi_path_classes')]
class SettingsFormTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'path_alias',
    'helfi_path_classes',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['helfi_path_classes']);
  }

  /**
   * Tests that submitting the form persists the overrides to config.
   */
  public function testConfigForm(): void {
    $formBuilder = $this->container->get(FormBuilderInterface::class);

    $form_state = new FormState();
    $form_state->setValues([
      'overrides' => [
        'table' => [
          0 => ['path' => '/about-us', 'style' => 'style-2', 'weight' => 1],
          1 => ['path' => '/front-page', 'style' => 'style-1', 'weight' => 0],
          2 => ['path' => '', 'style' => '', 'weight' => 2],
        ],
      ],
    ]);

    $formBuilder->submitForm(SettingsForm::class, $form_state);

    $this->assertEmpty($form_state->getErrors());

    $overrides = $this->config('helfi_path_classes.settings')->get('overrides');
    $this->assertCount(2, $overrides);
    $this->assertSame('/front-page', $overrides[0]['path']);
    $this->assertSame('style-1', $overrides[0]['style']);
    $this->assertSame(0, $overrides[0]['weight']);
    $this->assertSame('/about-us', $overrides[1]['path']);
    $this->assertSame('style-2', $overrides[1]['style']);
    $this->assertSame(1, $overrides[1]['weight']);
  }

  /**
   * Tests that a path without a selected style override is rejected.
   */
  public function testConfigFormRequiresStyle(): void {
    $formBuilder = $this->container->get(FormBuilderInterface::class);

    $form_state = new FormState();
    $form_state->setValues([
      'overrides' => [
        'table' => [
          0 => ['path' => '/about-us', 'style' => '', 'weight' => 0],
        ],
      ],
    ]);

    $formBuilder->submitForm(SettingsForm::class, $form_state);

    $this->assertNotEmpty($form_state->getErrors());
  }

}
