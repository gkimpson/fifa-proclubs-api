<x-app-layout :user="request()->route('user')">
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
            <div class="mx-auto px-4 sm:px-8 text-center">
                <p class="p-1"><a href="//www.youtube.com/watch?v=M82Eua9wkQc" data-lity>GOAL OF THE MONTH</a><br></p>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div x-data="{selected:null}" class="inline-block min-w-full shadow rounded-lg overflow-hidden">
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
                                <td class="visible sm:table-cell bg-white text-sm">
                                    <div class="mx-5 px-3 py-3">
                                            <img class="w-full h-full rounded-full"
                                                src="{{$result->home_team_crest_url}}"
                                                alt="Crest" />
                                    </div>
                                </td>
                                <td class="visible sm:table-cell text-center bg-white text-xs text-gray-500" colspan="2">
                                    {{ $result->match_date->diffForHumans() }}<br>@isset($result->match_data)
                                    <button @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                      </svg>
                                    </button>
                                    @endisset     
                                    @if (count($result->media_ids) > 0)
                                    <div class="flex flex-row">
                                      @foreach ($result->media_ids as $key => $media_id)                                        
                                            <div><a href="//www.youtube.com/watch?v={{ $media_id }}" data-lity>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            </a>
                                            </div>                                     
                                      @endforeach
                                    </div>     
                                    @endif                                
                                </td>
                                <td class="visible sm:table-cell bg-white text-sm">
                                    <div class="mx-5 px-3 py-3">
                                        <img class="w-full h-full rounded-full"
                                            src="{{$result->away_team_crest_url}}"
                                            alt="Crest" />
                                    </div>                                 
                                </td>
                            </tr>
                            <tr data-matchId="{{ $result->match_id }}">
                                <td class="px-2 py-2 md:px-5 md:py-5 border-b border-gray-200 bg-white text-sm w-2/5 
                                @if($result->outcome === 'homewin' && $result->home_team_id === $myClubId) bg-green-200 
                                @elseif($result->outcome === 'awaywin' && $result->home_team_id === $myClubId) bg-red-200 
                                @endif">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 hidden sm:table-cell">
                                            <img class="w-full h-full rounded-full"
                                                src="{{$result->home_team_crest_url}}"
                                                alt="Crest" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{-- {{ $result->properties['clubs'][0]['name'] }} --}} 
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
                                    @if (count($result->media_ids) > 0)
                                    <div class="flex flex-row">
                                      @foreach ($result->media_ids as $key => $media_id)                                        
                                            <div><a href="//www.youtube.com/watch?v={{ $media_id }}" data-lity>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            </a>
                                            </div>                                     
                                      @endforeach
                                    </div>     
                                    @endif  
                                                  
                                    <div>{{ $result->match_date->diffForHumans() }}</div>
                                    @isset($result->match_data)
                                    <button @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                      </svg>
                                    </button>
                                    @endisset
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
                                                {{-- {{ $result->properties['clubs'][1]['name'] }} --}}
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
                                                alt="Crest" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td x-cloak x-show="selected == {{ $loop->iteration }}" 
                                    {{-- x-transition:enter="transition ease-out origin-top-right duration-100"
                                    x-transition:enter-start="opacity-0 transform scale-10"
                                    x-transition:enter-end="opacity-100 transform scale-100" --}}
                                    {{-- x-transition:leave="transition origin-top-center ease-in duration-100"
                                    x-transition:leave-start="opacity-100 transform scale-10"
                                    x-transition:leave-end="opacity-0 transform scale-100 ease-in"                                     --}}
                                    class="text-center text-xs" colspan="5">
                                    @isset($result->match_data)
                                    {{-- todo: need to use components for this.. --}}
                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->shots }}</div>
                                        <div class="text-center text-xs md:text-sm">Shots on Target</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->shots }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->saves }}</div>
                                        <div class="text-center text-xs md:text-sm">Saves (Human GK)</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->saves }}</div>
                                    </div>                                    

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->redcards }}</div>
                                        <div class="text-center text-xs md:text-sm">Red Cards</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->redcards }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->tacklesmade }}</div>
                                        <div class="text-center text-xs md:text-sm">Tackles Made</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->tacklesmade }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->tackleattempts }}</div>
                                        <div class="text-center text-xs md:text-sm">Tackle Attempts</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->tackleattempts }}</div>
                                    </div>       
                                    
                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->assists }}</div>
                                        <div class="text-center text-xs md:text-sm">Assists</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->assists }}</div>
                                    </div>                                      

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->passesmade }}</div>
                                        <div class="text-center text-xs md:text-sm">Passes Made</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->passesmade }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->home_team_id]->passattempts }}</div>
                                        <div class="text-center text-xs md:text-sm">Pass Attempts</div>
                                        <div class="text-center text-xs md:text-sm">{{ $result->match_data[$result->away_team_id]->passattempts }}</div>
                                    </div> 

                                    <div class="grid grid-cols-3 gap-4 w-100 md:w-1/2 mx-auto border-b py-2">
                                        <div class="text-center text-xs md:text-sm">{{ round(($result->match_data[$result->home_team_id]->passesmade / $result->match_data[$result->home_team_id]->passattempts) * 100) }}%</div>
                                        <div class="text-center text-xs md:text-sm">Pass Completion %</div>
                                        <div class="text-center text-xs md:text-sm">{{ round(($result->match_data[$result->away_team_id]->passesmade / $result->match_data[$result->away_team_id]->passattempts) * 100) }}%</div>
                                    </div>                                                                       
                                    @endisset
                                </td>
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
