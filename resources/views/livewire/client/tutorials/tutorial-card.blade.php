<div class="relative rounded-lg border border-gray-200 bg-white pr-6 pl-8 py-5 shadow-sm w-full">
    <!-- Card Icon -->
    <div
        class="rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center absolute h-8 w-8 text-center"
        style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.tutorial class="h-4 w-4 text-gray-500"/>
    </div>

    <div class="flex flex-col space-y-3">
        <div class="flex-1 flex-col space-y-3 min-w-0">
            <h2 class="text-xl font-bold text-center">{{ $tutorial->title }}</h2>

            <div class="mx-auto h-auto w-full relative overflow-hidden rounded-md shadow-md border-2 border-gray-100">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6">
                    <x-svg.spinner class="text-secondary-500 w-6 h-6"/>
                </div>
                <iframe
                    style="position:relative; width: 100%; height: 100%; min-height: 22rem;"
                    width="600"
                    height="300"
                    src="https://www.loom.com/embed/{{ $embed_id }}?hide_share=true&hideEmbedTopBar=true&autoplay=0"
                    title="{{ $tutorial->title }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                >
                </iframe>
            </div>
            @if($tutorial->body)
                <div class="flex space-x-2 bg-gray-50 p-3 rounded-md">
                    <x-svg.info-circle class="h-4 w-4 mt-0.5 text-gray-500"/>
                    <p>{{ $tutorial->body }}</p>
                </div>
            @endif
        </div>
    </div><!-- top container ends -->
</div>
