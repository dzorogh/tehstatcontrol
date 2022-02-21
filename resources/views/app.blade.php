<!doctype html>
<html lang="ru" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Техстат</title>

        <link rel="icon" href="{{ asset('images/cropped-ll-32x32.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('images/cropped-ll-192x192.png') }}" sizes="192x192" />
        <link rel="apple-touch-icon" href="{{ asset('images/cropped-ll-180x180.png') }}" />

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body class="min-h-full h-full">

        <div id="app" class="min-h-full h-full"></div>

        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
          (function (m, e, t, r, i, k, a) {
            m[i]   = m[i] || function () {(m[i].a = m[i].a || []).push(arguments);};
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
              k, a);
          })
          (window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');

          ym(86667607, 'init', {
            clickmap:            true,
            trackLinks:          true,
            accurateTrackBounce: true,
            webvisor:            true,
            userParams:          {
              email: '{{ request()->input('email', '') }}',
              {{--UserID: '{{ request()->input('email', '') }}',--}}
            },
          });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/86667607" style="position:absolute; left:-9999px;" alt="" /></div>
        </noscript>
        <!-- /Yandex.Metrika counter -->

    </body>
</html>
