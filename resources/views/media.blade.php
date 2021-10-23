@include('layouts.dashboard')

<x-app-layout>
    <x-slot name="slot">
        <div class="container mx-auto px-4 sm:px-8 py-10">
                {{-- -- add media content here -- --}}
                {{ $media->links() }}
                <div>
                    <div class="grid grid-cols-2 gap-10">

                        @foreach ($formatted as $key => $mediaItem)
                                @foreach ($mediaItem as $item)
                                <div><iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $item }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>                                    
                                @endforeach                                
                        @endforeach
                      </div>                    
                </div>
        </div>
    </x-slot>
</x-app-layout>
