@component('mail::message')
# Welcome to Stealth
<br><br>
Hello {{ $user->first_name }}! Your new account is ready for you to access.
<br><br>

### Your account details:
@component('mail::panel')
Email: <strong>{{ $user->email }}</strong><br>
Password: <strong>{{ $password }}</strong>
@endcomponent

<!-- Link to dashboard -->
@component('mail::button', ['url' => route('home'), 'color' => 'primary'])
<x-svg.login class="dashboard-link__svg"/>
<span>Go to your dashboard</span>
@endcomponent


Thanks,<br>
<div class="signature">
<img class="signature__company-logo"
src="{{ asset('/assets/STEALTH-logo-black.svg') }}"
alt="Stealth Media">
</div>

@component('mail::subcopy')
    <small>If you are unable to click the button above, paste this link in your browser to view your dashboard: <a href="{{ route('home') }}">{{ route('home') }}</a></small>
@endcomponent
@endcomponent
