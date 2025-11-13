@extends('layouts.client-app')

@section('title', 'Client Dashboard')

@section('content')
<main>
    <div class="rounded-shadow-box bg-white p-6 shadow-lg rounded-lg">
        <div class="flex flex-col gap-5">
            <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Quick Links</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- THIS LINK IS NOW UPDATED --}}
                <a class="block w-full text-center p-4 bg-blue-600 text-white rounded-lg shadow font-medium hover:bg-blue-700 transition-colors" 
                   href="{{ route('time-tracking') }}">
                   Attendance Tracker
                </a>
                
                <a class="block w-full text-center p-4 bg-blue-600 text-white rounded-lg shadow font-medium hover:bg-blue-700 transition-colors" 
                   href="https://docs.google.com/forms/d/e/1FAIpQLSd8hevC7VEYYcOUjoOIJcw_1H4bueOmilf8sbqEC3dNaKRhNA/viewform" 
                   target="_blank" rel="noopener noreferrer">
                   201 Files Submission
                </a>
                
                <a class="block w-full text-center p-4 bg-gray-400 text-white rounded-lg shadow cursor-not-allowed" 
                   href="#" 
                   onclick="event.preventDefault(); alert('This feature is not yet available.');">
                   E-Leave Submission
                </a>
                
                <a class="block w-full text-center p-4 bg-gray-400 text-white rounded-lg shadow cursor-not-allowed" 
                   href="#" 
                   onclick="event.preventDefault(); alert('This feature is not yet available.');">
                   E-NTE Submission
                </a>

                {{-- THIS LINK IS NOW UPDATED --}}
                <a class="block w-full text-center p-4 bg-blue-600 text-white rounded-lg shadow font-medium hover:bg-blue-700 transition-colors" 
                   href="{{ route('workforce-records') }}" 
                   target="_blank">
                   Workforce Records
                </a>
            </div>
        </div>
    </div>
</main>
@endsection