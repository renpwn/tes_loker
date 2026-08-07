<?php

$x = 5;

echo "<pre>";
for ($i = 1; $i <= $x; $i++) {
    echo str_repeat(" ", $x - $i) . str_repeat("*", 2 * $i - 1) . '<br>';
}
// echo "</pre>";

?>