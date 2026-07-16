<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Locale Test</title>
</head>
<body>
    <h1>@lang('messages.welcome')</h1>
    <p>Current Locale: {{ app()->getLocale() }}</p>
    <p>Session Locale: {{ session('locale') }}</p>
    <p>Cookie Locale: {{ request()->cookie('locale') }}</p>

    <h2>Translations:</h2>
    <ul>
        <li>Welcome: @lang('messages.welcome')</li>
        <li>Name: @lang('messages.name')</li>
    </ul>

    <a href="{{ route('language.switch', 'en') }}">English</a> |
    <a href="{{ route('language.switch', 'ur') }}">اردو</a>

    <hr>
    <a href="/force-urdu-test">Test Force Urdu API</a>
</body>
</html>
