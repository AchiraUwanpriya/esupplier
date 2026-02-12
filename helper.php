<?php
function getvalue($row,$key, $default=""){
    return isset($row[$key])?$row[$key]:$default;
}

?>