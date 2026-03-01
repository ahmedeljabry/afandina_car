<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function edit(): View
    {
        $settings = Template::query()->latest('id')->first() ?? new Template();

        return view('pages.admin.website_settings.edit', [
            'settings' => $settings,
            'logoPreview' => $this->resolveAssetPath($settings->logo_path, asset('website/assets/img/logo.svg')),
            'darkLogoPreview' => $this->resolveAssetPath($settings->dark_logo_path ?? $settings->logo_path, asset('website/assets/img/logo.svg')),
            'faviconPreview' => $this->resolveAssetPath($settings->favicon_path, asset('website/assets/img/favicon.png')),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'logo_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,webp', 'max:10240'],
            'dark_logo_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,webp', 'max:10240'],
            'favicon_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,webp,ico', 'max:5120'],
        ]);

        $settings = Template::query()->latest('id')->first() ?? new Template();
        $settings->site_name = $validated['site_name'] ?? null;

        $this->replaceUploadedFile($request, $settings, 'logo_path', 'website-settings');
        $this->replaceUploadedFile($request, $settings, 'dark_logo_path', 'website-settings');
        $this->replaceUploadedFile($request, $settings, 'favicon_path', 'website-settings');

        $settings->save();

        return redirect()
            ->route('admin.website-settings.edit')
            ->with('success', __('Website settings updated successfully.'));
    }

    private function replaceUploadedFile(Request $request, Template $settings, string $field, string $directory): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        $existingPath = $settings->getAttribute($field);
        if (filled($existingPath) && !Str::startsWith($existingPath, ['http://', 'https://'])) {
            Storage::disk('public')->delete(ltrim(Str::replaceFirst('storage/', '', $existingPath), '/'));
        }

        $settings->setAttribute($field, $request->file($field)->store($directory, 'public'));
    }

    private function resolveAssetPath(?string $path, string $fallback): string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');
        if (Str::startsWith($normalizedPath, ['storage/', 'admin/', 'website/'])) {
            return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    }
}
