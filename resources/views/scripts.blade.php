{{-- Global configuration object --}}
@php

    $config = [
    'appName' => config('app.name'),
    'siteUrl' =>  config('app.siteUrl'),
    'socialLogin' => config('services.social_auth'),
    'paypal' => [
      'env'       =>  config('services.paypal.env'),
      'sandbox'   =>  config('services.paypal.client_id_sandbox'),
      'live'      =>  config('services.paypal.client_id_live')
    ],
    'recaptcha' => config('services.google.recaptcha.site_key'),
];
@endphp
<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {"colors":{"state":{"brand":"#374afb","light":"#ffffff","dark":"#282a3c","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
</script>
<!-- end::Global Config -->
<script>window.config = @json($config);</script>
<script src="{{ mix('js/app.js') }}"></script>
