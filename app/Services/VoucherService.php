<?php
namespace App\Services;

use App\Mail\WelcomeEmail;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class VoucherService
{
    /**
     * Create a unique voucher code for a given user.
     *
     * @param int $userId
     * @return Voucher
     */
    public function createVoucherForUser(int $userId): ?Voucher
    {
        $voucherCount = Voucher::where('user_id', $userId)->count();
        
        if ($voucherCount >= 10) {
            return null;
        }
        
        $voucherCode = $this->generateUniqueVoucherCode();

        return Voucher::create([
            'user_id' => $userId,
            'code' => $voucherCode,
        ]);
    }

    /**
     * Create a unique voucher code for a given user and send welcome email.
     *
     * @param int $userId
     * @return Voucher
     */
    public function createVoucherForUserAndSendEmail(int $userId)
    {
        $voucher = $this->createVoucherForUser($userId);
        $this->sendVoucherEmail($userId, $voucher->code);
    }

    /**
     * Get all vouchers for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getVouchersForUser(int $userId)
    {
        return Voucher::where('user_id', $userId)->get();
    }

    /**
     * Get a voucher by ID for a user.
     *
     * @param int $userId
     * @param int $voucherId
     * @return Voucher|null
     */
    public function getVoucherByIdForUser(int $userId, int $voucherId)
    {
        return Voucher::where('user_id', $userId)->find($voucherId);
    }

    /**
     * Delete a voucher for a user.
     *
     * @param int $userId
     * @param int $voucherId
     * @return bool
     */
    public function deleteVoucherForUser(int $userId, int $voucherId): bool
    {
        return Voucher::where('user_id', $userId)->where('id', $voucherId)->delete();
    }

    /**
     * Generate a unique voucher code.
     *
     * @return string
     */
    protected function generateUniqueVoucherCode(): string
    {
        $maxAttempts = 100;
        $attempts = 0;
    
        do {
            $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
            $attempts++;
        } while (Voucher::where('code', $code)->exists() && $attempts < $maxAttempts);
    
        if ($attempts >= $maxAttempts) {
            throw new \Exception('Unable to generate a unique voucher code.');
        }
    
        return $code;
    }

    protected function sendVoucherEmail(int $userId, string $voucherCode): void
    {
        $user = User::find($userId);
        Mail::to($user->email)->send(new WelcomeEmail($user, $voucherCode));
    }

}
