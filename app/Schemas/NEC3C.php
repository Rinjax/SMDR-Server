<?php

namespace App\Schemas;

class NEC3C
{
    protected $smdr;

    public $map;

    public function __construct($smdrArray)
    {
        $this->smdr = $smdrArray;

        $this->map = $this->map($this->smdr);
    }

    protected function map($array)
    {
        return [
            'call_id' =>$array[2],
            'call_leg_id' => $array[22],
            'time_of_call' => $array[14],
            'origin_name' => $array[3],
            'origin_type' => $array[4],
            'origin_number' => $array[5],
            'intended_name' => $array[9],
            'intended_type' => $array[10],
            'intended_number' => $array[11],
            'received_name' => $array[6],
            'received_type' => $array[7],
            'received_number' => $array[8],
            'outcome' => $array[12],
            'reason' => $array[13],
            'duration_call' => $array[15],
            'duration_ring' => $array[38]
        ];
    }

}