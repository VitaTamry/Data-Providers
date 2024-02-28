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
     * @OA\Get(
     *     path="/api/v1/transactions",
     *     tags={"Transactions"},
     *     summary="Get all transactions with filters",
     *     description="Get all transactions with filters",
     *     operationId="getTransactions",
     *     @OA\Parameter(name="statusCode",in="query",description="status code",required=false,
     *         @OA\Schema(type="string", enum={"paid", "pending", "reject"})
     *     ),
     *     @OA\Parameter(name="currency",in="query",description="currency",required=false,
     *         @OA\Schema( type="string" )
     *     ),
     *     @OA\Parameter(name="amountMin",in="query",description="minimum amount",required=false,
     *         @OA\Schema( type="number" )
     *     ),
     *     @OA\Parameter(name="amountMax",in="query",description="maximum amount",required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter( name="provider", in="query",description="provider", required=false,
     *         @OA\Schema(type="string", enum={"DataProviderX", "DataProviderY", "DataProviderW"})
     *     ),
     * 
     *     @OA\Response(response=200, description="successful operation",
     *        @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(property="payload", type="array", @OA\Items(
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="amount", type="number", example=200),
     *            @OA\Property(property="currency", type="string", example="USD"),
     *            @OA\Property(property="statusCode", type="string", example="paid"),
     *            @OA\Property(property="provider", type="string", example="DataProviderX"),
     *            @OA\Property(property="created_at", type="string", example="2021-01-01 00:00:00"),
     *           ),
     *         ),
     *       ),
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     * 
     *
     * Show all transactions
     * @return /Iluminate/Http/Response
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
