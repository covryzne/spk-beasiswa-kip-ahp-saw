
   Psy\Exception\ParseErrorException 

  PHP Parse error: Syntax error, unexpected '.' on line 1

  at D:\Coolyeah\Skripsh1t\Ikik\spk-beasiswa-kip\vendor\psy\psysh\src\Exception\ParseErrorException.php:44
     40▕      * @param \PhpParser\Error $e
     41▕      */
     42▕     public static function fromParseError(\PhpParser\Error $e): self
     43▕     {
  ➜  44▕         return new self($e->getRawMessage(), $e->getAttributes());
     45▕     }
     46▕ }
     47▕

  1   D:\Coolyeah\Skripsh1t\Ikik\spk-beasiswa-kip\vendor\psy\psysh\src\CodeCleaner.php:306
      Psy\Exception\ParseErrorException::fromParseError(Object(PhpParser\Error))

  2   D:\Coolyeah\Skripsh1t\Ikik\spk-beasiswa-kip\vendor\psy\psysh\src\CodeCleaner.php:240
      Psy\CodeCleaner::parse("<?php echo '=== C3 NULL CHECK ==='; $calon = App\Models\CalonMahasiswa::get(['nama', 'c3']); foreach ($calon as $c) { $isNull = is_null($c->c3) ? 'NULL' : 'NOT NULL'; $isEmpty = empty($c->c3) ? 'EMPTY' : 'NOT EMPTY'; echo $c->nama . ' - C3: "' . $c- . '" - ' . $isNull . ' - ' . $isEmpty; }
")

