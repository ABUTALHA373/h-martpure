<?php

namespace App;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogAdminActivity
{
    // Boot trait properly inside Eloquent models
    public static function bootLogAdminActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Log an activity for the model
     */
    protected function logActivity(string $action): void
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) return;

        $url = request()->fullUrl();
        $component = null;

        // Enhanced logging for Livewire
        if (request()->route() && request()->route()->named('livewire.update')) {
            $url = request()->header('Referer') ?? $url;
            
            // Try v3 snapshot parsing
            $snapshot = request()->input('components.0.snapshot');
            if ($snapshot) {
                $decoded = json_decode($snapshot, true);
                $component = $decoded['memo']['name'] ?? null;
            } else {
                 // Fallback for v2 or other structures
                $component = request()->input('fingerprint.name') ?? request()->input('components.0.name');
            }
        }

        AdminActivityLog::create([
            'admin_id' => $admin->id,
            'event_type' => 'model',
            'model' => get_class($this),
            'model_id' => $this->id,
            'action' => $action,
            'is_manual' => false,
            'request_route' => json_encode([
                'route' => request()->route() ? request()->route()->getName() : null,
                'url' => $url,
                'component' => $component
            ]),
            // 'url' and 'component' columns removed as per request

            // model before update
            'previous_data' => json_encode($this->getOriginal()),

            // model after update
            'new_data' => json_encode($this->toArray()),

            // only changed fields
            'changes' => json_encode($this->getDirty()),

            'ip_address' => request()->ip(),
            'user_agent' => json_encode(getBrowserInfo(request()->userAgent())),
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
