<?php

require_once('streams.php');
require_once('gettext.php');

$app = "gd-mylist";
$language = $_GET['locale'];

$lang_file = new FileReader('../lang/' . $app . '-'.$language.'.mo');
$lang_fetch = new gettext_reader($lang_file);

function __($text) {
    global $lang_fetch;
    return $lang_fetch->translate($text);
}

?>
