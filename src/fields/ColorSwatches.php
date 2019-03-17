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

namespace rias\colorswatches\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use rias\colorswatches\assetbundles\colorswatchesfield\ColorSwatchesFieldAsset;
use rias\colorswatches\ColorSwatches as Plugin;
use rias\colorswatches\models\ColorSwatches as ColorSwatchesModel;
use yii\db\Schema;

/**
 * @author    Rias
 *
 * @since     1.0.0
 */
class ColorSwatches extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * Available options.
     *
     * @var array
     */
    public $options = [];

    /** @var bool */
    public $useConfigFile = false;

    /** @var bool */
    public $allowMultiple = false;

    /** @var string */
    public $palette = null;

    // Static Methods
    // =========================================================================

    /**
     * {@inheritdoc}
     */
    public static function displayName(): string
    {
        return Craft::t('color-swatches', 'Color Swatches');
    }

    // Public Methods
    // =========================================================================

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['options', 'each', 'rule' => ['required']],
        ]);

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value instanceof ColorSwatchesModel) {
            return $value;
        }

        return new ColorSwatchesModel($value);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        if (!$value instanceof ColorSwatchesModel) {
            $value = new ColorSwatchesModel($value);
        }

        return $value->values;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsHtml()
    {
        Craft::$app->getView()->registerAssetBundle(ColorSwatchesFieldAsset::class);

        if (count($this->options)) {
            $rows = $this->options;
        } elseif ($this->palette) {
            $rows = Plugin::$plugin->settings->palettes[$this->palette];
        } else {
            $rows = Plugin::$plugin->settings->colors;
        }

        $config = [
            'instructions' => Craft::t('color-swatches', 'Define the available colors.'),
            'id'           => 'options',
            'name'         => 'options',
            'addRowLabel'  => Craft::t('color-swatches', 'Add a color'),
            'cols'         => [
                'label' => [
                    'heading' => Craft::t('color-swatches', 'Label'),
                    'type'    => 'singleline',
                ],
                'color' => [
                    'heading' => Craft::t('color-swatches', 'Hex Colors (comma seperated)'),
                    'type'    => 'singleline',
                ],
                'default' => [
                    'heading'      => Craft::t('color-swatches', 'Default?'),
                    'type'         => 'checkbox',
                    'class'        => 'thin',
                ],
            ],
            'rows' => $rows,
        ];

        $paletteOptions = [];
        $paletteOptions[] = [
                'label' => null,
                'value' => null,
            ];
        foreach (array_keys((array) Plugin::$plugin->settings->palettes) as $palette) {
            $paletteOptions[] = [
                'label' => $palette,
                'value' => $palette,
            ];
        }

        $currentPalette = $this->palette;

        $paletteOptions = [];
        $paletteOptions[] = [
            'label' => 'Color config',
            'value' => null,
        ];
        foreach (array_keys((array) Plugin::$plugin->settings->palettes) as $palette) {
            $paletteOptions[] = [
                'label' => $palette,
                'value' => $palette,
            ];
        }

        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'color-swatches/settings',
            [
                'field'             => $this,
                'config'            => $config,
                'configOptions'     => Plugin::$plugin->settings->colors,
                'paletteOptions'    => $paletteOptions,
                'palettes'          => Plugin::$plugin->settings->palettes,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(ColorSwatchesFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        Craft::$app->getView()->registerJs("new ColorSelectInput('{$namespacedId}');");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'color-swatches/input',
            [
                'name'         => $this->handle,
                'fieldValue'   => $value,
                'field'        => $this,
                'id'           => $id,
                'namespacedId' => $namespacedId,
                'configOptions'=> Plugin::$plugin->settings->colors,
                'palettes'     => Plugin::$plugin->settings->palettes,
            ]
        );
    }
}
