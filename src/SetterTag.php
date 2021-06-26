<?php

namespace NekoOs\Decorator;

use Jasny\PhpdocParser\Tag\PhpDocumentor\VarTag;

class SetterTag extends VarTag
{
    public function process(array $notations, string $value): array
    {
        $notations = parent::process($notations, $value);

        $closure = null;

        if ($value && preg_match('/{write\s+(\w+)}/', $value, $matches)) {
            $value = preg_replace('/\s*{write\s+(\w+)}\s*/', ' ', $value);
            $closure = $matches[1];
        }

        $notations[$this->name]['description'] = $value;
        if ($closure) {
            $notations[$this->name]['setter'] = $closure;
        }

        return $notations;
    }
}