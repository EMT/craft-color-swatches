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

namespace fieldwork\colorswatches\assetbundles\colorswatchesfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Rias
 *
 * @since     1.0.0
 */
class ColorSwatchesFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->sourcePath = '@fieldwork/colorswatches/assetbundles/colorswatchesfield/dist';

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/ColorSwatches.css',
        ];

        $this->js = [
            'js/ColorSwatches.js',
        ];

        parent::init();
    }
}
