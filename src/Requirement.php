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

/**
 * Requirement
 *
 * @author  Simon Deeley <s.deeley@icloud.com>
 */
abstract class Requirement
{
    use ChainedRequirement;
        
    /**
     * Evaluate the requirement
     *
     * @return  bool    Should returns true on success, false if a 
     *                  requirement is not met
     */
    abstract protected function evaluate();
}
