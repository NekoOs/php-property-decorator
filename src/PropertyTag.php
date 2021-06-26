<?php

namespace NekoOs\Decorator;

use Jasny\PhpdocParser\Tag\PhpDocumentor\VarTag;

class PropertyTag extends VarTag
{
    public function process(array $notations, string $value): array
    {
        $getter = (new GetterTag('property'))->process($notations, $value);
        $setter = (new SetterTag('property'))->process($notations, $getter['property']['description'] ?? null);
        $master = parent::process($notations, $setter['property']['description']);

        $response[$this->name] = array_merge(
            $getter[$this->name]
            , $setter[$this->name]
            , $master[$this->name]
        );

        return $response;
    }
}