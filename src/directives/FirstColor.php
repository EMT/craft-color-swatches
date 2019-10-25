<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */
namespace fieldwork\colorswatches\directives;

use craft\gql\base\Directive;
use craft\gql\GqlEntityRegistry;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use GraphQL\Type\Definition\FieldArgument;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
/**
 * Markdown GraphQL Directive
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.3.0.1
 */
class FirstColor extends Directive
{
    // const DEFAULT_FLAVOR = null;
    /**
     * @inheritdoc
     */
    public static function create(): GqlDirective
    {
        if ($type = GqlEntityRegistry::getEntity(self::class)) {
            return $type;
        }
        $type = GqlEntityRegistry::createEntity(static::name(), new self([
            'name' => static::name(),
            'locations' => [
                DirectiveLocation::FIELD,
            ],
            'args' => [],
            'description' => 'Returns the first color from a ColorSwatches field.',
        ]));
        return $type;
    }
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
      return 'firstColor';
    }
    /**
     * @inheritdoc
     */
    public static function apply($source, $value, array $arguments, ResolveInfo $resolveInfo)
    {
      return $value->colors() && count( $value->colors() ) ? trim( $value->colors()[0] ) : '';
    }
}