<?php
// includes/helpers.php

if (!function_exists('e')) {
    /**
     * Escape output untuk mencegah XSS
     * 
     * @param string|null $str
     * @return string
     */
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}