<?php

class ChangeCalculator
{
    private $availableChange;
    private $conversionRate;

    public function __construct()
    {
        $this->availableChange = $this->getAvailableChange();
        $this->conversionRate = 100;
    }

    public function getAvailableChange()
    {
        return array(
            1 => 25,
            2 => 74,
            5 => 14,
            10 => 18,
            20 => 0,
            50 => 5,
            100 => 30,
            200 => 15,
            500 => 8,
            1000 => 11,
            2000 => 8,
            5000 => 5,
            10000 => 2,
            20000 => 0,
            50000 => 0
        );
    }

    /**
     * Convert the given amount in euros to cents using the conversion rate.
     *
     * @param   int     $amountInEuros The amount in euros to convert.
     * @return  int     The converted amount in cents.
     */
    public function convertToCents(float $amountInEuros)
    {
        return $amountInEuros * $this->conversionRate;
    }

    /**
     * Set the conversion rate for euros to cents.
     *
     * @param int $conversionRate The conversion rate.
     */
    public function setConversionRate(int $conversionRate)
    {
        $this->conversionRate = $conversionRate;
    }

    /**
     * Set the conversion rate for euros to cents.
     *
     * @param int $conversionRate The conversion rate.
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }
    /**
     * Cette méthode prendra en entrée le montant à rendre et calculera le rendu de la monnaie en utilisant
     *  le tableau availableChange. Elle renverra le nombre de pièces/billets nécessaires pour rendre la monnaie.
     *
     * @param int $amount
     * @return array $array
     */
    public function calculateChange(float $amount)
    {
        $amoutRender = $amount;
        print 'Total de ' . ($amoutRender > $this->getConversionRate() ? $amoutRender / $this->getConversionRate() : $amoutRender) . ' €';
        $availableChange = $this->availableChange;
        krsort($availableChange);
        $currencyKeys = array_keys($availableChange);
        foreach ($currencyKeys as $currency) {
            $nb = $availableChange[$currency];
            if (($nb > 0 && $currency <= $amount && $amount != 0)) {
                $use = 0;
                for ($nb; $currency <= $amount && $nb > 0; $nb--) {
                    $amount -= $currency;
                    $use++;
                }
                $this->displayRender($amoutRender, $currency, $use);
                // print $currency . ' use : ' . $use . '<br>';
                $array[$currency] = $use;
            }
        }
        print '<div>-----------Fin de la transaction-----------</div>';
        return $array;
    }

    /**
     * Update the availability of coins and bills after a change is made.
     * 
     * @param   array               $changeArray            The array representing the coins and bills used for change.
     * @param   bool                true debit false crédit
     * @return  ChangeCalculator    The updated availability of coins and bills.
     */
    public function updateAvailableChange($changeArray)
    {

        $availableChange = $this->availableChange;
        krsort($availableChange);
        $currencyKeys = array_keys($availableChange);
        foreach ($currencyKeys as $currency) {
            if (isset($changeArray[$currency])) {
                $this->availableChange[$currency] -= $changeArray[$currency];
            }
        }
        return $this->availableChange;
    }
    /**
     *
     * @param int $currency
     * @param int $use
     * @return void
     */
    public function displayRender(float $amoutRender, int $currency, int $use)
    {
        $rate = $this->getConversionRate();
        if ($currency > $rate) {
            print '<div> Billet de : ' . ($currency / $rate) . ' X ' . $use . '</div>';
        } else {
            print '<div> Pièces de : ' . $currency . ' X ' . $use . '</div>';
        }
    }
}

$change = new ChangeCalculator();
var_dump($change);
$amount = $change->convertToCents(500.50);
$arrayRender = $change->calculateChange(intval($amount));
$change->updateAvailableChange($arrayRender);
var_dump($change);
$amount = $change->convertToCents(200.25);
$arrayRender = $change->calculateChange(intval($amount));
$change->updateAvailableChange($arrayRender);
var_dump($change);
$amount = $change->convertToCents(100.11);
$arrayRender = $change->calculateChange(intval($amount));
$change->updateAvailableChange($arrayRender);
var_dump($change);
