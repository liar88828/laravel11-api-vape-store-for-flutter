<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Resources\CheckoutResource;
use App\Http\Resources\ProductResource;
use App\Interfaces\CheckoutRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Checkout;
use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Requests\UpdateCheckoutRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private CheckoutRepositoryInterface $checkoutRepository;

    public function __construct(CheckoutRepositoryInterface $checkoutRepository)
    {
        $this->checkoutRepository = $checkoutRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->checkoutRepository->findAll();
            return ApiResponseClass::sendResponse($data, '', 200);

        } catch (Exception $e) {

            return ApiResponseClass::sendResponse('Product Delete Fail', '', 404);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckoutRequest $request)
    {
        $checkout = [
            'total' => $request->total,
            'delivery' => $request->delivery,
            'payment' => $request->payment,
            'id_user' => $request->id_user,
        ];

        DB::beginTransaction();
        try {
            $data = $this->checkoutRepository->create($checkout);
            DB::commit();
            return ApiResponseClass::sendResponse(new CheckoutResource($data), 'Checkout Create Successful', 201);

        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $data = $this->checkoutRepository->findId($id);
            return ApiResponseClass::sendResponse(new CheckoutResource($data), 'success', 200);
        } catch (Exception $e) {
            return ApiResponseClass::sendResponse('Check Delete Fail', '', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckoutRequest $request, $id)
    {
        try {
            $checkout = [
                'total' => $request->total,
                'delivery' => $request->delivery,
                'payment' => $request->payment,
                'id_user' => $request->id_user,
            ];
            DB::beginTransaction();
            $this->checkoutRepository->update($checkout, $id);
            DB::commit();
            return ApiResponseClass::sendResponse('Checkout Update Successful', '', 201);

        } catch (Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $response = $this->checkoutRepository->delete($id);
            if ($response) {
                return ApiResponseClass::sendResponse('Checkout Delete Successful', '', 204);
            } else {
                return ApiResponseClass::sendResponse('The Data Checkout is not found', '', 404);
            }
        } catch (\Exception $ex) {
            return ApiResponseClass::sendResponse('Checkout Delete Fail', '', 404);
        }
    }
}
