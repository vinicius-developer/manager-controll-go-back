<?php

namespace App\Traits;

trait FormatData
{

    /**
     * Obtêm um array e passa por cada items desse
     * array checando se há algum item com nome incorreto
     * com base em uma array (referência) criado por nós.
     *
     * @param array $items
     * @param array $reference
     * @return array
     */
    private function checkSintaxeWithReference(array $items, array $reference): array
    {

        foreach ($reference as $wrong => $correct) {

            if(key_exists($wrong, $items)) {

                $items[$correct] = $items[$wrong];

                unset($items[$wrong]);

            }

        }

        return $items;

    }

}
