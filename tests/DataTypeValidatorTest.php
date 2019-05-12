<?php declare(strict_types=1);

namespace PHPExperts\DataTypeValidator\Tests;

use Carbon\Carbon;
use function foo\func;
use PHPExperts\DataTypeValidator\DataTypeValidator;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\DataTypeValidator\IsAFuzzyDataType;
use PHPExperts\DataTypeValidator\IsAStrictDataType;
use PHPUnit\Framework\TestCase;

/** @testdox PHPExperts\DataTypeValidator\DataTypeValidator */
class DataTypeValidatorTest extends TestCase
{
    /** @var DataTypeValidator */
    private $strict;

    /** @var DataTypeValidator */
    private $fuzzy;

    public function setUp(): void
    {
        $this->strict = new DataTypeValidator(new IsAStrictDataType());
        $this->fuzzy = new DataTypeValidator(new IsAFuzzyDataType());

        parent::setUp();
    }

    private function getDataByType(string $dataType, array $dataAndTypes): array
    {
        $out = [];
        foreach ($dataAndTypes as [$type, $value]) {
            if ($type === $dataType) {
                $out[] = $value;
            }
        }

        return $out;
    }

    public function testCanBulkValidateADataArray()
    {
        $data = [
            'name'     => 'Cheyenne',
            'age'      => 22,
            'birthday' => Carbon::parse('1996-12-04 15:15:15'),
            'daysOld'  => 8194.35,
            'today'    => Carbon::now(),
            'sayHi'    => function () { return 'Hi!'; },
        ];

        $rules = [
            'name'     => 'string',
            'age'      => 'int',
            'birthday' => 'Carbon',
            'daysOld'  => 'float',
            'today'    => 'Carbon\Carbon',
            'sayHi'    => 'callable',
        ];

        self::assertTrue($this->strict->validate($data, $rules));
    }

    public function testWillReturnAnArrayOfInvalidKeysWithExplanations()
    {
        $data = [
            'name'     => 'Cheyenne',
            'age'      => '22',
            'birthday' => '1996-12-04 15:15:15',
            'daysOld'  => '8194.35',
        ];

        $rules = [
            'name'     => 'string',
            'age'      => 'int',
            'birthday' => 'Carbon',
            'daysOld'  => 'float',
        ];

        try {
            self::assertTrue($this->strict->validate($data, $rules), 'Invalid data validated :o');
            $this->fail('Invalid data did not throw an exception.');
        } catch (InvalidDataTypeException $e) {
            self::assertSame('There were 3 validation errors.', $e->getMessage());
            $reasons = $e->getReasons();
            self::assertNotEmpty($reasons);

            self::assertSame('age is not a valid int', $reasons['age']);
            self::assertSame('birthday is not a valid Carbon', $reasons['birthday']);
            self::assertSame('daysOld is not a valid float', $reasons['daysOld']);
            self::assertSame(['age', 'birthday', 'daysOld'], array_keys($reasons));
        }
    }

    public function testWillSilentlyIgnoreDataNotInTheRules()
    {
        $data = [
            'name'     => 'Cheyenne',
            'favFood'  => 'Italian',
        ];

        $rules = [
            'name'     => 'string',
        ];

        self::assertTrue($this->strict->validate($data, $rules), 'Invalid data validated :o');
    }

    public function testWillSilentlyIgnoreRulesWithNoData()
    {
        $data = [
            'name'     => 'Cheyenne',
            'favFood'  => 'Italian',
        ];

        $rules = [
            'name'     => 'string',
            'age'      => 'int',
            'birthday' => 'Carbon',
            'daysOld'  => 'float',
            'favFood'  => 'string',
        ];

        self::assertTrue($this->strict->validate($data, $rules), 'Invalid data validated :o');
    }
}
