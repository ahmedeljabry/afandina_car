<?php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $template = Template::query()->latest('id')->first();
        $siteLogo = $this->resolveAssetPath(
            $template?->logo_path,
            asset('admin/dist/logo/website_logos/logo_light.svg')
        );

        return view('pages.admin.auth.login', compact('siteLogo'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $dashboardRoute = Route::has('admin.dashboard')
                ? route('admin.dashboard')
                : url('/admin/dashboard');

            return redirect()->intended($dashboardRoute);
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('admin/login');
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
