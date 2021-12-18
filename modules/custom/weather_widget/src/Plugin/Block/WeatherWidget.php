<?php

namespace Drupal\weather_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Weather Widget Block.
 *
 * @Block(
 *   id = "weather_widget_block",
 *   admin_label = @Translation("Weather Widget Block"),
 * )
 */
class WeatherWidget extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $key = \Drupal::config('weatherwidget.settings')->get('weatherapi_key');
    if ($key) {
      $node = \Drupal::routeMatch()->getParameter('node');
      if ($node && $node->bundle() == 'city') {
        $city = $node->field_city->value;
        $countrycode = $node->field_country->value;
        $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city . ',' . $countrycode. '&units=metric&appid=' . $key;
        $data = json_decode(file_get_contents($url));
        if ($data->cod == 200) {
          return [
            '#theme' => 'weather_widget',
            '#markup' => $this->t('Weather Widget block!'),
            '#temp' => $data->main->temp,
            '#city' => $city,
            '#image' => $data->weather[0]->icon,
            '#attached' => [
              'library' => 'weather_widget/styling',
            ],
          ];
        }
        else {
          return;
        }
      }
    }
    else {
      \Drupal::messenger()->addError(t('Api Key not found or invalid'));
      return;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['weather_widget'] = $form_state->getValue('weather_widget');
  }

}
