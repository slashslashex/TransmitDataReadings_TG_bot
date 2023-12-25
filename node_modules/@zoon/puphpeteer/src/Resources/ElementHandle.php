<?php

namespace Nesk\Puphpeteer\Resources;

use Nesk\Puphpeteer\Traits\AliasesEvaluationMethods;
use Nesk\Puphpeteer\Traits\AliasesSelectionMethods;

/**
 * @method \Nesk\Puphpeteer\Resources\ElementHandle|null waitForSelector(string $selector, array $options = [])
 *
 * @method-extended \Nesk\Puphpeteer\Resources\ElementHandle|null waitForSelector(string $selector, array{ visible: bool, hidden: bool, timeout: float } $options = null)
 *
 * @method \Nesk\Puphpeteer\Resources\ElementHandle|null waitForXPath(string $xpath, array $options = [])
 *
 * @method-extended \Nesk\Puphpeteer\Resources\ElementHandle|null waitForXPath(string $xpath, array{ visible: bool, hidden: bool, timeout: float } $options = null)
 *
 * @method \Nesk\Puphpeteer\Resources\ElementHandle|mixed[]|null asElement()
 *
 * @method-extended \Nesk\Puphpeteer\Resources\ElementHandle|mixed[]|null asElement()
 *
 * @method \Nesk\Puphpeteer\Resources\Frame|null contentFrame()
 *
 * @method-extended \Nesk\Puphpeteer\Resources\Frame|null contentFrame()
 *
 * @method mixed clickablePoint(mixed $offset = null)
 *
 * @method-extended mixed clickablePoint(mixed $offset = null)
 *
 * @method void hover()
 *
 * @method-extended void hover()
 *
 * @method void click(array $options = [])
 *
 * @method-extended void click(array<string, mixed> $options = null)
 *
 * @method mixed drag(mixed $target)
 *
 * @method-extended mixed drag(mixed $target)
 *
 * @method void dragEnter(mixed $data = null)
 *
 * @method-extended void dragEnter(mixed $data = null)
 *
 * @method void dragOver(mixed $data = null)
 *
 * @method-extended void dragOver(mixed $data = null)
 *
 * @method void drop(mixed $data = null)
 *
 * @method-extended void drop(mixed $data = null)
 *
 * @method void dragAndDrop(\Nesk\Puphpeteer\Resources\ElementHandle $target, array $options = [])
 *
 * @method-extended void dragAndDrop(\Nesk\Puphpeteer\Resources\ElementHandle $target, array{ delay: float } $options = null)
 *
 * @method string[] select(string ...$values)
 *
 * @method-extended string[] select(string ...$values)
 *
 * @method void uploadFile(string ...$filePaths)
 *
 * @method-extended void uploadFile(string ...$filePaths)
 *
 * @method void tap()
 *
 * @method-extended void tap()
 *
 * @method void focus()
 *
 * @method-extended void focus()
 *
 * @method void type(string $text, array $options = [])
 *
 * @method-extended void type(string $text, array{ delay: float } $options = null)
 *
 * @method void press(mixed $key, array $options = [])
 *
 * @method-extended void press(mixed $key, array<string, mixed> $options = null)
 *
 * @method mixed|null boundingBox()
 *
 * @method-extended mixed|null boundingBox()
 *
 * @method mixed|null boxModel()
 *
 * @method-extended mixed|null boxModel()
 *
 * @method string|mixed screenshot(array $options = [])
 *
 * @method-extended string|mixed screenshot(array<string, mixed> $options = null)
 *
 * @method bool isIntersectingViewport(array $options = [])
 *
 * @method-extended bool isIntersectingViewport(array{ threshold: float } $options = null)
 */
class ElementHandle extends JSHandle
{
    use AliasesEvaluationMethods;
    use AliasesSelectionMethods;
}
