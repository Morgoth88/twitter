<?php

namespace App\Services;

class ForeacherService
{

    /**
     * @param $items
     */
    public function ormDeleteForeach($items)
    {
        foreach ($items as $item) {
            $item->delete();
        }
    }

}
