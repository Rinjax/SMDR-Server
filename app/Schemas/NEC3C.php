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
            'origin_name'       => $this->reorderName($array[2]),
            'origin_type'       => $this->nullable($array[3]),
            'origin_number'     => $this->nullable($array[4]),
            'intended_name'     => $this->reorderName($array[8]),
            'intended_type'     => $this->nullable($array[9]),
            'intended_number'   => $this->nullable($array[10]),
            'received_name'     => $this->reorderName($array[5]),
            'received_type'     => $this->nullable($array[6]),
            'received_number'   => $this->nullable($array[7]),
            'outcome'           => (int)$array[11],
            'reason'            => (int)$array[12],
            'duration_call'     => (int) $array[14],
            'duration_ring'     => (int) $array[37],
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
    }

    /**
     * Search the string for the {!} marker - this is from escaped commas in the CDR data. If that is present, then the
     * NEC has provided a name in the format SURNAME, FIRST - break the name into an array and return in the correct
     * order FIRST SURNAME. If there is no {!} marker then its assumed that name is the correct way round
     *
     * @param $name
     * @return string
     */
    protected function reorderName($name)
    {
        if(strpos($name, '{!}')){
            $array = explode(' ', str_replace('{!}', '',$name));

            if(count($array) == 2) return $array[1] . " " . $array[0];

            if(count($array) == 3) return $array[2] . " " . $array[1] . " " . $array[0];
        }

        return $name;
    }

    /**
     * Convert blank strings to null
     * @param $data
     * @return null
     */
    protected function nullable($data)
    {
        if($data == '') return null;

        return $data;
    }

}