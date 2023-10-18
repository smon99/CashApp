<?php declare(strict_types=1);

namespace Test\Core;

use App\Core\Redirect;
use App\Core\RedirectRecordings;

use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    public function testRedirectTo(): void
    {
        $recordedUrl = new RedirectRecordings();
        $redirect = new Redirect($recordedUrl);
        $redirect->redirectTo('hi');
        self::assertSame('hi', $recordedUrl->recordedUrl[0]);
    }
}