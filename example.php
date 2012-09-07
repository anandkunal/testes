<?
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