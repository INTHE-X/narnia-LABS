<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo 'opcache cleared OK';
} else {
    echo 'opcache not enabled';
}
