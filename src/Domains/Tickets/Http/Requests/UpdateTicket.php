<?php
namespace Domain\Tickets\Http\Requests;

use Support\BaseClasses\BaseFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UpdateTicket extends BaseFormRequest
{
    use AuthorizesRequests;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;

        //TODO: add proper auth check using policies
        $ticket = $this->route('booking');

        return $ticket && $this->user()->can('update', $ticket);
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
