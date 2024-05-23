<?php
// This script creates a symbolic link for the storage directory

$target = __DIR__.'/storage/app/public';
$link = __DIR__.'/public/storage';

if (symlink($target, $link)) {
    echo 'The symbolic link has been created.';
} else {
    echo 'Failed to create the symbolic link.';
}
