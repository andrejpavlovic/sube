<?php

// Written by: Andrej Pavlovic
// Date: May 8, 2005
//
// Use _only_ the following functions:
//   selfTest()         - make sure that the class is working correctly
//   isISBN($isbn)      - check if 10 or 13 digit ISBN 
//   is10Digit($isbn)   - check if 10 digit ISBN
//   is13Digit($isbn)   - check if 13 digit ISBN
//   get10Digit($isbn)  - Will return true if it can generate a 10 digit ISBN,
//                        false otherwise. Generated ISBN is stored in
//                        $this->isbn;
//                        Note: Converting a 13 digit ISBN that starts with a
//                        prefix different from 978 is pointless
//   get13Digit($isbn)  - Same as get10Digit($isbn) function but for a 13 digit
//                        ISBN
//
// Use $this->isbn to obtain a well formatted (only digits) ISBN after running
// any of the above functions if they return true.

class isbntest {
  var $isbn;
 
  function selfTest() {

    // These are 10 digit
    assert($this->is10Digit('0-88898-074-4'));
    assert($this->is10Digit('0-07-048716-2'));
    assert($this->is10Digit('0-397-50877-8'));
    assert($this->is10Digit('0-06-017758-6'));
    assert($this->is10Digit('0-1315-2447-X'));
    assert($this->is10Digit('0-13-288366-x'));
    assert($this->is10Digit('0201624850'));
    assert($this->is10Digit('-----0---8--0-5--3-3asdfs1sss4aa9dddd-2dfsdf'));
    
    // These are not 10 digit
    assert(!$this->is10Digit('0-8359-4874-8'));
    assert(!$this->is10Digit('0-02-408031-3'));
    assert(!$this->is10Digit('0-534-20244-X'));
    assert(!$this->is10Digit('0-345-24865-1-150'));
    assert(!$this->is10Digit('1234'));
    assert(!$this->is10Digit('12345678xx'));
    assert(!$this->is10Digit('02016x4850'));
    assert(!$this->is10Digit('02016X4850'));
    assert(!$this->is10Digit('x020162485'));
    assert(!$this->is10Digit('X020162485'));  
    
    // These are 13 digit
    assert($this->is13Digit('978-1-234-56789-7'));
    assert($this->is13Digit('978-1-873671-00-9'));
    assert($this->is13Digit('9780966225730'));
    assert($this->is13Digit('9790866225730'));
    assert($this->is13Digit('979sdsss0866225asdfasdsss---sdaa73x-0'));
    
    // These are not 13 digit
    assert(!$this->is13Digit('978-1-873671-00-x'));
    assert(!$this->is13Digit('979-X765-30692-1'));
    assert(!$this->is13Digit('0201624850'));
    assert(!$this->is13Digit('9780966325730'));
    assert(!$this->is13Digit('971-0765-30692-1'));

    // Good Conversions
    $this->get13Digit('020534075X');
    assert(strcmp($this->isbn, '9780205340750') == 0);
    $this->get13Digit('0-88898-074-4');
    assert(strcmp($this->isbn, '9780888980748') == 0);
    $this->get13Digit('0-1315-2447-X');
    assert(strcmp($this->isbn, '9780131524477') == 0);
    $this->get13Digit('0201624850');
    assert(strcmp($this->isbn, '9780201624854') == 0);
    $this->get13Digit('9780201saa62---4854');
    assert(strcmp($this->isbn, '9780201624854') == 0);

    $this->get10Digit('9780205340750');
    assert(strcmp($this->isbn, '020534075X') == 0);
    $this->get10Digit('0-1315-2447-X');
    assert(strcmp($this->isbn, '013152447X') == 0);
    $this->get10Digit('9780966225730');
    assert(strcmp($this->isbn, '0966225732') == 0);
    $this->get10Digit('0-1315-2447-X');
    assert(strcmp($this->isbn, '013152447X') == 0);

    // Bad Conversions
    $this->get10Digit('0-1315-2487-X');
    assert(strcmp($this->isbn, '') == 0);
    $this->get13Digit('0201624820');
    assert(strcmp($this->isbn, '') == 0);
  }

  function isISBN($isbn) {
    if ( $this->is10Digit($isbn) || $this->is13Digit($isbn) ) {
      return true;
    } else {
      return false;
    }
  }

  function is10Digit($isbn) {
    $isbn = preg_replace('/[^0-9X]/', '', strtoupper($isbn));

    if (!( ( strlen($isbn) == 10 ) && !( ( strcmp(substr($isbn, 0, 1), 'X') == 0) || ( strcmp(substr($isbn, 1, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 2, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 3, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 4, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 5, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 6, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 7, 1), 'X') == 0 ) || ( strcmp(substr($isbn, 8, 1 ), 'X') == 0 ) ) ) ) {
      return false;
    }
   
    $checkDigit = substr($isbn, 9, 1);

    if ( strcmp($checkDigit, 'X') == 0 ) {
      $checkDigit = 10;
    }

    $checkSum = ($this->checkSum10($isbn) + $checkDigit) % 11;

    if ($checkSum == 0) {
      $this->isbn = $isbn;
      return true;
    } else {
      $this->isbn = '';
      return false;
    }
  }

  function checkSum10($isbn) {
    return 10 * substr($isbn, 0, 1) + 9 * substr($isbn, 1, 1) + 8 * substr($isbn, 2, 1) + 7 * substr($isbn, 3, 1) + 6 * substr($isbn, 4, 1) + 5 * substr($isbn, 5, 1) + 4 * substr($isbn, 6, 1) + 3 * substr($isbn, 7, 1) + 2 * substr($isbn, 8, 1);
  }

  function is13Digit($isbn) {
    $isbn = preg_replace('/[^0-9]/', '', $isbn);

    if ( strlen($isbn) != 13 ) {
      return false;
    }

    $checkSum = ($this->checkSum13($isbn) + substr($isbn, 12, 1)) % 10;

    if ($checkSum == 0) {
      $this->isbn = $isbn;
      return true;
    } else {
      $this->isbn = '';
      return false;
    }
  }

  function checkSum13($isbn) {
    return substr($isbn, 0, 1) + 3 * substr($isbn, 1, 1) + substr($isbn, 2, 1) + 3 * substr($isbn, 3, 1) + substr($isbn, 4, 1) + 3 * substr($isbn, 5, 1) + substr($isbn, 6, 1) + 3 * substr($isbn, 7, 1) + substr($isbn, 8, 1) + 3 * substr($isbn, 9, 1) + substr($isbn, 10, 1) + 3 * substr($isbn, 11, 1);
  }

  function get10Digit($isbn) {
    if ($this->is10Digit($isbn)) {
      return true;
    } elseif ($this->is13Digit($isbn)) {
      $this->convert13To10($this->isbn);
      return true;
    } else {
      return false;
    }
  }

  function get13Digit($isbn) {
    if ($this->is13Digit($isbn)) {
      return true;
    } elseif ($this->is10Digit($isbn)) {
      $this->convert10To13($this->isbn);
      return true;
    } else {
      return false;
    }
  }

  function convert10To13($isbn) {
    $isbn = substr($isbn, 0, 9);
    $isbn = '978' . $isbn;

    $checkSum = (10 - ($this->checkSum13($isbn) % 10)) % 10;

    $this->isbn = $isbn . $checkSum;
  }

  function convert13To10($isbn) {

    $isbn = substr($isbn, 3, 9);

    $checkSum = (11 - ($this->checkSum10($isbn) % 11)) % 11;

    if ($checkSum == 10 ) {
      $checkSum = 'X';
    }

    $this->isbn = $isbn . $checkSum;
  }

}

?>