<?php

declare(strict_types=1);

namespace Drupal\helfi_path_classes\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\helfi_path_classes\StyleOverrideOptions;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configures the style overrides added to the html body for specific paths.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * The wrapper id used by the overrides table ajax callbacks.
   */
  private const WRAPPER_ID = 'helfi-path-classes-overrides-wrapper';

  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typed_config_manager,
    protected readonly StyleOverrideOptions $styleOverrideOptions,
  ) {
    parent::__construct($config_factory, $typed_config_manager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get(StyleOverrideOptions::class),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'helfi_path_classes_settings_form';
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-return array<string>
   */
  protected function getEditableConfigNames(): array {
    return ['helfi_path_classes.settings'];
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-param array<string, mixed> $form
   * @phpstan-return array<string, mixed>
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    if ($form_state->get('overrides') === NULL) {
      $overrides = $this->config('helfi_path_classes.settings')->get('overrides') ?? [];
      $form_state->set('overrides', array_values($overrides));
    }

    $form['overrides'] = [
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => ['id' => self::WRAPPER_ID],
    ];

    $form['overrides']['table'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Path'),
        $this->t('Style override'),
        $this->t('Weight'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('There are no style overrides yet.'),
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'helfi-path-classes-override-weight',
        ],
      ],
    ];

    $options = $this->styleOverrideOptions->getOptions();
    foreach ($form_state->get('overrides') as $key => $item) {
      $form['overrides']['table'][$key] = $this->buildRow((int) $key, $item, $options);
    }

    $form['overrides']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add item'),
      '#submit' => ['::addItemSubmit'],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => self::WRAPPER_ID,
      ],
      '#limit_validation_errors' => [],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Builds a single style override table row.
   *
   * @param int $key
   *   The row key.
   * @param array<string, mixed> $item
   *   The default values for the row.
   * @param array<string, string> $options
   *   The available style override options.
   *
   * @return array<string, mixed>
   *   The row render array.
   */
  private function buildRow(int $key, array $item, array $options): array {
    $row = [
      '#attributes' => ['class' => ['draggable']],
      '#weight' => $item['weight'] ?? 0,
    ];

    $row['path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Path'),
      '#title_display' => 'invisible',
      '#default_value' => $item['path'] ?? '',
      '#size' => 40,
      '#maxlength' => 255,
      '#placeholder' => '/example-path',
    ];

    $row['style'] = [
      '#type' => 'select',
      '#title' => $this->t('Style override'),
      '#title_display' => 'invisible',
      '#options' => $options,
      '#empty_option' => $this->t('- Select -'),
      '#default_value' => $item['style'] ?? '',
    ];

    $row['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#title_display' => 'invisible',
      '#default_value' => $item['weight'] ?? 0,
      '#delta' => 50,
      '#attributes' => ['class' => ['helfi-path-classes-override-weight']],
    ];

    $row['operations'] = [
      '#type' => 'submit',
      '#value' => $this->t('Remove'),
      '#name' => 'helfi_path_classes_remove_' . $key,
      '#row_key' => $key,
      '#submit' => ['::removeItemSubmit'],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => self::WRAPPER_ID,
      ],
      '#limit_validation_errors' => [],
    ];

    return $row;
  }

  /**
   * Ajax callback returning the rebuilt overrides table.
   *
   * @phpstan-param array<string, mixed> $form
   *
   * @return array<string, mixed>
   *   The overrides render array.
   */
  public function ajaxCallback(array &$form, FormStateInterface $form_state): array {
    return $form['overrides'];
  }

  /**
   * Submit handler for the "Add item" button.
   *
   * @phpstan-param array<string, mixed> $form
   */
  public function addItemSubmit(array &$form, FormStateInterface $form_state): void {
    $overrides = $form_state->get('overrides') ?? [];
    $overrides[] = ['path' => '', 'style' => '', 'weight' => count($overrides)];
    $form_state->set('overrides', $overrides);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the per-row "Remove" button.
   *
   * @phpstan-param array<string, mixed> $form
   */
  public function removeItemSubmit(array &$form, FormStateInterface $form_state): void {
    $key = $form_state->getTriggeringElement()['#row_key'] ?? NULL;
    $overrides = $form_state->get('overrides') ?? [];
    if ($key !== NULL) {
      unset($overrides[$key]);
    }
    $form_state->set('overrides', array_values($overrides));
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-param array<string, mixed> $form
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $trigger = $form_state->getTriggeringElement();
    if (isset($trigger['#limit_validation_errors']) && $trigger['#limit_validation_errors'] === []) {
      // The add/remove buttons handle their own logic and skip validation.
      return;
    }

    $table = $form_state->getValue(['overrides', 'table']) ?? [];
    foreach ($table as $key => $row) {
      $path = trim((string) ($row['path'] ?? ''));
      $style = (string) ($row['style'] ?? '');

      if ($path === '' && $style === '') {
        // Empty rows are ignored on submit.
        continue;
      }
      if ($path === '') {
        $form_state->setErrorByName("overrides][table][$key][path", $this->t('Path is required.'));
      }
      if ($style === '') {
        $form_state->setErrorByName("overrides][table][$key][style", $this->t('Style override is required.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-param array<string, mixed> $form
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // The add/remove buttons define their own #submit handlers above and
    // never reach this method.
    $table = $form_state->getValue(['overrides', 'table']) ?? [];
    $overrides = [];
    foreach ($table as $row) {
      $path = trim((string) ($row['path'] ?? ''));
      $style = (string) ($row['style'] ?? '');
      if ($path === '' || $style === '') {
        continue;
      }
      $overrides[] = [
        'path' => $path,
        'style' => $style,
        'weight' => (int) ($row['weight'] ?? 0),
      ];
    }

    usort($overrides, static fn(array $a, array $b): int => $a['weight'] <=> $b['weight']);
    foreach ($overrides as $delta => &$item) {
      $item['weight'] = $delta;
    }
    unset($item);

    $this->config('helfi_path_classes.settings')
      ->set('overrides', $overrides)
      ->save();

    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
  }

}
