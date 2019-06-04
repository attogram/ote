<?php
/**
 * Open Translation Engine v2
 * User
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 */
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

class User
{
    /** @var Repository - Translation Database access */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getId(): int
    {
        return 1;
    }

    public function getName(): string
    {
        return 'v2Test';
    }

    public function isAdmin(): bool
    {
        return true;
    }
}