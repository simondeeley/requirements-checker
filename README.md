# Requirements Checker for PHP


[![Build Status](https://travis-ci.org/simondeeley/requirements-checker.svg?branch=master)](https://travis-ci.org/simondeeley/requirements-checker)

#### Library for assessing PHP runtime requirements.

It provides an intuitive and simple API for checking system requirements at runtime for any PHP script integrating this library. At the moment it supports PHP version and loaded extensions. There are plans to add more checks in the pipeline!


## Requirements

* PHP >= 5.3.x


## Installation

    composer require insider/requirements-checker


## Usage
Running any checks that aren't met will throw a `Insider\RequirementsChecker\Exception\RequirementException` error which will hold a message telling you what constraint wasn't met. If all checks pass, then the method returns boolean `true`, allowing you to add it to conditional code in your project, for example you can check that a minimum version of PHP is available:
```php
use Insider\RequirementsChecker\VersionRequirement;

$requirement = new VersionRequirement('7.1.2');

if ($requirement->check()) {
    // Do something now we know we have PHP 7.1.2 available
}
```
    
Check that an extension is loaded
```php
use Insider\RequirementsChecker\ExtensionRequirement;

$requirement = new ExtensionRequirement('mcrypt');
$requirement->check();
```
    
Ensure that the maximum PHP version running is 5.6.7, for example
```php
use Insider\RequirementsChecker\VersionRequirement;

$requirement = new VersionRequirement('5.6.7', '<=');
$requirement->check();
```

In fact, you can use any constraint with a `VersionRequirement` such as `lt`, `==`, `>`, `!=` etc. This brings us onto the next subject..

Chaining requirements
```php
use Insider\RequirementsChecker\VersionRequirement;

$minRequirement = new VersionRequirement('5.4.0', '>=');
$maxRequirement = new VersionRequirement('5.6.7', '<');

$minRequirement->add($maxRequirement)->check();
```

You can go mad and chain any number of requirements!
```php
use Insider\RequirementsChecker\ExtensionRequirement;
use Insider\RequirementsChecker\VersionRequirement;

$minRequirement = new VersionRequirement('5.4.0', '>=');
$maxRequirement = new VersionRequirement('5.6.7', '<');
$mycryptRequirement = new ExtensionRequirement('mycrypt');
$reddisRequirement = new ExtensionRequirement('reddis');

$minRequirement
    ->add($maxRequirement)
    ->add($mycryptRequirement)
    ->add($reddisRequirement)
->check();
```
