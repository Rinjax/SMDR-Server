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

use Carbon\Carbon;

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
            'call_id'           => (int)$array[1],
            'call_leg_id'       => (int)$array[21],
            'time_of_call'      => Carbon::createFromTimestamp((int)$array[13]),
            'origin_name'       => $array[2],
            'origin_type'       => $array[3],
            'origin_number'     => $array[4],
            'intended_name'     => $array[8],
            'intended_type'     => $array[9],
            'intended_number'   => $array[10],
            'received_name'     => $array[5],
            'received_type'     => $array[6],
            'received_number'   => $array[7],
            'outcome'           => (int)$array[11],
            'reason'            => (int)$array[12],
            'duration_call'     => (int) $array[14],
            'duration_ring'     => (int) $array[38],  //should be 37 according to manual (array offset 0)
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
    }

}