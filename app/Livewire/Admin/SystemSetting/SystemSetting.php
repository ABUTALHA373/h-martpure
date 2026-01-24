<?php

namespace App\Livewire\Admin\SystemSetting;

use App\Models\SystemSetting as SystemSettingModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layout.admin')]
class SystemSetting extends Component
{
    #[Url]
    public $activeTab = 'general';
    public $settingValues = [];
    public $groups = [];

    public function mount()
    {
        $settings = SystemSettingModel::all();

        // Initialize values
        foreach ($settings as $setting) {
            $this->settingValues[$setting->key] = $setting->value;
        }
    }

    public function render()
    {
        // Fetch fresh settings grouped by the active tab
        $currentSettings = SystemSettingModel::where('group', $this->activeTab)
            ->orWhere(function ($q) {
                if ($this->activeTab === 'general') {
                    $q->whereNull('group');
                }
            })
            ->orderBy('id')
            ->get();

        return view('livewire.admin.system-setting.system-setting', [
            'currentSettings' => $currentSettings
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        foreach ($this->settingValues as $key => $value) {
            SystemSettingModel::where('key', $key)->update(['value' => $value]);
        }

        $this->dispatch('toast', type: 'success', title: 'Saved', message: 'Settings updated successfully.');
    }
}
