<?php
/**
 * color-swatches plugin for Craft CMS 3.x.
 *
 * Let clients choose from a predefined set of colors.
 *
 * @link      https://rias.be
 *
 * @copyright Copyright (c) 2018 Rias
 */

namespace fieldwork\colorswatches;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterGqlDirectivesEvent;
use craft\services\Gql;
use craft\services\Fields;
use fieldwork\colorswatches\fields\ColorSwatches as ColorSwatchesField;
use fieldwork\colorswatches\types\ColorSwatches as ColorSwatchesType;
use fieldwork\colorswatches\directives\FirstColor;
use fieldwork\colorswatches\directives\ColorsList;
use fieldwork\colorswatches\models\Settings;
use GraphQL\Type\Definition\Type;
use yii\base\Event;

/**
 * Class Colorswatches.
 *
 * @author    Rias
 *
 * @since     1.0.0
 */
class ColorSwatches extends Plugin
{
  // Static Properties
  // =========================================================================

  /**
   * @var ColorSwatches
   */
  public static $plugin;

  // Public Methods
  // =========================================================================

  /**
   * {@inheritdoc}
   */
  public function init()
  {
    parent::init();
    self::$plugin = $this;

    Event::on(
      Fields::class,
      Fields::EVENT_REGISTER_FIELD_TYPES,
      function ( RegisterComponentTypesEvent $event ) {
        $event->types[] = ColorSwatchesField::class;
      }
    );

    Event::on(
      Gql::class,
      Gql::EVENT_REGISTER_GQL_DIRECTIVES,
      function( RegisterGqlDirectivesEvent $event ) {
        $event->directives[] = FirstColor::class;
        $event->directives[] = ColorsList::class;
      }
    );
  }

  protected function createSettingsModel()
  {
    return new Settings();
  }
}
