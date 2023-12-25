<?php

namespace Nesk\Puphpeteer\Traits;

use Nesk\Puphpeteer\Resources\JSHandle;
use Nesk\Rialto\Data\JsFunction;

/**
 * @method bool|int|float|string|array|null querySelectorEval(string $selector, JsFunction $pageFunction, bool|int|float|string|array|JSHandle|null ...$args)
 * @method bool|int|float|string|array|null querySelectorAllEval(string $selector, JsFunction $pageFunction, bool|int|float|string|array|JSHandle|null ...$args)
 */
trait AliasesEvaluationMethods
{
    public function querySelectorEval(...$arguments)
    {
        return $this->__call('$eval', $arguments);
    }

    public function querySelectorAllEval(...$arguments)
    {
        return $this->__call('$$eval', $arguments);
    }
}
