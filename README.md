# Testes

*Make your code test-tacular.*

**Testes** is a simple PHP testing library. It is designed for programmers who want to add test coverage using an intuitive API. 

## Features

- Around a dozen built-in assertions 
- Support for `before` and `after` methods (setup and cleanup)
- Single file dependency (`testes.php`)
- Tested with PHP 5.3 and above

## Example

Copy/paste the following in file called `example.php`:

```php
<?php

include("testes.php");

class TestArrays extends Testes {
    function before() {
        $this->a = array("First", true);
    }
    
    function test_count() {
        $this->assert_count($this->a, 2); // Pass
        $this->assert_count($this->a, 20); // Fail
    }
    
    function test_first_element() {
        $this->assert_equals($this->a[0], "First"); // Pass
        $this->assert_equals($this->a[0], "Second"); // Fail
    }
    
    function test_second_element() {
        $this->assert_true($this->a[1]); // Pass
        $this->assert_false($this->a[1]); // Fail
    }
    
    function after() {
        unset($this->a);
    }
}

Testes::run();
```

Executing `php example.php` from the command line will yield the following output:

	.X.X.X

	TestArrays::test_count -- <2> does not match count of 20
	/Users/kunal/Desktop/Testes/example.php:11

	TestArrays::test_first_element -- <Second> (string) expected but was First (string)
	/Users/kunal/Desktop/Testes/example.php:16
	
	TestArrays::test_second_element -- <false> expected but was true (boolean)
	/Users/kunal/Desktop/Testes/example.php:21
	
	6 assertions. 3 failures.


## Using Testes

**Testes** is easy to use:

1. Make sure your test class extends `Testes`. This will allow your class to get access to the built-in assertions. 

2. Make sure that each of your test methods begin with the phrase `test` (case-insensitive).

3. When invoked, `Testes::run()` will build a list of classes that extend `Testes`. It will then automagically call `test` methods. `before`/`after` methods will wrap each test call (see below).

4. `Testes` will keep track of every assertion, including the originating file, class, method, and line number. At the end of test execution, you will be presented with a detailed log of successes and/or failures.


## Before & After

**Testes** allows programmers to create setup/cleanup methods that execute before and after each test. These methods are appropriately named `before` and `after`. You are not required to implement these methods in your test classes. 

The pair provides convenient functionality:

- Easy to create/destroy common objects (reusability)
- Allows for code consistency - copying and pasting setup blocks is bad
- Can prevent test pollution - i.e. destructive functions against an object that will be tested later

## Assertions API

**Testes** includes the following assertions: 

Function     | Pseudo-English 
------------ | -------------- 
assert_count($actual, $count) | $actual (array/object) has a count of $expected
assert_equals($actual, $expected) | $actual equals $expected
assert_false($actual) | $actual is false (requires boolean type)
assert_instance_of($actual, $string_or_object) | $actual is an instance of $string_or_object
assert_not_null($actual) | $actual is not null
assert_null($actual) | $actual is null
assert_string_contains($string, $expected) | $string contains $expected (case-sensitive)
assert_string_ends_with($string, $expected) | $string ends with $expected (case-sensitive)
assert_string_matches($string, $regex) | $string matches $regex
assert_string_starts_with($string, $expected) | $string starts with $expected (case-sensitive)
assert_true($actual) | $actual is true


## Installation

Include `testes.php` and start adding test coverage. That's it.

## Credits & License

**Testes** was made by [Kunal Anand](http://kunalanand.com) and released under the MIT License.

Special thanks to [Francisco Brito](http://nullisnull.blogspot.com) and [Jason Mooberry](http://jasonmooberry.com/) for creative input and rhetorical emulsification. 

All forks and stars are dedicated to the [Minazo Remembrance Foundation](http://knowyourmeme.com/memes/lolrus).