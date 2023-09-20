<?php

namespace App\Http\Controllers\Api\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Customers as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Sales\Customers as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\DuplicateContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Contacts;

class Customers extends Controller
{
    use Contacts;

    /**
     * @var string
     */
    public $type = Contact::CUSTOMER_TYPE;


    /**
     * Store a newly created resource in storage using api.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function createCustomer(Request $request)
    {
        $getname = $request->all();
        $clone = Contact::where('name', $getname['name'])->orWhere('email',$getname['email'])->first();
        if($clone){
            $message = trans('Aleready exists', ['type' => trans_choice('general.customers', 1)]);
        } else {
        $response = $this->ajaxDispatch(new CreateContact($request));

        if ($response['success']) {
            
            $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

        } else {
            
            $message = $response['message'];
            }
        }
        
        return response()->json($message);
    }

    
}
