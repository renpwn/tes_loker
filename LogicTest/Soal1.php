<?php

$x = 8;

for ($i = 1; $i <= $x; $i++) {
    echo implode('', range(1, $i)) . '<br>';
}

?>