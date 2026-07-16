@foreach(config('app.available_locales') as $locale => $name)
    <a href="{{ route('language.switch', $locale) }}"
       class="{{ app()->getLocale() === $locale ? 'active' : '' }}"
       hx-get="{{ route('language.switch', $locale) }}"
       hx-target="body"
       hx-push-url="false">
        {{ strtoupper($locale) }}
    </a>
    @if(!$loop->last) | @endif
@endforeach
