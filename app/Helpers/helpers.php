<?php
/**
 * LUXE Ecommerce — Global Helper Functions
 * Location: app/Helpers/helpers.php
 * Registration: Add to composer.json autoload.files or bootstrap/app.php
 */

if (!function_exists('money')) {
    /**
     * Format a number as a currency string.
     *
     * @param float|int $amount
     * @param string    $currency
     * @param int       $decimals
     * @return string
     */
    function money(float|int $amount, string $currency = '$', int $decimals = 2): string
    {
        return $currency . number_format($amount, $decimals);
    }
}
