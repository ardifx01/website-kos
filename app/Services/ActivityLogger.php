<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($event, $tableName, $recordId = null, $changes = [], $description = null)
    {
        try {
            $user = Auth::user();
            
            ActivityLog::create([
                'user_id' => $user ? $user->id : null,
                'event' => $event,
                'table_name' => $tableName,
                'record_id' => $recordId,
                'changes' => $changes,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'created_by' => $user ? $user->id : null,
                'created_date' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error jika diperlukan, tapi jangan sampai mengganggu proses utama
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    public static function logLogin($userId)
    {
        self::log('login', 'users', $userId, [], 'User logged in');
    }

    public static function logLogout($userId)
    {
        self::log('logout', 'users', $userId, [], 'User logged out');
    }

    public static function logCreate($tableName, $recordId, $data = [])
    {
        self::log('created', $tableName, $recordId, ['new_data' => $data], 'Record created');
    }

    public static function logUpdate($tableName, $recordId, $oldData, $newData)
    {
        $changes = [];
        foreach ($newData as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] != $value) {
                $changes[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value
                ];
            }
        }
        
        if (!empty($changes)) {
            self::log('updated', $tableName, $recordId, $changes, 'Record updated');
        }
    }

    public static function logDelete($tableName, $recordId, $data = [])
    {
        self::log('deleted', $tableName, $recordId, ['deleted_data' => $data], 'Record deleted');
    }

    public static function logView($tableName, $recordId)
    {
        self::log('viewed', $tableName, $recordId, [], 'Record viewed');
    }

    public static function logCustom($event, $tableName, $recordId = null, $changes = [], $description = null)
    {
        self::log($event, $tableName, $recordId, $changes, $description);
    }
}