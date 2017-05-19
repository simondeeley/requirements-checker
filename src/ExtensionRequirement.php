<?php

/*
 * This file is part of the Requirements Checker library.
 *
 * © Simon Deeley <s.deeley@icloud.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Insider\RequirementsChecker;

final class ExtensionRequirement extends Requirement
{
    /**
     * @var string
     */
    private $extension;
    
    /**
     * Error message if requirement is not met
     *
     * @return  string
     */
    final public function __toString()
    {
        return sprintf('The extension "%s" is not loaded or not enabled.', $this->extension);
    }
    
    /**
     * Add an extension requirement
     *
     * @param   string $extension
     * @return  self
     * @throws  InvalidArgumentException
     */
    final public function __construct($extension)
    {
        if (false === is_string($extension) || strlen($extension) === 0) {
            throw new \InvalidArgumentException(sprintf(
                'The name for a required extension must be a string, %s passed.',
                gettype($extension)
            ));
        }
        
        if (preg_match('/[^a-z_\-0-9]/i', $extension)) {
            throw new \InvalidArgumentException(sprintf(
                'The name of a required extension must only contain alphanumeric characters, %s passed.',
                $extension
            ));
        }
        
        $this->extension = $extension;
    }

    /**
     * Evaluate the requirement
     *
     * @return	bool
     */
    final public function evaluate()
    {
        return extension_loaded($this->extension);
    }
}
