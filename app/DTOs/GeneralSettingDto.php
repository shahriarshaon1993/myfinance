<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class GeneralSettingDto
{
    public function __construct(
        public string $siteTitle,
        public string $dateFormat = 'd M Y',
        public bool $logoRemoved = false,

        public ?string $developedBy = null,
        public ?UploadedFile $siteLogo = null,
    ) {
        //
    }

    /**
     * @param  array{site_title: string, date_format: string, logo_removed: bool, developed_by: string|null, site_logo: UploadedFile|null}  $data
     */
    public static function formArray(array $data): self
    {
        return new self(
            siteTitle: $data['site_title'],
            dateFormat: $data['date_format'],
            logoRemoved: (bool) $data['logo_removed'],
            developedBy: $data['developed_by'],
            siteLogo: $data['site_logo'],
        );
    }

    /**
     * @return array{site_title: string, date_format: string, developed_by: string|null, site_logo: UploadedFile|null, logo_removed: bool}
     */
    public function toArray(): array
    {
        return [
            'site_title' => $this->siteTitle,
            'date_format' => $this->dateFormat,
            'logo_removed' => $this->logoRemoved,
            'developed_by' => $this->developedBy,
            'site_logo' => $this->siteLogo,
        ];
    }
}
