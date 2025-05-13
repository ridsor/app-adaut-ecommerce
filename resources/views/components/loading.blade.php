<div id="loadingAnimation"></div>
@push('scripts')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js'></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('loadingAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/assets/animations/loading.json' // lottie file path
        })
    </script>
@endpush
