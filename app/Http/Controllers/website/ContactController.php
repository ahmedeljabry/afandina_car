<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactWithUsMessage;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ContactController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale() ?? 'en';

        $contact = Contact::query()
            ->where('is_active', true)
            ->latest('id')
            ->first()
            ?? Contact::query()->latest('id')->first();

        $home = Home::query()
            ->with('translations')
            ->where('is_active', true)
            ->first()
            ?? Home::query()->with('translations')->latest('id')->first();

        $homeTranslation = $this->translationFor($home, $locale);

        return view('website.contact-us', compact(
            'contact',
            'homeTranslation',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactWithUsMessage::query()->create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
        ]);

        return back()->with('success', __('website.contact.form.success_message'));
    }

    private function translationFor($model, string $locale): mixed
    {
        if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
            return null;
        }

        return $model->translations->firstWhere('locale', $locale)
            ?? $model->translations->first();
    }
}
