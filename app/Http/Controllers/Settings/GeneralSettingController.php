<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\UpdateGeneralSetting;
use App\DTOs\GeneralSettingDto;
use App\Http\Requests\Settings\StoreGeneralSettingRequest;
use App\Http\Resources\GeneralSettingResource;
use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class GeneralSettingController
{
    public function edit(): Response
    {
        Gate::authorize('access', GeneralSetting::class);

        return Inertia::render('settings/GeneralSetting', [
            'setting' => GeneralSettingResource::make(
                GeneralSetting::query()->with('media')->first(),
            ),
        ]);
    }

    public function update(StoreGeneralSettingRequest $request, UpdateGeneralSetting $action): RedirectResponse
    {
        Gate::authorize('access', GeneralSetting::class);

        /** @var array{site_title: string, date_format: string, developed_by: string|null, site_logo: UploadedFile|null, logo_removed: bool} $data */
        $data = $request->validated();

        $action->handle(GeneralSettingDto::formArray($data));

        return to_route('general-settings.edit')
            ->with('success', 'General setting update successfully!');
    }
}
