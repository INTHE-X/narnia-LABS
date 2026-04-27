<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "opcache_reset() OK";
} else {
    echo "opcache not available";
}
