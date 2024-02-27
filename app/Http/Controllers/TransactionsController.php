<?php

namespace App\Http\Controllers;

use App\Services\TransactionsService;
use Illuminate\Http\Request;
use App\Enums\TransactionStatusEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller
{

    private $transactionsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TransactionsService $transactionsService)
    {
        $this->transactionsService = $transactionsService;
    }

    /**
     * Show all transactions
     * @return /Iluminate/Http/Response
     * 
     */
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'statusCode' => ['string', Rule::in(TransactionStatusEnum::getValues())],
            'currency' => 'string',
            'amountMin' => 'numeric',
            'amountMax' => 'numeric',
            'provider' => ['string', Rule::in($this->transactionsService->availableProviders)]
        ]);
        if ($validator->fails()) {
            return $this->jsonResponse('Bad Request', $validator->errors(), 400);
        }
        $validated = $validator->validated();
        $transactions = $this->transactionsService->getTransactions($validated);
        if (count($transactions) > 0)
            return $this->jsonResponse('success', $transactions);
        else
            return  $this->jsonResponse('Not Found', null, 404);
    }
}
