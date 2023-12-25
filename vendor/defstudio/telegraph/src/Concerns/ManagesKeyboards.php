<?php

/** @noinspection PhpUnused */

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\KeyboardException;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait ManagesKeyboards
{
    /**
     * @param array<array-key, array<array-key, array{text: string, url?: string, callback_data?: string, web_app?: string[], login_url?: string[]}>>|Keyboard|callable(Keyboard):Keyboard $keyboard
     */
    public function keyboard(callable|array|Keyboard $keyboard): Telegraph
    {
        $telegraph = clone $this;

        if (is_callable($keyboard)) {
            $keyboard = $keyboard(Keyboard::make());
        }

        if (is_array($keyboard)) {
            $keyboard = Keyboard::fromArray($keyboard);
        }

        data_set($telegraph->data, 'reply_markup.inline_keyboard', $keyboard->toArray());

        return $telegraph;
    }

    /**
     * @param array<array-key, array<array-key, array{text: string, request_contact?: bool, request_location?: bool, request_poll?: string[], web_app?: string[]}>>|ReplyKeyboard|callable(ReplyKeyboard):ReplyKeyboard $keyboard
     */
    public function replyKeyboard(callable|array|ReplyKeyboard $keyboard): Telegraph
    {
        $telegraph = clone $this;

        if (is_callable($keyboard)) {
            $keyboard = $keyboard(ReplyKeyboard::make());
        }

        if (is_array($keyboard)) {
            $keyboard = ReplyKeyboard::fromArray($keyboard);
        }

        data_set($telegraph->data, 'reply_markup.keyboard', $keyboard->toArray());

        foreach ($keyboard->options() as $option_key => $option_value) {
            data_set($telegraph->data, "reply_markup.$option_key", $option_value);
        }

        return $telegraph;
    }

    public function forceReply(string $placeholder = '', bool $selective = false): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['reply_markup'] = ['force_reply' => true, 'selective' => $selective];

        if ($placeholder !== '') {
            if (strlen($placeholder) > 64) {
                throw KeyboardException::maxPlaceholderLengthExcedeed($placeholder);
            }

            $telegraph->data['reply_markup']['input_field_placeholder'] = $placeholder;
        }

        return $telegraph;
    }

    public function removeReplyKeyboard(bool $selective = false): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['reply_markup'] = [
            'remove_keyboard' => true,
            'selective' => $selective,
        ];

        return $telegraph;
    }

    /**
     * @param Keyboard|callable(Keyboard):Keyboard $newKeyboard
     */
    public function replaceKeyboard(int $messageId, Keyboard|callable $newKeyboard): Telegraph
    {
        $telegraph = clone $this;

        if (is_callable($newKeyboard)) {
            $newKeyboard = $newKeyboard(Keyboard::make());
        }

        if ($newKeyboard->isEmpty()) {
            $replyMarkup = '';
        } else {
            $replyMarkup = ['inline_keyboard' => $newKeyboard->toArray()];
        }

        $telegraph->endpoint = self::ENDPOINT_REPLACE_KEYBOARD;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup,
        ];

        return $telegraph;
    }

    public function deleteKeyboard(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        return $telegraph->replaceKeyboard($messageId, Keyboard::make());
    }
}
