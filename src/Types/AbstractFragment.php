<?php

declare(strict_types=1);

namespace Magento2GraphQLClient\Types;

use Magento2GraphQLClient\Types\FilterableFragmentInterface;

abstract class AbstractFragment
{
    public const __TYPENAME = '__typename';
    public const SIMPLE_TYPE = 'simple';

    /** 
     * @var array<string> $validAttributes
     */
    protected array $validAttributes = [];

    protected array $attributes = [];

    protected array $variants = [];

    public function __construct(
        array $attributes = []
    ) {
        foreach($attributes as $k => $v) {
            if(is_numeric($k)){
                $attribute = $v;
                $typeName = self::SIMPLE_TYPE;
                $type = self::SIMPLE_TYPE;
            }else{
                $attribute = $k;
                $typeName = get_class($v);
                $type = $v;
            }

            if(!array_key_exists($attribute, $this->validAttributes)) {
                throw new \InvalidArgumentException("Invalid attribute: $attribute, for class ".get_class($this).", valid attributes are: ".implode(', ', array_keys($this->validAttributes)));
            }

            $expectedType = $this->validAttributes[$attribute];
            if($expectedType != $typeName) {
                throw new \InvalidArgumentException("Invalid type for attribute $attribute: expected $expectedType, got $typeName");
            }

            $this->attributes[$attribute] = $type;
        }
    }

    public static function withAttributes(array $attributes): self
    {
        return new static($attributes);
    }

    public function on(string $typeName, self $fragment): self
    {
        $this->variants[$typeName] = $fragment;
        return $this;
    }

    public function __toString(): string
    {
        $attr = [];
        foreach($this->attributes as $attribute => $type) {
            if($type === self::SIMPLE_TYPE) {
                $attr[] = $attribute.PHP_EOL;
            } else {
                if($type instanceof FilterableFragmentInterface && $type->hasFilter()) {
                    $attr[] = sprintf('%s(filters: {%s}) {'.PHP_EOL.'%s'.PHP_EOL.'}', $attribute, $type->getFilter()->__toString(), $type->__toString());
                }else{
                    $attr[] = sprintf('%s {'.PHP_EOL.'%s'.PHP_EOL.'}', $attribute, $type->__toString());
                }
                
            }
        }
        foreach($this->variants as $typeName => $fragment) {
            $attr[] = sprintf('... on %s {'.PHP_EOL.'%s'.PHP_EOL.'}', $typeName, $fragment->__toString());
        }
        return implode(' ', $attr);
    }
}