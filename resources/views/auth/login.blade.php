<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/images/Archive-Favicon.png') }}"/>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swal/swal.css') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Stafify UMS - Login</title>
</head>
<body class="bg-mesh-gradient">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
        <p class="loading-text neural-grotesk">Loading Stafify UMS - Login...</p>
    </div>

    <main class="flex overflow-hidden items-center justify-center" id="mainContent" style="display: none;">
        <section class="flex justify-center items-center wide-container max-[840px]:flex-col max-[840px]:!w-full h-screen">
            <div class="flex flex-col justify-center gap-6 z-1 w-[475px]">
                <div class="flex flex-col gap-30">
                    <div class="flex flex-col items-center gap-[10px] logo-wrapper">
                        <img src="{{ asset('assets/images/Stafify-Logo.png') }}" alt="Stafify Logo" class="w-[205px]" onclick="window.location.href='/';" style="cursor: pointer;">
                        <p class="text-zinc-200 mt-2 text-center">User Management System</p>
                    </div>
                    <div class="flex flex-col gap-[40px] bg-white !p-[40px] rounded-[20px]">
                        <div class="flex flex-col gap-3">
                            <h1 class="text-3xl text-dark text-left mt-5 !font-bold">User Login</h1>
                            <p class="text-zinc-600 mt-2">Please enter your credentials below.</p>
                        </div>
                        <form id="loginForm" class="flex flex-col gap-7" method="POST" autocomplete="off">
                            @csrf
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col gap-3">
                                    <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="uid" id="uid" placeholder="Username or Email" required>
                                    
                                    <div class="relative">
                                        <input type="password" class="!px-4 !py-3 pr-12 border-1 border-zinc-400 rounded-[7px] password-field w-full" name="password" id="pass" placeholder="Password" required>
                                        <button type="button" onclick="togglePassword('pass')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-sm text-zinc-600">Show</button>
                                    </div>
                                </div>
                                <a href="#" class="text-accent text-right text-sm font-500">Forgot Password?</a>
                            </div>
                            
                            <div class="flex gap-4">
                                <button type="submit" class="primary-button pro-sub w-full">Sign In</button>
                            </div>
                        </form>
                        <p class="text-center text-zinc-600">Don't have an account yet? <a href="{{ route('register') }}" class="text-accent font-500">Sign Up</a></p>
                    </div>
                    <p class="copyright text-center text-white animate__animated animate__fadeInUp delay-2s">©<span class="yearDisplay">Loading...</span> Stafify BPO. All rights reserved</p>
                </div>
            </div>
        </section>
    </main>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const btn = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = 'Hide';
            } else {
                input.type = 'password';
                btn.textContent = 'Show';
            }
        }
        
        // Get the current year
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".yearDisplay").textContent = new Date().getFullYear();
        });

        // Handle form submission
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '{{ route("login") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show success message and redirect
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Login successful. Redirecting...',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.href = response.redirect;
                            });
                        } else {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message || 'Something went wrong!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred. Please try again.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        // Loading screen functionality
        window.addEventListener("load", () => {
            const loadingScreen = document.getElementById("loadingScreen");
            
            // Add hidden class to start fade-out
            loadingScreen.classList.add("hidden");

            // Wait for the fade-out transition to complete before hiding completely
            loadingScreen.addEventListener("transitionend", () => {
                loadingScreen.style.display = "none";
            });

            // Show main content
            document.getElementById("mainContent").style.display = "flex";
        });
    </script>
</body>
</html>
