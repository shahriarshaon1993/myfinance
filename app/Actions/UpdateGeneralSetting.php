<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\GeneralSettingDto;
use App\Models\GeneralSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class UpdateGeneralSetting
{
    public function handle(GeneralSettingDto $settingDto): GeneralSetting
    {
        return DB::transaction(function () use ($settingDto): GeneralSetting {
            $payload = $settingDto->toArray();
            $setting = GeneralSetting::query()->first();

            $data = [
                'site_title' => $payload['site_title'],
                'date_format' => $payload['date_format'],
                'developed_by' => $payload['developed_by'],
            ];

            if ($setting) {
                $setting->update($data);
            } else {
                $setting = GeneralSetting::create($data);
            }

            // Handle media (site logo)
            $this->handleSiteLogo($setting, $payload);

            // Clear cache and update runtime config
            $this->refreshAppConfig($setting);

            return $setting;
        });
    }

    /**
     * @param  array{site_title: string, date_format: string, developed_by: string|null, site_logo: UploadedFile|null, logo_removed: bool}  $payload
     */
    private function handleSiteLogo(GeneralSetting $setting, array $payload): void
    {
        if (! empty($payload['logo_removed'])) {
            $setting->clearMedia('site_logo');
        }

        if (! empty($payload['site_logo'])) {
            $setting->clearMedia('site_logo');
            $setting->addMedia($payload['site_logo'], 'site_logo');
        }
    }

    private function refreshAppConfig(GeneralSetting $setting): void
    {
        Cache::forget('general_settings');

        config([
            'app.timezone' => $setting->timezone,
            'app.site_title' => $setting->site_title,
            'app.date_format' => $setting->date_format,
        ]);

        if (! empty($setting->timezone)) {
            date_default_timezone_set($setting->timezone);
        }
    }
}
