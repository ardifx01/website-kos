<?php
// app/Services/ComplaintTokenService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ComplaintTokenService
{
    /**
     * Generate a unique token for complaint form access
     */
    public static function generateToken($type = 'general', $expiresInHours = 168): array // 7 days default
    {
        $timestamp = now()->format('YmdHis');
        $randomString = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $token = $type . '-' . $timestamp . '-' . $randomString;
        
        // Store token info in cache for tracking
        $tokenData = [
            'token' => $token,
            'type' => $type,
            'generated_at' => now()->toDateTimeString(),
            'expires_at' => now()->addHours($expiresInHours)->toDateTimeString(),
            'used_count' => 0,
            'max_usage' => $type === 'single-use' ? 1 : null,
        ];
        
        Cache::put('complaint_token_' . $token, $tokenData, now()->addHours($expiresInHours));
        
        Log::info('Complaint token generated', $tokenData);
        
        return [
            'token' => $token,
            'link' => route('komplain.form.token', ['token' => $token]),
            'qr_code_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode(route('komplain.form.token', ['token' => $token])),
            'expires_at' => $tokenData['expires_at'],
            'type' => $type,
        ];
    }
    
    /**
     * Validate and track token usage
     */
    public static function validateToken($token): array
    {
        $cacheKey = 'complaint_token_' . $token;
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData) {
            return [
                'valid' => false,
                'reason' => 'Token not found or expired',
            ];
        }
        
        // Check if token has exceeded max usage
        if (isset($tokenData['max_usage']) && $tokenData['used_count'] >= $tokenData['max_usage']) {
            return [
                'valid' => false,
                'reason' => 'Token usage limit exceeded',
                'data' => $tokenData,
            ];
        }
        
        // Increment usage count
        $tokenData['used_count']++;
        $tokenData['last_used_at'] = now()->toDateTimeString();
        
        Cache::put($cacheKey, $tokenData, now()->parse($tokenData['expires_at']));
        
        Log::info('Complaint token used', [
            'token' => $token,
            'usage_count' => $tokenData['used_count'],
            'type' => $tokenData['type'],
        ]);
        
        return [
            'valid' => true,
            'data' => $tokenData,
        ];
    }
    
    /**
     * Get token statistics
     */
    public static function getTokenStats($token): ?array
    {
        return Cache::get('complaint_token_' . $token);
    }
    
    /**
     * Generate different types of tokens
     */
    public static function generateQRCodeToken(): array
    {
        return self::generateToken('qr-code', 720); // 30 days
    }
    
    public static function generateEmailToken(): array
    {
        return self::generateToken('email', 168); // 7 days
    }
    
    public static function generateSingleUseToken(): array
    {
        return self::generateToken('single-use', 24); // 1 day, single use
    }
    
    public static function generateStaffToken(): array
    {
        return self::generateToken('staff', 8760); // 1 year
    }
}