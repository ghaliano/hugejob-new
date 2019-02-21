<?php
namespace App\Tests\Entity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CalculatorTest extends KernelTestCase
{
    private $calculatorService;

    public function setUp() {
        self::bootKernel();
        $this->calculatorService = self::$container
            ->get('calculator');
    }

    public function testDivision()
    {
        $this->assertEquals(
            2,
            $this->calculatorService->divide(6, 3)
        );
        $this->assertEquals(
            false,
            $this->calculatorService->divide(6, 0)
        );
    }
}