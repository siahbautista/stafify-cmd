@extends('layouts.client-app')

@section('title', 'User Profile')

@section('content')
<main>
    <div class="flex gap-5 max-[768px]:flex-col">
        <!-- Profile Picture Upload Section -->
        <div>
            <div class="flex flex-col items-center gap-5 mb-6 bg-white shadow-lg rounded-lg p-6 min-w-[300px] max-[768px]:min-w-[100%]">
                <form id="profile-picture-form" class="flex flex-col items-center relative">
                    @csrf
                    <div class="relative">
                        <img id="profile-picture-preview" 
                             src="{{ $user->profile_picture == 'default.png' ? asset('uploads/default.png') : Storage::url($user->profile_picture) }}" 
                             alt="Profile Picture" 
                             class="w-32 h-32 rounded-full border-4 border-gray-300 object-cover"
                             onerror="this.src='{{ asset('uploads/default.png') }}'">
                        
                        <button type="button" id="edit-profile-pic-btn" class="absolute bottom-1 right-1 bg-gray-800 text-white p-2 rounded-full cursor-pointer hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        </button>
            
                        <div id="profile-pic-options" class="absolute bottom-12 right-2 bg-white shadow-lg rounded-md p-2 hidden z-10">
                            <label for="profile_picture" class="block px-4 py-2 text-gray-700 cursor-pointer hover:bg-gray-100">Upload Picture</label>
                            <button type="button" id="delete-profile-pic" class="block px-4 py-2 text-red-600 w-full text-left hover:bg-gray-100">Remove Picture</button>
                        </div>
                    </div>
                    <input type="file" name="profile_picture" id="profile_picture" class="hidden">
            
                    <button type="submit" id="update-picture-btn" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 hidden">
                        Update Picture
                    </button>
                </form>
                <div class="flex flex-col justify-between gap-5 w-full">
                    <div class="flex flex-col gap-2">
                        <p class="text-[16px] text-primary text-center font-medium">@ {{ $user->user_name }}</p>
                        <h2 class="text-[28px] font-bold text-center">{{ $user->full_name ?? 'Full name' }}</h2>
                        <p class="text-[14px] text-zinc-600 text-center">{{ $user->user_dept }} - {{ $user->user_position }}</p>
                    </div>
                    <hr class="w-full">
                    <div class="flex flex-col gap-2 items-center">
                        <h2 class="text-[14px] text-zinc-500 font-semi">PIN</h2>
                        <h2 class="text-[22px] text-zinc-600 font-bold tracking-normal">{{ $user->user_pin }}</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Details Form -->
        <div class="w-full">
            <div class="bg-white shadow-lg rounded-lg w-full p-6">
                <form id="profile-update-form" class="space-y-6 w-full">
                    @csrf
                    <!-- Read-only Fields -->
                    <div class="grid md:grid-cols-2 gap-4">
                       <div>
                            <label class="block text-sm font-medium text-gray-700">Starting Date</label>
                            <input type="text" value="{{ $user->employment_date ? $user->employment_date->format('F j, Y') : 'N/A' }}" readonly class="mt-1 block w-full border border-gray-300 bg-gray-100 rounded-md shadow-sm p-2">
                        </div>
                    </div>
                    
                    <!-- Editable Fields -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $user->user_email }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                    
                        <div>
                            <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" name="contact" id="contact" value="{{ $user->phone_number }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" id="address" value="{{ $user->address ?? '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                        
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <select name="country" id="country" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="">Select Country</option>
                                <!-- Countries will be dynamically populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    
                    <!-- Password Update -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="old_password" class="block text-sm font-medium text-gray-700">Old Password</label>
                            <input type="password" name="old_password" id="old_password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="Only if changing password">
                        </div>
                    
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            <p id="password-strength" class="text-sm mt-1"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="confirm_password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" name="update" class="px-6 py-2 bg-[#1F5497] text-white rounded-md hover:bg-blue-700 transition-colors duration-300 ease-in-out shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- CSRF Token for Fetch ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const fetchHeaders = {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
    };
    // -----------------------------

    // Profile picture elements
    const fileInput = document.getElementById("profile_picture");
    const previewImage = document.getElementById("profile-picture-preview");
    const updateBtn = document.getElementById("update-picture-btn");
    const editButton = document.getElementById("edit-profile-pic-btn");
    const optionsDropdown = document.getElementById("profile-pic-options");
    const deleteButton = document.getElementById("delete-profile-pic");
    const pictureForm = document.getElementById("profile-picture-form");
    
    // Profile update elements
    const profileForm = document.getElementById("profile-update-form");
    const passwordInput = document.getElementById("password");
    const passwordStrengthText = document.getElementById("password-strength");

    /** Show SweetAlert toast notification */
    function showToast(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: type,
            title: message
        });
    }

    /** Toggle profile picture options dropdown */
    editButton.addEventListener("click", function () {
        optionsDropdown.classList.toggle("hidden");
    });

    /** Handle profile picture upload preview */
    if (fileInput) {
        fileInput.addEventListener("change", function (event) {
            if (event.target.files.length > 0) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
                updateBtn.classList.remove("hidden");
                optionsDropdown.classList.add("hidden");
            }
        });
    }

    /** Handle profile picture update (AJAX to avoid reload) */
    if (pictureForm) {
        pictureForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent page refresh
            let formData = new FormData(pictureForm);

            fetch("{{ route('profile.updatePicture') }}", {
                method: "POST",
                headers: { ...fetchHeaders }, // Add CSRF token
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    previewImage.src = `${data.image}?t=${new Date().getTime()}`; 
                    showToast("Profile picture updated successfully!");
                    updateBtn.classList.add("hidden");
                    // Update sidebars too
                    document.querySelectorAll('.sidebar-profile-pic').forEach(img => img.src = previewImage.src);
                } else {
                    showToast(data.message || "Failed to update picture.", "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showToast("An error occurred. Please try again.", "error");
            });
        });
    }

    /** Delete profile picture */
    if (deleteButton) {
        deleteButton.addEventListener("click", function () {
            optionsDropdown.classList.add("hidden");
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to remove your profile picture?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('profile.deletePicture') }}", { 
                        method: "POST",
                        headers: { ...fetchHeaders }, // Add CSRF token
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            previewImage.src = "{{ asset('uploads/default.png') }}";
                            showToast("Profile picture removed.");
                            // Update sidebars too
                            document.querySelectorAll('.sidebar-profile-pic').forEach(img => img.src = previewImage.src);
                        } else {
                            showToast("Failed to remove picture.", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        showToast("An error occurred. Please try again.", "error");
                    });
                }
            });
        });
    }

    /** Profile Update Form Submission */
    profileForm.addEventListener("submit", function (event) {
        event.preventDefault();
        let formData = new FormData(profileForm);

        fetch("{{ route('profile.updateDetails') }}", {
            method: "POST",
            headers: { ...fetchHeaders }, // Add CSRF token
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw data; });
            }
            return response.json();
        })
        .then(data => {
            if (data.status === "success") {
                showToast(data.message);
                // Clear password fields
                document.getElementById('old_password').value = '';
                document.getElementById('password').value = '';
                document.getElementById('confirm_password').value = '';
                document.getElementById('password-strength').textContent = '';
            }
        })
        .catch(data => {
            let errorMsg = "An error occurred. Please try again.";
            if (data && data.message) {
                errorMsg = data.message;
            }
            // Handle validation errors
            if (data && data.errors) {
                const firstError = Object.values(data.errors)[0][0];
                errorMsg = firstError;
            }
            showToast(errorMsg, "error");
        });
    });

    /** Password Strength Checker */
    if (passwordInput && passwordStrengthText) {
        passwordInput.addEventListener("input", function () {
            const password = passwordInput.value;
            if (!password) {
                passwordStrengthText.textContent = '';
                return;
            }
            let strength = checkPasswordStrength(password);
            passwordStrengthText.textContent = `Strength: ${strength}`;
            passwordStrengthText.style.color = 
                strength === "Weak" ? "red" : 
                strength === "Moderate" ? "orange" : "green";
        });
    }

    function checkPasswordStrength(password) {
        if (password.length < 8) return "Weak";
        let hasLower = /[a-z]/.test(password);
        let hasUpper = /[A-Z]/.test(password);
        let hasNumber = /\d/.test(password);
        let hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        return (hasLower + hasUpper + hasNumber + hasSpecial) >= 3 ? "Strong" : "Moderate";
    }
    
    // Make sure clicking outside the dropdown closes it
    document.addEventListener('click', function(event) {
        if (!editButton.contains(event.target) && !optionsDropdown.contains(event.target)) {
            optionsDropdown.classList.add('hidden');
        }
    });

    // --- Country Dropdown Population ---
    const countrySelect = document.getElementById("country");
    const currentCountry = "{{ $user->country ?? '' }}";

    fetch('https://restcountries.com/v3.1/all')
        .then(response => response.ok ? response.json() : Promise.reject('API failed'))
        .then(countries => populateCountries(countries))
        .catch(error => {
            console.warn('Using fallback country list:', error);
            useLocalCountriesList();
        });

    function populateCountries(countries) {
        countries.sort((a, b) => a.name.common.localeCompare(b.name.common));
        countries.forEach(country => {
            const option = document.createElement('option');
            option.value = country.name.common;
            option.textContent = country.name.common;
            if (country.name.common === currentCountry) {
                option.selected = true;
            }
            countrySelect.appendChild(option);
        });
    }

    function useLocalCountriesList() {
        const countryList = [
            "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", 
            "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", 
            "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", 
            "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", 
            "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", 
            "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", 
            "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", 
            "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", 
            "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", 
            "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kosovo", "Kuwait", "Kyrgyzstan", 
            "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", 
            "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", 
            "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", 
            "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", 
            "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", 
            "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", 
            "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", 
            "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", 
            "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", 
            "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", 
            "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", 
            "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", 
            "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
        ];
        
        countryList.sort();
        countryList.forEach(countryName => {
            const option = document.createElement('option');
            option.value = countryName;
            option.textContent = countryName;
            if (countryName === currentCountry) {
                option.selected = true;
            }
            countrySelect.appendChild(option);
        });
    }
});
</script>
@endsection