<?php

use App\Http\Controllers\LemonCheckoutController;
use App\Livewire\Resume\CoverLetter;
use App\Livewire\Resume\ResumeAnalyzer;
use App\Livewire\Resume\ResumeTailor;
use App\Livewire\Settings\Subscriptions;
use Illuminate\Support\Facades\Route;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('homepages.welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('customers', 'homepages.customers')
    ->name('customers');

Route::view('features', 'homepages.features')
    ->name('features');

Route::view('pricing', 'homepages.pricing')
    ->name('pricing');

Route::view('privacy', 'homepages.privacy')
    ->name('privacy');

Route::view('terms', 'homepages.terms')
    ->name('terms');

Route::get('/checkout/start/{variant}', function ($variant) {
    session(['checkout_variant' => $variant]);

    if (auth()->check()) {
        $user = auth()->user();

        if ($user->subscriber?->hasAccess()) {
            return redirect()->route('subscriptions.edit');
        }

        return redirect()->route('checkout', $variant);
    }

    return redirect()->route('login');
})->name('checkout.start');

Route::get('/checkout/{variant}', [LemonCheckoutController::class, 'create'])
    ->middleware('auth')
    ->name('checkout');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::get('settings/subscriptions', Subscriptions::class)->name('subscriptions.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::middleware(['auth', 'subscribed'])->group(function () {
    Route::get('dashboard/resume-tailor', ResumeTailor::class)->name('resume.resume-tailor');
    Route::get('dashboard/resume-analyzer', ResumeAnalyzer::class)->name('resume.resume-analyzer');
    Route::get('dashboard/cover-letter', CoverLetter::class)->name('resume.cover-letter');
});
