<?php
class DoubleTestCase implements PHPUnit_Framework_Test
{
    protected $testCase;

    public function __construct(PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    #[ReturnTypeWillChange]
    public function count()
    {
        return 2;
    }

    public function run($result = NULL)
    {
        if ($result !== NULL && !$result instanceof PHPUnit_Framework_TestResult) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'PHPUnit_Framework_TestResult');
        }

        $result->startTest($this);

        $this->testCase->runBare();
        $this->testCase->runBare();

        $result->endTest($this, 0);
    }
}
