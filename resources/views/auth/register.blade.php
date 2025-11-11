<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/images/Archive-Favicon.png') }}"/>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        html, body {
            height: 100%;
        }
    </style>
    <title>User Registration</title>
</head>
<body class="bg-mesh-gradient">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
        <p class="loading-text neural-grotesk">Loading User Registration...</p>
    </div>

    <main class="flex overflow-y-auto items-center justify-center h-full" id="mainContent" style="display: none;">
        <section class="flex justify-center items-center wide-container max-[840px]:flex-col max-[840px]:!w-full !mt-10 !mb-10">
            <div class="flex flex-col justify-center gap-6 z-1 w-[575px]">
                <div class="flex flex-col gap-20">
                    <div class="flex flex-col items-center gap-[10px] logo-wrapper">
                        <img src="{{ asset('assets/images/Stafify-Logo.png') }}" alt="Stafify Logo" class="w-[205px]" onclick="window.location.href='/';" style="cursor: pointer;">
                        <p class="text-zinc-200 mt-2 text-center">CRM Management</p>
                    </div>
                    <div class="flex flex-col gap-[20px] bg-white !p-[40px] rounded-[20px]">
                        <div class="flex flex-col gap-3">
                            <h1 class="text-3xl font-600 text-dark text-left mt-5 font-bold">User Registration</h1>
                            <p class="text-zinc-600 mt-2">Please fill in the details below.</p>
                            <div class="step-indicator">
                                <div class="step-circle active" id="step-circle-1">1</div>
                                <div class="step-circle" id="step-circle-2">2</div>
                                <div class="step-circle" id="step-circle-3">3</div>
                            </div>
                        </div>
                        <form class="flex flex-col gap-7" id="registrationForm" method="POST" autocomplete="off" onsubmit="appendFullName(); appendFullAddress();">
                            @csrf
                            
                            <!-- Step 1: Personal Details -->
                            <div class="step active" id="step1">
                                <div class="flex flex-col gap-4 !mb-[40px]">
                                    <!-- Username Field -->
                                    <input type="text" id="username" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="user_name" placeholder="Enter your Username" required>
                                    
                                    <!-- First Name & Last Name Fields -->
                                    <div class="flex gap-4">
                                        <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px] w-1/2" name="first_name" id="firstName" placeholder="First Name" required>
                                        <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px] w-1/2" name="last_name" id="lastName" placeholder="Last Name" required>
                                    </div>

                                    <!-- Hidden full_name field -->
                                    <input type="hidden" name="full_name" id="fullName">

                                    <!-- Email Field -->
                                    <input type="email" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="email" placeholder="Enter your Email" required>

                                    <!-- Phone Number -->
                                    <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="phone_number" placeholder="Enter your Phone Number" required>
                                </div>
                                
                                <div class="flex gap-4 mt-6">
                                    <button type="button" class="primary-button w-full" onclick="nextStep(1, 2)">Next Step</button>
                                </div>
                            </div>

                            <!-- Step 2: Company and Address -->
                            <div class="step" id="step2">
                                <div class="flex flex-col gap-4 !mb-[40px]">
                                    <!-- Company -->
                                    <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="company" placeholder="Enter your Company Name" required>
                                    
                                    <!-- Address Line 1 -->
                                    <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="address_line1" id="addressLine1" placeholder="Address Line 1" required>
                                    
                                    <!-- Address Line 2 (Optional) -->
                                    <input type="text" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px]" name="address_line2" id="addressLine2" placeholder="Address Line 2 (Optional)">
                                    
                                    <!-- Hidden full address field -->
                                    <input type="hidden" name="address" id="fullAddress">
                                    
                                    <!-- Country Selection -->
                                    <div class="relative">
                                        <select id="country" name="country" class="!px-4 !py-3 border-1 border-zinc-400 rounded-[7px] w-full" required>
                                            <option value="" disabled selected>Select your Country</option>
                                        </select>
                                        <div id="selectedCountryFlag" class="absolute left-4 top-1/2 transform -translate-y-1/2" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="flex gap-4 mt-6">
                                    <button type="button" class="secondary-button w-1/2" onclick="prevStep(2, 1)">Previous</button>
                                    <button type="button" class="primary-button w-1/2" onclick="nextStep(2, 3)">Next Step</button>
                                </div>
                            </div>

                            <!-- Step 3: Password -->
                            <div class="step" id="step3">
                                <div class="flex flex-col gap-4 !mb-[40px]">
                                    <!-- Password Field -->
                                    <div class="relative">
                                        <input type="password" class="!px-4 !py-3 pr-12 border-1 border-zinc-400 rounded-[7px] password-field w-full" name="password" id="password" placeholder="Enter your Password" required>
                                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-sm text-zinc-600">Show</button>
                                    </div>
                                    
                                    <!-- Confirm Password Field -->
                                    <div class="relative">
                                        <input type="password" class="!px-4 !py-3 pr-12 border-1 border-zinc-400 rounded-[7px] password-field w-full" name="confirm_password" id="confirm_password" placeholder="Confirm your Password" required>
                                        <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-sm text-zinc-600">Show</button>
                                    </div>
                                </div>

                                <div class="flex gap-4 mt-6">
                                    <button type="button" class="secondary-button w-1/2" onclick="prevStep(3, 2)">Previous</button>
                                    <button type="submit" class="primary-button w-1/2">Sign Up</button>
                                </div>
                            </div>
                        </form>
                        <p class="text-center text-zinc-600">Already have an account? <a href="{{ route('login') }}" class="text-accent font-500">Sign In</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function appendFullName() {
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            document.getElementById('fullName').value = firstName + ' ' + lastName;
        }

        function appendFullAddress() {
            const addressLine1 = document.getElementById('addressLine1').value.trim();
            const addressLine2 = document.getElementById('addressLine2').value.trim();
            const fullAddress = addressLine2 ? addressLine1 + ', ' + addressLine2 : addressLine1;
            document.getElementById('fullAddress').value = fullAddress;
        }

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

        function nextStep(currentStep, nextStep) {
            // Validate current step
            if (!validateStep(currentStep)) {
                return false;
            }
            
            // Hide current step
            document.getElementById('step' + currentStep).classList.remove('active');
            
            // Show next step
            document.getElementById('step' + nextStep).classList.add('active');
            
            // Update step indicators
            document.getElementById('step-circle-' + currentStep).classList.remove('active');
            document.getElementById('step-circle-' + currentStep).classList.add('completed');
            document.getElementById('step-circle-' + nextStep).classList.add('active');
        }

        function prevStep(currentStep, prevStep) {
            // Hide current step
            document.getElementById('step' + currentStep).classList.remove('active');
            
            // Show previous step
            document.getElementById('step' + prevStep).classList.add('active');
            
            // Update step indicators
            document.getElementById('step-circle-' + currentStep).classList.remove('active');
            document.getElementById('step-circle-' + prevStep).classList.remove('completed');
            document.getElementById('step-circle-' + prevStep).classList.add('active');
        }

        function validateStep(step) {
            let isValid = true;
            
            if (step === 1) {
                // Validate Step 1 fields
                const username = document.getElementById('username').value.trim();
                const firstName = document.getElementById('firstName').value.trim();
                const lastName = document.getElementById('lastName').value.trim();
                const email = document.querySelector('input[name="email"]').value.trim();
                const phone = document.querySelector('input[name="phone_number"]').value.trim();
                
                if (!username || !firstName || !lastName || !email || !phone) {
                    alert('Please fill in all required fields.');
                    isValid = false;
                }
            } else if (step === 2) {
                // Validate Step 2 fields
                const company = document.querySelector('input[name="company"]').value.trim();
                const addressLine1 = document.getElementById('addressLine1').value.trim();
                const country = document.getElementById('country').value;
                
                if (!company || !addressLine1 || !country) {
                    alert('Please fill in all required fields.');
                    isValid = false;
                }
            }
            
            return isValid;
        }

        // Populate countries dropdown using RestCountries API
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country');
            
            // Add padding to the select field to make room for the flag
            countrySelect.style.paddingLeft = '32px';
            
            // Create a container for all country data
            let countryData = [];
            
            fetch('https://restcountries.com/v3.1/all')
                .then(response => response.json())
                .then(data => {
                    // Sort countries alphabetically
                    const sortedCountries = data.sort((a, b) => {
                        return a.name.common.localeCompare(b.name.common);
                    });
                    
                    // Store country data with flags
                    countryData = sortedCountries.map(country => ({
                        name: country.name.common,
                        flag: country.flags.svg || country.flags.png
                    }));
                    
                    // Populate the select with countries
                    countryData.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.name;
                        option.textContent = country.name;
                        option.setAttribute('data-flag', country.flag);
                        countrySelect.appendChild(option);
                    });
                    
                    // Add event listener for country selection
                    countrySelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const flagUrl = selectedOption.getAttribute('data-flag');
                        const flagContainer = document.getElementById('selectedCountryFlag');
                        
                        if (flagUrl && this.value !== '') {
                            flagContainer.innerHTML = `<img src="${flagUrl}" class="country-flag" alt="${this.value} flag">`;
                            flagContainer.style.display = 'block';
                            this.style.paddingLeft = '32px';
                        } else {
                            flagContainer.style.display = 'none';
                            this.style.paddingLeft = '16px';
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching countries:', error);
                    
                    // Fallback: Add some common countries if API fails
                    const fallbackCountries = [
                        "United States", "Canada", "United Kingdom", "Australia", 
                        "Germany", "France", "Japan", "China", "India", "Brazil"
                    ];
                    
                    fallbackCountries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country;
                        option.textContent = country;
                        countrySelect.appendChild(option);
                    });
                });

            // Handle form submission
            document.getElementById('registrationForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("register") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert('An error occurred. Please try again.');
                }
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
