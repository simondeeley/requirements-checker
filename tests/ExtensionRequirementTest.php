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
use Insider\RequirementsChecker\Requirement\ExtensionRequirement;

/**
 * Unit tests for ExtensionRequirement
 *
 * @author  Simon Deeley <s.deeley@icloud.com>
 */
final class ExtensionRequirementTest extends TestCase
{
    /**
     * Test
     *
     * @dataProvider    extensionProvider
     *
     * @param   string $extension
     * @return  void
     */
    final public function testShouldBeAbleToAddValidExtensionRequirement($extension)
    {
        $requirement = new ExtensionRequirement($extension);
        
        $this->assertInstanceOf('Insider\RequirementsChecker\Requirement\ExtensionRequirement', $requirement);
    }
    
    /**
     * Test
     *
     * @dataProvider    invalidExtensionProvider
     *
     * @param   mixed $extension
     * @return  void
     */
    final public function testShouldThrowExceptionWithInvalidExtension($extension)
    {
        $this->expectException('InvalidArgumentException');

        $requirement = new ExtensionRequirement($extension);
    }

    /**
     * Test
     *
     * @dataProvider    requirementsProvider
     *
     * @param   string $extension
     * @param   string $exception
     * @return  void
     */
    final public function testShouldThrowExceptionIfRequirementIsNotMet($requirement, $exception)
    {
        $this->expectException('Insider\RequirementsChecker\Exception\RequirementException');
        $this->expectExceptionMessage($exception);
        
        $requirement = new ExtensionRequirement($requirement);
        $requirement->check();  
    }
    
    /**
     * Test
     *
     * @dataProvider    extensionProvider
     *
     * @param   string $extension
     * @return  void
     */
    final public function testShouldAllowChainingOfRequirements($extension)
    {
        $requirement = new ExtensionRequirement($extension);
        $requirement->add(new ExtensionRequirement($extension));
        
        $this->assertTrue($requirement->check());
    }
    
    /**
     * Test
     *
     * @dataProvider    extensionProvider
     *
     * @param   string $extension
     * @return  void
     */
    final public function testShouldThrowExceptionWhenDeepChainedRequirementFails($extension)
    {
        $requirement_parent = new ExtensionRequirement($extension);
        $requirement_child_one = new ExtensionRequirement($extension);
        $requirement_child_two = new ExtensionRequirement($extension);
        $requirement_child_three = new ExtensionRequirement($extension);
        
        // This should trigger a RequirementException
        $requirement_child_four = new ExtensionRequirement('made-up-extension');
        
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
        $this->expectExceptionMessage('The extension "made-up-extension" is not loaded or not enabled.');
        
        $requirement_parent->check();        
    }
    
    /**
     * Data for valid extension names test
     *
     * @return  array
     */
    final public function extensionProvider()
    {
        return [
            array_rand(array_flip(get_loaded_extensions()), 3)
        ];
    }

    /**
     * Data for invalid extension names test
     *
     * @return  array
     */
    final public function invalidExtensionProvider()
    {
        return [
            ['invalid*ch@r@a(ters'],
            [' '],
            [''],
            [4],
            [false],
            [new \stdClass()],
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
            ['unknown', 'The extension "unknown" is not loaded or not enabled.'],
        ];
    }
}
