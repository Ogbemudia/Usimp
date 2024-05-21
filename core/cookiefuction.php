<?php
function build_cookie($var_array) {
    $out = '';
    if (is_array($var_array)) {
      foreach ($var_array as $index => $data) {
        $out .= ($data != "") ? $index . "=" . $data . "|" : "";
      }
    }
    return rtrim($out, "|");
  }
  
  // make the func to break the cookie
  // down into an array
  