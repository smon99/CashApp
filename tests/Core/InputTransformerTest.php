<?php declare(strict_types=1);

namespace Core;

use App\Core\InputTransformer;
use PHPUnit\Framework\TestCase;

class InputTransformerTest extends TestCase
{
    private InputTransformer $inputTransformer;

    protected function setUp(): void
    {
        $this->inputTransformer = new InputTransformer();
    }

    public function testTransformInput(): void
    {
        $input = '1';
        $output = $this->inputTransformer->transformInput($input);

        self::assertSame(1.00, $output);
    }
}