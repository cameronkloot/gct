<?php
//: lib/common.php ://

function ck_encode_twitter( $text ) {
  return htmlspecialchars(urlencode(html_entity_decode($text, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
}

function startsWith($haystack, $needle) {
  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}
function endsWith($haystack, $needle) {
  return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

//: END lib/common.php ://
