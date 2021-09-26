<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>


    <!-- todo Use proper layouts for this later -->
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Matches Schedule</h2>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Home
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Away
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $key => $result)
                            {{-- @if ($loop->first)
                                This is the first iteration.
                            @endif
                        
                            @if ($loop->last)
                                This is the last iteration.
                            @endif --}}

                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm w-2/5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                                            <img class="w-full h-full rounded-full"
                                                src="https://images.unsplash.com/photo-1601046668428-94ea13437736?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2167&q=80"
                                                alt="" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                @php 
                                                    $p = json_decode($result->properties);
                                                    print($p->clubs[0]->name);
                                                    // todo: fix this later so there isn't a need to json_decode this in blade
                                                @endphp
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap text-center">{{ $result->home_team_goals }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white text-xs">
                                    ID: {{ $result->match_id}}<br>
                                    {{ $result->match_date }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap text-center">
                                        {{ $result->away_team_goals }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm w-2/5">
                                    <div class="flex items-center float-right">
                                        <div class="mr-3">
                                            <p class="text-gray-900 whitespace-no-wrap text-right">
                                                @php 
                                                    $p = json_decode($result->properties);
                                                    print($p->clubs[1]->name);  
                                                    // todo: fix this later so there isn't a need to json_decode this in blade
                                                @endphp
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                                            <img class="w-full h-full rounded-full"
                                                src="https://images.unsplash.com/photo-1601046668428-94ea13437736?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2167&q=80"
                                                alt="" />
                                        </div>
                                    </div>
                                </td>
                            </tr>                              
                            
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
