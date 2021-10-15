@include('layouts.dashboard')

<x-app-layout>
    <x-slot name="slot">
        <div class="container mx-auto px-4 sm:px-8">
        
                -- add club content here --
                {{ $myClubId }} passed from club() in MyDashboardController.php

        </div>
    </x-slot>
</x-app-layout>
