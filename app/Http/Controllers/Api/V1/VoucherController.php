<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends BaseApiController
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Display a listing of the vouchers for the authenticated user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $vouchers = $this->voucherService->getVouchersForUser(auth()->user()->id);

        return $this->success($vouchers, 'Vouchers retrieved successfully.');
    }

    /**
     * Store a newly created voucher for the authenticated user.
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $voucher = $this->voucherService->createVoucherForUser(auth()->user()->id);

        if ($voucher) {
            return $this->success($voucher, 'Voucher code generated successfully.');
        }

        return $this->error('Voucher code limit reached.', [], 400);
    }

    /**
     * Display the specified voucher for the authenticated user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $voucher = $this->voucherService->getVoucherByIdForUser(auth()->user()->id, $id);

        if ($voucher) {
            return $this->success($voucher, 'Voucher retrieved successfully.');
        }

        return $this->error('Voucher not found.', [], 404);
    }

    /**
     * Remove the specified voucher from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->voucherService->deleteVoucherForUser(auth()->user()->id, $id);

        if ($deleted) {
            return $this->success('Voucher deleted successfully.');
        }

        return $this->error('Voucher not found or could not be deleted.', [], 404);
    }
}
