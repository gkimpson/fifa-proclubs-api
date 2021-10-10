<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div> --}}


    <!-- todo Use proper layouts for this later -->
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Matches</h2>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div x-data="{ show: false }" class="inline-block min-w-full shadow rounded-lg overflow-hidden">
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
                                    class="hidden sm:table-cell px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Away
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="px-2 py-2 md:px-5 md:py-5 flex-1">{{ $results->links() }}</div>
                            @foreach ($results as $key => $result)
                            {{-- @if ($loop->first)
                                This is the first iteration.
                            @endif
                        
                            @if ($loop->last)
                                This is the last iteration.
                            @endif --}}
                            
                            <tr class="md:hidden">
                                <td class="visible sm:table-cell bg-white text-sm"></td>
                                <td class="visible sm:table-cell text-center bg-white text-xs text-gray-500" colspan="2">{{ $result->match_date->diffForHumans() }}</td>
                                <td class="visible sm:table-cell bg-white text-sm"></td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2 md:px-5 md:py-5 border-b border-gray-200 bg-white text-sm w-2/5 
                                @if($result->outcome === 'homewin' && $result->home_team_id === $myClubId) bg-green-200 
                                @elseif($result->outcome === 'awaywin' && $result->home_team_id === $myClubId) bg-red-200 
                                @endif">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                                            <img class="w-full h-full rounded-full"
                                                src="{{$result->home_team_crest_url}}"
                                                alt="Home Team Crest" />
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
                                <td class="hidden md:table-cell border-b border-gray-200 bg-white text-xs text-center text-gray-500">
                                    @isset($result->media)
                                    <div class="flex">
                                        <div class="m-auto">
                                          <div class="shadow-md p-4 flex flex-row rounded-lg animate-bounce">
                                            <div class="bg-red-500 inline-block rounded-lg p-1 mr-1"></div>
                                            <b class="p-1">GOAAAAALLLL!</b>
                                          </div>
                                        </div>
                                      </div>             
                                      <p class="p-1"><a href="//www.youtube.com/watch?v={{$result->media}}" data-lity>Click to View Highlights</a><br></p>
                                      @endisset                       
                                    {{ $result->match_date->diffForHumans() }}<br><button @click="show = !show">Insights</button>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap text-center">
                                        {{ $result->away_team_goals }}
                                    </p>
                                </td>
                                <td class="px-2 py-2 md:px-5 md:py-5 border-b border-gray-200 bg-white text-sm w-2/5
                                @if($result->outcome === 'awaywin' && $result->away_team_id === $myClubId) bg-green-200
                                @elseif($result->outcome === 'homewin' && $result->away_team_id === $myClubId) bg-red-200 
                                @endif">
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
                                                src="{{$result->away_team_crest_url}}"
                                                alt="Away Team Crest" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td x-show="show" class="text-center text-xs" colspan="5">--data here--</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                <div class="px-2 py-2 md:px-5 md:py-5 flex-1">{{ $results->links() }}</div></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
