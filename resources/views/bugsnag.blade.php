@if (config('bugsnag.api_key_js') !== '' && app()->environment('production'))
  <script src="//d2wy8f7a9ursnm.cloudfront.net/bugsnag-3.min.js" data-apikey="{{ config('bugsnag.api_key_js') }}"></script>
  <script>
    Bugsnag.releaseStage = "{{ config('app.env') }}";
    @if (Auth::check())
    Bugsnag.user = {!! json_encode(array_only(Auth::user(), ['id', 'name', 'email'])) !};
    @endif
  </script>
@endif
