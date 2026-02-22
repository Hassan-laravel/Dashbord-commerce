<?php
// app/Http/Resources/SettingResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // No need to read headers or set locale here; the Middleware has already handled it.

        // Use app()->getLocale() to retrieve the current application locale.
        $locale = app()->getLocale();

        return [
            'site_name'        => $this->translateOrNew($locale)->site_name,
            'site_description' => $this->translateOrNew($locale)->site_description,
            'copyright'        => $this->translateOrNew($locale)->copyright,
            'email'            => $this->site_email,
            'phone'            => $this->site_phone,
            'logo'             => $this->site_logo_url,
            'maintenance_mode' => (bool) $this->maintenance_mode,
        ];
    }
}
