<?php

namespace App\Api\Requests\Bookings;

use Support\BaseClasses\BaseFormRequest;

class CreateTicket extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //$booking = Booking::findOrFail($this->route('booking'));
        //return $this->user()->can('store', $booking);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vehicle_uuid' => 'required|exists:vehicles,uuid',
            'estimated_distance' => 'required|integer', //|min:20
            'estimated_start_time' => 'required|iso_date|after_or_equal:today',
            'estimated_end_time' => 'required|iso_date|after:estimated_start_time',
            'test' => 'boolean',
            'insurance_excess_waiver' => 'boolean',
            'butler_coordinate_uuid' => 'exists:butler_coordinates,uuid',
        ];
    }
}
