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

namespace rias\colorswatches;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use rias\colorswatches\fields\ColorSwatches as ColorSwatchesField;
use rias\colorswatches\models\Settings;
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
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ColorSwatchesField::class;
            }
        );

        Event::on(
          ColorSwatchesField::class,
          'craftQlGetFieldSchema',
          function ( \markhuot\CraftQL\Events\GetFieldSchema $event ) {
            $event->handled = true;

            $event->schema
              ->addStringField( $event->sender )
              ->lists()
              ->resolve( function( $root, $args ) {
                $colors = [];

                foreach ( $root->colors->values as $color ) {
                  $colors[] = $color->color;
                }

                return $colors;
              } );
          }
        );
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }
}
