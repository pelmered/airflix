<?php
namespace Domain\Tickets\Http\Requests;

use Support\BaseClasses\BaseFormRequest;
class ShowTicket extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $booking = $this->route('ticket');

        return $booking && $this->user()->can('view', $booking);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
