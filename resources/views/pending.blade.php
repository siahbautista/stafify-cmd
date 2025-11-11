<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending - Stafify CRM</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Account Pending Approval</h2>
                <p class="text-gray-600 mb-6">
                    Your account is currently pending approval from an administrator. 
                    You will be notified once your account has been approved.
                </p>
                <div class="space-y-4">
                    <p class="text-sm text-gray-500">
                        <strong>Account Details:</strong><br>
                        Name: {{ Auth::user()->full_name }}<br>
                        Email: {{ Auth::user()->user_email }}<br>
                        Username: {{ Auth::user()->user_name }}
                    </p>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-hover transition-colors">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
