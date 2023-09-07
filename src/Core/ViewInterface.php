<?php declare(strict_types=1);

namespace Core;

interface ViewInterface
{
    public function addParameter(string $key, mixed $value): void;

    public function display(string $template);
}