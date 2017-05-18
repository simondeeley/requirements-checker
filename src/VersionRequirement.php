<?php

/*
 * This file is part of the Requirements Checker library.
 *
 * © Simon Deeley <s.deeley@icloud.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aside\RequirementsChecker;

final class VersionRequirement extends Requirement
{
    /**
     * @var array
     */
    private $operands = [
        // Operand => Error, not the actual operand description!
        '<' => 'Maximum required PHP version %s is greater than the current version %s.',
        'lt' => 'Maximum required PHP version %s is greater than the current version %s.',
        '<=' => 'Maximum required PHP version %s is greater than the current version %s.',
        'le' => 'Maximum required PHP version %s is greater than the current version %s.', 
        '>' => 'Minimum required PHP version %s is less than the current version %s.',
        'gt' => 'Minimum required PHP version %s is less than the current version %s.',
        '>=' => 'Minimum required PHP version %s is less than the current version %s.', 
        'ge' => 'Minimum required PHP version %s is less than the current version %s.',
        '==' => 'Required PHP version %s is not equal to the current version %s.', 
        '=' => 'Required PHP version %s is not equal to the current version %s.',
        'eq' => 'Required PHP version %s is not equal to the current version %s.',
        '!=' => 'Required PHP version %s should not be equal to the current version %s.',
        '<>' => 'Required PHP version %s should not be equal to the current version %s.',
    ];
    
    /**
     * @var string
     */
    private $semver;
    
    /**
     * @var string
     */
    private $operand;
    
    /**
     * Error message if requirement is not met
     *
     * @return  string
     */
    final public function __toString()
    {
        return sprintf($this->operands[$this->operand], $this->semver, PHP_VERSION);
    }
    
    /**
     * Adds a version requirement
     *
     * @param   string $semver  Semantic version
     * @param   string $operand Greater/equals/lesser logic operands
     * @return  self
     * @throws  InvalidArgumentException
     */
    final public function __construct($semver, $operand = '>=')
    {
        if (preg_match('/^([0-9]+\.[0-9]+\.[0-9]+)(-[0-9a-zA-Z.]+)?$/', $semver) !== 1) {
            throw new \InvalidArgumentException("The version $semver is not a valid semantic version number.");
        }
        
        if (!array_key_exists($operand, $this->operands)) {
            throw new \InvalidArgumentException("The operand $operand is not a valid logic operand.");
        }

        $this->semver = $semver;
        $this->operand = $operand;

        return $this;
    }

    /**
     * Evaluate the requirement
     *
     * @return  bool    Should returns true on success, false if a 
     *                  requirement is not met
     */
    final protected function evaluate()
    {
        return version_compare(PHP_VERSION, $this->semver, $this->operand);
    }
}
