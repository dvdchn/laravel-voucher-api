<?php
namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Str;

class VoucherService
{
    /**
     * Create a unique voucher code for a given user.
     *
     * @param int $userId
     * @return void
     */
    public function createVoucherForUser(int $userId)
    {
        $voucherCode = $this->generateUniqueVoucherCode();

        Voucher::create([
            'user_id' => $userId,
            'code' => $voucherCode,
        ]);
    }

    /**
     * Generate a unique voucher code.
     *
     * @return string
     */
    protected function generateUniqueVoucherCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
        } while (Voucher::where('code', $code)->exists());
    
        return $code;
    }
}
