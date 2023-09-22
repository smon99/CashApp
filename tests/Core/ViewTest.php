<?php declare(strict_types=1);

namespace Test\Core;

use PHPUnit\Framework\TestCase;
use App\Core\View;

class ViewTest extends TestCase
{
    public function testDisplay(): void
    {
        $templatePath = __DIR__ . '/ViewTest';
        $view = new View($templatePath);
        $view->addParameter('success', 'Testing');

        ob_start();
        $view->display('test.twig');
        $output = ob_get_clean();

        $this->assertStringContainsString('No resting only', $output);
    }
}