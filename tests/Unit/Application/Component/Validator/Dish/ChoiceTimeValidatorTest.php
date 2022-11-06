<?php
declare(strict_types=1);

namespace Unit\Application\Component\Validator\Dish;


use DateTime;
use Meals\Application\Component\Validator\Dish\ChoiceTimeValidator;
use Meals\Application\Component\Validator\Exception\WrongTimeException;
use PHPUnit\Framework\TestCase;

class ChoiceTimeValidatorTest extends TestCase
{
    /**
     * @dataProvider successfulDataProvider
     * @param DateTime[] $dateTimes
     */
    public function testSuccessful(array $dateTimes): void
    {
        foreach ($dateTimes as $dateTime) {
            $validator = new ChoiceTimeValidator();
            verify($validator->validate($dateTime))->null();
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param DateTime[] $dateTimes
     */
    public function testFail(array $dateTimes): void
    {
        foreach ($dateTimes as $dateTime) {
            $validator = new ChoiceTimeValidator();

            try {
                $validator->validate($dateTime);
                $this->fail('AccessDeniedException was not thrown');
            } catch (WrongTimeException $e) {
                $this->assertEmpty($e->getMessage());
            }
        }
    }

    public function successfulDataProvider(): iterable
    {
        return [
            [
                [
                    new DateTime('2022-11-07T13:00:00'),
                    new DateTime('2022-11-14T10:00:00'),
                    new DateTime('2022-11-14T06:00:00'),
                    new DateTime('2022-11-14T21:59:59'),
                ],
            ],
        ];
    }

    public function failDataProvider(): iterable
    {
        return [
            [
                [
                    new DateTime('2022-11-08T13:00:00'), // tuesday
                    new DateTime('2022-11-06T10:00:00'), // sunday
                    new DateTime('2022-11-07T05:00:00'), // monday very early
                    new DateTime('2022-11-14T22:59:59'), // monday very late
                ]

            ],
        ];
    }
}