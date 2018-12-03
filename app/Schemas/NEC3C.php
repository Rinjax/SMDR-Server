<?php

/*
 * Reason Codes - Defines the reason for the telephone call’s appearance at the destination address:

    • 0 - Unknown
    • 1 - Direct
    • 2 - Forwarded on busy
    • 3 - Forwarded on no answer
    • 4 - Forwarded
    • 5 - Transferred
    • 6 - Picked up
    • 7 - Parked
    • 8 - Conferenced
    • 9 - Forwarded on DND
    • 10 - Forwarded on unresolved address
    • 11 - Intercom
    • 12 - Callback
    • 14 - Click to dial
    • 15 - Supervision
    • 16 - Group Page
    • 17 - Auto Ring Back (ARB)
    • 18 - Forwarded on reject

 * Outcome Codes - Defines the outcome of the telephone call:

    • 0 - Connected
    • 1 - Congested
    • 2 - Unanswered
    • 3 - Trunks Busy
 */

namespace App\Schemas;

class NEC3C
{
    //the SMDR data provided as an array.
    protected $smdr;

    //attribute to hold the mapped assoc array.
    public $map;

    public function __construct($smdrArray)
    {
        $this->smdr = $smdrArray;

        $this->map = $this->map($this->smdr);
    }

    /**
     * map the SMDR array to the correct database fields.
     * @param $array
     * @return array
     */
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