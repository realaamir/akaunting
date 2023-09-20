<?php

namespace App\Http\Controllers\Api\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Invoices\Invoices as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Document\Document as Request;
use App\Imports\Sales\Invoices\Invoices as Import;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\SendDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Traits\Documents;

class Invoices extends Controller
{
    use Documents;

    /**
     * @var string
     */
    public $type = Document::INVOICE_TYPE;

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function createInvoices(Request $request)
    {
        
        $getname = $request->all();
        $clone = Document::where('document_number', $getname['document_number'])->first();

        if($clone){
            $message = trans('Aleready exists', ['type' => trans_choice('general.customers', 1)]);
        } else {

        $response = $this->ajaxDispatch(new CreateDocument($request));

        if ($response['success']) {
            $paramaters = ['invoice' => $response['data']->id];

            $paramaters['senddocument'] = true;
            
            $message = trans('messages.success.added', ['type' => trans_choice('general.invoices', 1)]);

        } else {
            
            $message = $response['message'];

        }
    }

        return response()->json($message);
    }

    
}
