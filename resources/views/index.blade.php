<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Central de Jogador">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">        <!--end::Fonts -->
    <!-- Hotjar Tracking Code for http://localhost:8000 -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1527693,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    <script type="text/javascript">
    !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t,e){var n=document.createElement("script");n.type="text/javascript";n.async=!0;n.src="https://cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(n,a);analytics._loadOptions=e};analytics.SNIPPET_VERSION="4.1.0";
    analytics.load("4BUNFnDjmBE4SSXZZeC5XTr5rtlLuCDd");
    }}();
  </script>
  <!-- fontawesome -->
  <link rel="stylesheet" href="{{ asset('css/fonts.bundle.css') }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body  class="kt-header--fixed kt-header--minimize-topbar kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent"  >
<noscript>
<div class="no-script-box">
    <div class="no-script-box-container">
        <div class="no-script-message">
            Oops! <a target="_blank" href="https://www.enable-javascript.com/pt/" rel="nofollow">Habilite o javascript</a> no seu navegador para poder utilizar
            a Central de Jogador.
        </div>
    </div>
    <style>.no-script-box {display: block !important;}</style>
</div>
</noscript>
<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed " >
    <div class="kt-header-mobile__logo">
        <a href="#">
            <img alt="Logo" src="https://painel-pw4fun.s3.sa-east-1.amazonaws.com/logo.png"/>
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more-1"></i></button>
    </div>
</div>
<!-- end:: Header Mobile -->
<div id="app" class="kt-grid kt-grid--hor kt-grid--root kt-page">
</div>
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="flaticon2-arrow-up"></i>
</div>
@include('scripts')
<script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
</body>

</html>