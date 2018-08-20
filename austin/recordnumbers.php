<?php

// Here we can show the user exactly what records they're looking at, since we know the offset, the total number of items, and the page size.

if ($offset >= $totalitems - $pagesize) {
echo 'Displaying ' . ($offset + 1) . "&#8212;" . ($totalitems + 1) . ' of ' . ($totalitems + 1);
} else {
echo 'Displaying ' . ($offset + 1) . "&#8212;" . ($offset + $pagesize) . ' of ' . ($totalitems + 1);
}
?>
