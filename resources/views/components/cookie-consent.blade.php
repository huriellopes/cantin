@php
    $gaId = config('services.ga.id');
    $adsId = config('services.google_ads.id');
    $primaryId = $gaId ?: $adsId;
@endphp

@if ($primaryId)
    <script>
        // Carrega o Google Analytics/Ads SOMENTE após consentimento de cookies.
        window.loadCantinAnalytics = function () {
            if (window.__cantinAnalyticsLoaded) return;
            window.__cantinAnalyticsLoaded = true;

            var s = document.createElement('script');
            s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=' + @json($primaryId);
            document.head.appendChild(s);

            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            window.gtag = gtag;
            gtag('js', new Date());
            @if ($gaId)
                gtag('config', @json($gaId));
            @endif
            @if ($adsId)
                gtag('config', @json($adsId));
            @endif
        };

        // Já consentiu numa visita anterior? Carrega imediatamente.
        if (localStorage.getItem('cookie-consent') === 'accepted') {
            window.loadCantinAnalytics();
        }
    </script>
@endif

<div
    x-data="{ show: ! localStorage.getItem('cookie-consent') }"
    x-show="show"
    x-cloak
    x-transition.opacity
    class="fixed inset-x-0 bottom-0 z-[60] px-4 pb-4"
>
    <div class="mx-auto flex max-w-4xl flex-col gap-4 rounded-2xl border border-slate-200 bg-white/95 p-5 shadow-2xl backdrop-blur sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-600">
            {{ __('cookies.message') }}
            {!! __('cookies.see_more', ['link' => '<a href="'.route('site.privacy').'" class="font-medium text-violet-600 hover:underline">'.__('cookies.privacy_link').'</a>']) !!}
        </p>
        <div class="flex shrink-0 gap-3">
            <button type="button"
                    @click="localStorage.setItem('cookie-consent','rejected'); show = false"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                {{ __('cookies.reject') }}
            </button>
            <button type="button"
                    @click="localStorage.setItem('cookie-consent','accepted'); show = false; window.loadCantinAnalytics && window.loadCantinAnalytics()"
                    class="rounded-lg bg-gradient-to-r from-violet-600 to-pink-500 px-4 py-2 text-sm font-semibold text-white transition hover:brightness-110">
                {{ __('cookies.accept') }}
            </button>
        </div>
    </div>
</div>
