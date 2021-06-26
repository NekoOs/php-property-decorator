<?php

namespace NekoOs\Decorator;

use Jasny\PhpdocParser\Tag\PhpDocumentor\VarTag;

class GetterTag extends VarTag
{
    public function process(array $notations, string $value): array
    {
        $notations = parent::process($notations, $value);

        $closure = null;
        if ($value && preg_match('/{read\s+(\w+)}/', $value, $matches)) {
            $value = preg_replace('/\s*{read\s+(\w+)}\s*/', ' ', $value);
            $closure = $matches[1];
        }

        $notations[$this->name]['description'] = $value;
        if ($closure) {
            $notations[$this->name]['getter'] = $closure;
        }

        return $notations;
    }
}