<?php declare(strict_types=1);

namespace Test\Core;

use PHPUnit\Framework\TestCase;
use App\Core\View;

class ViewTest extends TestCase
{
    public function testDisplay()
    {
        $templatePath = __DIR__ . '/temp_templates';
        mkdir($templatePath);
        file_put_contents($templatePath . '/test_template.twig', 'Hello, {{ name }}!');

        $view = new View($templatePath);

        $view->setTemplate('test_template.twig');
        $view->addParameter('name', 'John');

        ob_start();
        $view->display();
        $output = ob_get_clean();

        $this->assertSame('Hello, John!', $output);

        rmdir($templatePath);
    }

    public function testGetParameters()
    {
        $templatePath = __DIR__ . '/temp_templates';
        mkdir($templatePath);

        $view = new View($templatePath);
        $view->addParameter('param1', 'value1');
        $view->addParameter('param2', 'value2');

        $parameters = $view->getParameters();

        $this->assertIsArray($parameters);
        $this->assertCount(2, $parameters);
        $this->assertArrayHasKey('param1', $parameters);
        $this->assertArrayHasKey('param2', $parameters);
        $this->assertSame('value1', $parameters['param1']);
        $this->assertSame('value2', $parameters['param2']);

        rmdir($templatePath);
    }

    public function testGetTpl()
    {
        $templatePath = __DIR__ . '/temp_templates';
        mkdir($templatePath);

        $view = new View($templatePath);
        $view->setTemplate('test_template.twig');

        $this->assertSame('test_template.twig', $view->getTpl());

        rmdir($templatePath);
    }
}