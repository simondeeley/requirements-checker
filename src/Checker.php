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

/**
 * Checker
 *
 * Interface that any class that checks requirements should inherit from. It
 * provides only one public method check() which should return bool true if
 * all requirements are met.
 *
 * @author  Simon Deeley <s.deeley@icloud.com>
 */
interface Checker
{
    /**
     * Check requirement
     *
     * @return	bool;
     */
    public function check();
}
