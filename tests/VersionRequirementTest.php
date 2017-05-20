<?php

/*
 * This file is part of the Requirements Checker library.
 *
 * © Simon Deeley <s.deeley@icloud.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Insider\RequirementsChecker\Tests;

use PHPUnit\Framework\TestCase;
use Insider\RequirementsChecker\Requirement\VersionRequirement;
use Insider\RequirementsChecker\Exception\RequirementException;

final class VersionRequirementTest extends TestCase
{
    /**
     * Test
     *
     * @dataProvider    semverProvider
     * @return  void
     */
    final public function testShouldBeAbleToAddValidSemverRequirement($semver)
    {
        $requirement = new VersionRequirement($semver);
        
        $this->assertInstanceOf('Insider\RequirementsChecker\Requirement\VersionRequirement', $requirement);
    }
    
    /**
     * Test
     *
     * @dataProvider    invalidSemverProvider
     * @return  void
     */
    final public function testShouldThrowExceptionWithInvalidSemverNumbers($semver)
    {
        $this->expectException('InvalidArgumentException');

        $requirement = new VersionRequirement($semver);
    }

    /**
     * Test
     *
     * @dataProvider    operandProvider
     * @return  void
     */
    final public function testShouldBeAbleToAddValidOperands($operand)
    {
        $requirement = new VersionRequirement(PHP_VERSION, $operand);
        
        $this->assertInstanceOf('Insider\RequirementsChecker\Requirement\VersionRequirement', $requirement);
    }
    
    /**
     * Test
     *
     * @dataProvider    invalidOperandProvider
     * @return  void
     */
    final public function testShouldThrowExceptionWithdInvalidOperands($operand)
    {
        $this->expectException('InvalidArgumentException');

        $requirement = new VersionRequirement(PHP_VERSION, $operand);  
    }

    /**
     * Test
     *
     * @dataProvider    requirementsProvider
     * @return  void
     */
    final public function testShouldThrowExceptionIfRequirementIsNotMet($requirement, $operand, $exception)
    {
        $this->expectException('Insider\RequirementsChecker\Exception\RequirementException');
        $this->expectExceptionMessage($exception);
        
        $requirement = new VersionRequirement($requirement, $operand);
        $requirement->check();  
    }
    
    /**
     * Test
     *
     * @return  void
     */
    final public function testShouldAllowChainingOfRequirements()
    {
        $requirement = new VersionRequirement('5.6.3', '>');
        $requirement->add(new VersionRequirement(PHP_VERSION, 'le'));
        
        $this->assertTrue($requirement->check());
    }
    
    /**
     * Test
     *
     * @return  void
     */
    final public function testShouldThrowExceptionWhenDeepChainedRequirementFails()
    {
        $requirement_parent = new VersionRequirement(PHP_VERSION);
        $requirement_child_one = new VersionRequirement(PHP_VERSION);
        $requirement_child_two = new VersionRequirement(PHP_VERSION);
        $requirement_child_three = new VersionRequirement(PHP_VERSION);
        
        // This should trigger a RequirementException
        $requirement_child_four = new VersionRequirement('5.2.0', '<');
        
        // Ridiculous chaining!
        $requirement_parent->add(
            $requirement_child_one->add(
                $requirement_child_two->add(
                    $requirement_child_three->add(
                        $requirement_child_four
                    )
                )
            )
        );
        
        $this->expectException('Insider\RequirementsChecker\Exception\RequirementException');
        $this->expectExceptionMessage('Maximum required PHP version 5.2.0 is greater than the current version '. PHP_VERSION);
        
        $requirement_parent->check();        
    }
    
    /**
     * Data for semantic versions test
     *
     * @return  array
     */
    final public function semverProvider()
    {
        return [
            ['5.1.0'],
            ['7.0.2'],
            ['5.3.1-beta'],
            ['5.6.0-4bcrf']
        ];
    }

    /**
     * Data for invalid semantic versions test
     *
     * @return  array
     */
    final public function invalidSemverProvider()
    {
        return [
            ['5.1'],
            ['7.0.x'],
            ['5.3.1-beta-e43ed'],
            ['4']
        ];
    }

    
    /**
     * Data for operand test
     *
     * @return  array
     */
    final public function operandProvider()
    {
        return [
            ['<'],
            ['lt'],
            ['<='],
            ['le'],
            ['>'],
            ['gt'],
            ['>='],
            ['ge'],
            ['=='],
            ['='],
            ['eq'],
            ['!='],
            ['<>'],
        ];
    }

    /**
     * Data for invalid operand test
     *
     * @return  array
     */
    final public function invalidOperandProvider()
    {
        return [
            ['<<'],
            ['>>'],
            ['==='],
            ['><'],
            ['tl'],
        ];
    }
    
    /**
     * Data for requirements test
     *
     * @return  array
     */
    final public function requirementsProvider()
    {
        return [
            ['5.6.2', '<=', 'Maximum required PHP version 5.6.2 is greater than the current version ' . PHP_VERSION],
            ['7.3.0', '>=', 'Minimum required PHP version 7.3.0 is less than the current version ' . PHP_VERSION],
            ['4.2.0', 'eq', 'Required PHP version 4.2.0 is not equal to the current version ' . PHP_VERSION],
        ];
    }
}
