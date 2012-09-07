<?

class Testes {
  static private $assertion_count = 0;
  static private $exceptions = array();

  function before() { }
  function after() { }

  private function assert($fn, $args) {
    try {
      call_user_func_array($fn, $args);
      echo ".";
    }
    catch (TestesException $e) {
      self::$exceptions[] = $e;
      echo "X";
    }
    self::$assertion_count++;
  }

  function assert_count($actual, $count) {
    $this->assert(function($actual, $count) {
      if (count($actual) != $count) {
        $actual_count = count($actual);
        TestesException::new_exception("<{$actual_count}> does not match count of $count");
      }
    }, func_get_args());
  }
  
  function assert_equals($actual, $expected) {
    $this->assert(function($actual, $expected) {
      if ($actual !== $expected) {
        $actual_type = gettype($actual);
        $expected_type = gettype($expected);
        TestesException::new_exception("<$expected> ({$expected_type}) expected but was $actual ({$actual_type})");
      }
    }, func_get_args());
  }
  
  function assert_false($actual) {
    $this->assert(function($actual) {
      if ($actual !== false) {
        $actual_type = gettype($actual);
        $actual = Testes::prep_boolean($actual);
        TestesException::new_exception("<false> expected but was $actual ({$actual_type})");
      }
    }, func_get_args());
  }

  function assert_true($actual) {
    $this->assert(function($actual) {
      if ($actual !== true) {
        $actual_type = gettype($actual);
        $actual = Testes::prep_boolean($actual);
        TestesException::new_exception("<true> expected but was $actual ({$actual_type})");
      }
    }, func_get_args());
  }

  static function prep_boolean($a) {
    if (is_bool($a)) {
      if ($a === false) { 
        return "false";
      }
      else if ($a === true) { 
        return "true"; 
      }
    }
    return $a;
  }
  
  static function run() {
    $test_classes = array();
    $testes_methods = get_class_methods("Testes");
    
    foreach (get_declared_classes() as $c) {
      if (is_subclass_of($c, "Testes")) {
        $test_classes[] = $c;
      }
    }
    
    foreach ($test_classes as $c) {
      $c = new $c;
      $methods = array_diff(get_class_methods($c), $testes_methods);
      
      foreach ($methods as $m) {
        $m = strtolower($m);
        if (is_callable(array($c, "before"))) {
          $c->before();
        }
        if ((substr($m, 0, 4) === "test") && is_callable(array($c, $m))) {
          $c->$m();
        }
        if (is_callable(array($c, "after"))) {
          $c->after();
        }
      }
    }
    
    echo "\n\n";
    
    if (count(self::$exceptions) > 0) {
      foreach(self::$exceptions as $e) {
        echo "$e\n\n";
      }
    }

    echo self::$assertion_count . " assertions. ";
    echo count(self::$exceptions) . " failures.\n";
  }
}

class TestesException extends Exception {
  function __construct($message, $file, $class, $method, $line) {
    parent::__construct($message, 0);
    $this->file = $file;
    $this->class = $class;
    $this->method = $method;
    $this->line = $line;
  }

  function __toString() {
    return "{$this->class}::{$this->method} -- {$this->message}\n{$this->file}:{$this->line}";
  }

  static function new_exception($message) {
    $trace = debug_backtrace();
    $file = $trace[4]['file'];
    $class = $trace[5]['class'];
    $method = $trace[5]['function'];
    $line = $trace[4]['line'];
    throw new TestesException($message, $file, $class, $method, $line);
  }
}