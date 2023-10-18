<?php declare(strict_types=1);

namespace App\Core;

class Redirect
{
    private RedirectRecordings $redirectRecordings;
    public function __construct(RedirectRecordings $redirectRecordings)
    {
        $this->redirectRecordings = $redirectRecordings;
    }

    public function redirectTo(string $url): void
    {
        $this->redirectRecordings->sendUrl($url);
        header('Location: ' . $url);
    }
}