Errors Trait
============

Simple Errors Trait for PHP classes.

For example:
------------

You have some class.

```php

class Process 
{
	use \Assistance\ErrorsTrait\ErrorTrait;

    /**
     * Run process
     * @return $this
     */
	public function run() {
		// do something

		// is error happened - add error
		$this->addError('This is error');

		return $this;
	}
}

```

How to use?

```php

//...
require __DIR__ . '/vendor/autoload.php';

//...
session_start();
//...

require __DIR__ . '/Process.php';

// current page
$process = new Process();
$process->run();

if ($process->hasErrors()) {
	$errorArr = $process->getErrors();
	echo 'Current errors:' . PHP_EOL;
	foreach ($errorArr as $error) {
		echo $error . PHP_EOL;
	}

	$process->errorsToSession('process_errors');
}

//another page - get from session by key
$errors = new \Assistance\ErrorsTrait\Errors();
$errors->loadFromSession('process_errors');

if ($errors->hasErrors()) {
	$errorArr = $errors->getErrors();
	echo 'Errors for this page:' . PHP_EOL;
	foreach ($errorArr as $error) {
		echo $error . PHP_EOL;
	}
}

```