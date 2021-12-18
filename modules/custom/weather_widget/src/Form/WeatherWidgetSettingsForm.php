<?php

namespace Drupal\weather_widget\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for Weather Widget Settings api key.
 */
class WeatherWidgetSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'weatherwidget.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_widget_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['weatherapi_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter API Key'),
      '#default_value' => $config->get('weatherapi_key'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('weatherapi_key', $form_state->getValue('weatherapi_key'))->save();

    parent::submitForm($form, $form_state);
  }

}
