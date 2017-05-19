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

use Insider\RequirementsChecker\Exception\RequirementException;

trait ChainedRequirement
{
    /**
     * @var array
     */
    private $requirements = [];
    
    /**
     * Error message if requirement is not met
     *
     * @return  string
     */
    public function __toString()
    {
        return '';
    }
    
    /**
     * Check that the requirements are met
     *
     * @return  bool                    Returns true on success
     * @throws  RequirementException    if requirements are not met
     */
    final public function check()
    {        
        foreach($this->requirements as $requirement) {
            $requirement->check();
        }

        if ($this->evaluate() === true) {
            return true;
        }
        
        throw new RequirementException($this);
    }
    
    /**
     * Add a new requirement
     *
     * @param   Requirement $requirement
     * @return  self
     */
    final public function add(Checker $requirement)
    {
        $this->requirements[] = $requirement;
        
        return $this;
    }
}
