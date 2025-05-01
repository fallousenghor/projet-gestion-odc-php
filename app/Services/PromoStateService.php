<?php

namespace App\Services;

class PromoStateService
{
    public static function getState(array $promo): string
    {
        try {
            $currentDate = new \DateTime();


            $startDate = self::convertFrenchDate($promo['date_debut'] ?? '');
            $endDate = self::convertFrenchDate($promo['date_fin'] ?? '');

            if (!$startDate || !$endDate) {
                return 'indetermine';
            }

            if ($currentDate < $startDate) {
                return 'pas_commence';
            } elseif ($currentDate > $endDate) {
                return 'termine';
            }
            return 'en_cours';
        } catch (\Exception $e) {
            error_log("PromoStateService error: " . $e->getMessage());
            return 'indetermine';
        }
    }

    private static function convertFrenchDate(string $frenchDate): ?\DateTime
    {
        if (empty($frenchDate)) {
            return null;
        }


        if (!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $frenchDate, $matches)) {
            return null;
        }

        try {

            $isoDate = "{$matches[3]}-{$matches[2]}-{$matches[1]}";
            return new \DateTime($isoDate);
        } catch (\Exception $e) {
            error_log("Date conversion error for '$frenchDate': " . $e->getMessage());
            return null;
        }
    }
}