@livewireScripts
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
{{--<script src="https://unpkg.com/moment"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<x-input.file-upload-scripts/>
@stack('scripts')
{{--<script>--}}
{{--    new Vue({--}}
{{--        data: function() {--}}
{{--            return {--}}
{{--                searchClient: algoliasearch(--}}
{{--                    '{{ config('scout.algolia.id') }}',--}}
{{--                    '{{ Algolia\ScoutExtended\Facades\Algolia::searchKey(App\Models\Client::class) }}',--}}
{{--                ),--}}
{{--                open: false,--}}
{{--            };--}}
{{--        },--}}
{{--        methods: {--}}
{{--          openSearch() {--}}
{{--              this.open = true;--}}
{{--              setTimeout(function () { /* .focus() won't work without this */--}}
{{--                  document.querySelector('#search-form input').focus();--}}
{{--              }, 10)--}}
{{--          }--}}
{{--        },--}}
{{--        el: '#search',--}}
{{--    });--}}


{{--</script>--}}
@yield('scripts.footer')
</body>
</html>
