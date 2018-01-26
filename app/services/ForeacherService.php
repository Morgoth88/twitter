<?php

namespace App\services;

class ForeacherService
{

    /**
     * @param $items
     */
    public function OrmDeleteForeach($items)
    {
        foreach ($items as $item) {
            $item->delete();
        }
    }

}
