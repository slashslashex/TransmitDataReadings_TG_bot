<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class ChatMember implements Arrayable
{
    public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';

    private string $status;
    private User $user;
    private bool $isAnonymous;

    private function __construct()
    {
    }

    /**
     * @param array{status:string, user:array<string, mixed>, is_anonymous?:bool} $data
     */
    public static function fromArray(array $data): ChatMember
    {
        $member = new self();

        $member->status = $data['status'];

        /* @phpstan-ignore-next-line */
        $member->user = User::fromArray($data['user']);

        $member->isAnonymous = $data['is_anonymous'] ?? false;

        return $member;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status,
            'user' => $this->user->toArray(),
            'is_anonymous' => $this->isAnonymous,
        ]);
    }
}
