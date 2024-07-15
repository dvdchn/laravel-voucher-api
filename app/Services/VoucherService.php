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
    public function createVoucherForUser(int $userId): Voucher
    {
        $voucherCode = $this->generateUniqueVoucherCode();

        return Voucher::create([
            'user_id' => $userId,
            'code' => $voucherCode,
        ]);
    }

    public function createVoucherForUserAndSendEmail(int $userId)
    {
        $voucher = $this->createVoucherForUser($userId);
        $this->sendVoucherEmail($userId, $voucher->code);
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
