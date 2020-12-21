<?php

namespace GDMyList\tests\units;

require_once __DIR__ . '/../../gd-mylist.php';

use atoum;

class gd_mylist_plugin extends atoum {
    public function test_gd_setcookie () {
        $this
            ->given($this->newTestedInstance)
            ->then
                ->string($this->testedInstance->gd_setcookie())
                    ->isEqualTo('test')
        ;
    }
}