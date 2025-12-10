<?php

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('admin_log')) {
    function admin_log($action, $data = [], $eventType = 'model')
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) return;

        AdminActivityLog::create([
            'admin_id' => $admin->id,
            'event_type' => $eventType, // 'auth', 'status-change', 'custom'
            'model' => $data['model'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'action' => $action,
            'previous_data' => !empty($data['previous']) ? json_encode($data['previous']) : null,
            'new_data' => !empty($data['new']) ? json_encode($data['new']) : null,
            'changes' => !empty($data['changes']) ? json_encode($data['changes']) : null,
            'ip_address' => request()->ip(),
            'user_agent' => json_encode(getBrowserInfo(request()->header('User-Agent'))),
        ]);
    }

    function getBrowserInfo($userAgent = null): array
    {
        $ua = $userAgent ?? request()->header('User-Agent');

        $info = [
            'browser' => 'Unknown',
            'version' => null,
            'os' => 'Unknown',
            'platform' => null, // optional: mobile/desktop
        ];

        // Detect OS
        if (preg_match('/Windows NT 10.0/', $ua)) $info['os'] = 'Windows 10';
        elseif (preg_match('/Windows NT 6.3/', $ua)) $info['os'] = 'Windows 8.1';
        elseif (preg_match('/Windows NT 6.2/', $ua)) $info['os'] = 'Windows 8';
        elseif (preg_match('/Windows NT 6.1/', $ua)) $info['os'] = 'Windows 7';
        elseif (preg_match('/Mac OS X ([\d_]+)/', $ua, $m)) $info['os'] = 'Mac OS X ' . str_replace('_', '.', $m[1]);
        elseif (preg_match('/Linux/', $ua)) $info['os'] = 'Linux';
        elseif (preg_match('/Android/', $ua)) $info['os'] = 'Android';
        elseif (preg_match('/iPhone|iPad|iPod/', $ua)) $info['os'] = 'iOS';

        // Detect browser and version
        if (preg_match('/Edg\/([0-9\.]+)/', $ua, $m)) {
            $info['browser'] = 'Edge';
            $info['version'] = $m[1];
        } elseif (preg_match('/OPR\/([0-9\.]+)/', $ua, $m)) {
            $info['browser'] = 'Opera';
            $info['version'] = $m[1];
        } elseif (preg_match('/Chrome\/([0-9\.]+)/', $ua, $m)) {
            $info['browser'] = 'Chrome';
            $info['version'] = $m[1];
        } elseif (preg_match('/Firefox\/([0-9\.]+)/', $ua, $m)) {
            $info['browser'] = 'Firefox';
            $info['version'] = $m[1];
        } elseif (preg_match('/Version\/([0-9\.]+).*Safari/', $ua, $m)) {
            $info['browser'] = 'Safari';
            $info['version'] = $m[1];
        }

        // Detect platform type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/', $ua)) {
            $info['platform'] = 'Mobile';
        } else {
            $info['platform'] = 'Desktop';
        }

        return $info;
    }


}
