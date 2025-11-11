@extends('layouts.app')

@section('title', 'Benefits & Taxes')
@section('description', 'Manage employee benefits and tax-related information.')

@section('content')
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="px-0">
        <!-- Selected Toolkit Title with background -->
        <div class="flex items-center gap-2 mb-2">
            <span id="selectedToolkitTitle" class="selected-toolkit-title bg-title px-4 py-2" style="display:none;"></span>
        </div>
        <!-- Label below the title -->
        <div class="mb-5 mt-2">
            <span class="eform-label">Select E-Form</span>
        </div>

    <!-- Cards Row - Horizontal Layout -->
    <div class="mb-4">
        <div class="flex items-start gap-4 flex-wrap">
            <!-- Add Button Card -->
            <div class="toolkit-card-wrapper flex flex-col items-center">
                <button onclick="openAddToolkitModal()" class="toolkit-card square-card flex flex-col items-center justify-center">
                    <i class="fas fa-plus fa-2x text-gray-600"></i>
                </button>
            </div>

            @if($toolkits->isEmpty())
                <!-- No Toolkits Message -->
                <div class="no-toolkits w-full text-center py-8">
                    <i class="fas fa-folder-open text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-600">No toolkits available. Create your first toolkit!</p>
                </div>
            @else
                <!-- Existing Toolkit Cards -->
                @foreach($toolkits as $toolkit)
                    <div class="toolkit-card-wrapper flex flex-col items-center">
                        <div class="toolkit-card square-card bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100">
                            <div class="toolkit-actions">
                                @if($toolkit->user_id == auth()->id())
                                    <button 
                                        onclick="event.stopPropagation(); openAddToolkitModal({
                                            id: {{ $toolkit->sales_id }},
                                            title: '{{ addslashes($toolkit->sales_title) }}',
                                            formUrl: '{{ addslashes($toolkit->form_url) }}',
                                            responseUrl: '{{ addslashes($toolkit->response_url) }}',
                                            icon: '{{ addslashes($toolkit->icon) }}',
                                            type: '{{ addslashes($toolkit->type) }}'
                                        })"
                                        class="edit-button"
                                        title="Edit Toolkit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                            </div>
                            @if(!$toolkit->is_approved)
                                <span class="pending-badge">Pending</span>
                            @endif
                            <div class="toolkit-content" 
                                 onclick="loadToolkit('{{ $toolkit->response_url }}', '{{ $toolkit->form_url }}', {{ $toolkit->is_approved ? 'true' : 'false' }}, this.closest('.toolkit-card'), '{{ addslashes($toolkit->sales_title) }}', '{{ addslashes($toolkit->type) }}')">
                                @if($toolkit->icon && file_exists(public_path('HRIS/benefits-and-taxes/assets/crm_sales/' . basename($toolkit->icon))))
                                    <img src="{{ asset('HRIS/benefits-and-taxes/assets/crm_sales/' . basename($toolkit->icon)) }}" 
                                         alt="" 
                                         class="toolkit-icon bigger">
                                @else
                                    <i class="fas fa-file-alt text-2xl text-gray-400"></i>
                                @endif
                            </div>
                        </div>
                        <span class="toolkit-label">{{ $toolkit->sales_title }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Open Form Button (now below cards) -->
    <div class="mb-4 open-form-bar">
        <div class="open-form-bar-bg left-align-bar">
            <a id="openFormBtn" href="#" target="_blank" class="main-action-btn clean-btn compact-btn flex items-center gap-20" style="display:none;">
                <span>Open Form</span>
                <i class="fas fa-external-link-alt"></i>
            </a>
            <a id="openResponseBtn" href="#" target="_blank" class="main-action-btn clean-btn compact-btn flex items-center gap-20" style="display:none;">
                <span>Open Response</span>
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    </div>
        

    <!-- Toolkit Frame -->
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-100 relative">
        <div id="placeholderMessage" class="flex flex-col items-center justify-center h-[600px] text-gray-500">
            <i class="fas fa-file-alt text-4xl mb-4"></i>
            <span class="text-xl font-medium">Select a toolkit to view its content</span>
            <p class="text-sm text-gray-400 mt-2">Click on any toolkit card above to view its content here</p>
        </div>
        <iframe id="toolkitFrame" class="w-full h-[600px] border-0 hidden" src=""></iframe>
    </div>
</div>

    <!-- Add/Edit Toolkit Modal -->
    <div id="addToolkitModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative min-h-screen px-4 text-center">
            <div class="fixed inset-0" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-medium text-gray-800">Add New Toolkit</h3>
                    <button onclick="closeAddToolkitModal()" class="text-gray-500 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('benefits-and-taxes.store') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="toolkit_id" value="">
                    <input type="hidden" name="icon" id="selected_icon" value="communication.gif">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="sales_title" required 
                                   class="mt-1 block w-full p-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200"
                                   maxlength="50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            <div class="icon-grid">
                                @foreach($icons as $icon)
                                    @if($icon !== '.' && $icon !== '..')
                                        <div class="icon-option" 
                                             onclick="selectIcon(this)" 
                                             data-value="{{ $icon }}">
                                            <img src="{{ asset('HRIS/benefits-and-taxes/assets/crm_sales/' . $icon) }}" 
                                                 alt="{{ ucfirst(pathinfo($icon, PATHINFO_FILENAME)) }}" 
                                                 class="modal-icon">
                                            <p>{{ ucfirst(pathinfo($icon, PATHINFO_FILENAME)) }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" id="toolkit_type_select" class="mt-1 block w-full p-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200" required onchange="updateToolkitTypeFields()">
                                <option value="Form+Sheet">Form + Sheet</option>
                                <option value="Form">Form</option>
                                <option value="Sheet">Sheet</option>
                                <option value="Video">Video</option>
                                <option value="Slides">Slides</option>
                                <option value="Folder">Folder</option>
                            </select>
                        </div>
                        <div id="toolkit_type_fields">
                            <div id="form_url_group">
                                <label class="block text-sm font-medium text-gray-700 mb-1" id="form_url_label">Form URL</label>
                                <input type="url" name="form_url" id="form_url_input" required 
                                       class="mt-1 block w-full p-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                            </div>
                            <div id="response_url_group">
                                <label class="block text-sm font-medium text-gray-700 mb-1" id="response_url_label">Response URL</label>
                                <input type="url" name="response_url" id="response_url_input" required 
                                       class="mt-1 block w-full p-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                            </div>
                        </div>
                </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Save Toolkit</span>
                        </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3B82F6'
        });
    </script>
@endif

@if($errors->any())
    <script>
        Swal.fire({
            title: 'Error!',
            text: '{{ $errors->first() }}',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3B82F6'
        });
    </script>
@endif
<script>
let activeCard = null;
let selectedFormUrl = '';

// Helper to transform URLs for embedding
function getEmbeddableUrl(url, type) {
    if (!url) return url;
    // YouTube
    if (url.includes('youtube.com/watch')) {
        return url.replace('watch?v=', 'embed/');
    }
    if (url.includes('youtu.be/')) {
        // youtu.be/VIDEO_ID
        const id = url.split('youtu.be/')[1].split(/[?&]/)[0];
        return 'https://www.youtube.com/embed/' + id;
    }
    // Google Drive
    if (url.includes('drive.google.com')) {
        // Handle folder links
        if (url.includes('/folders/')) {
            // Extract folder ID
            const folderId = url.match(/\/folders\/([^/?]+)/)?.[1];
            if (folderId) {
                return `https://drive.google.com/embeddedfolderview?id=${folderId}#grid`;
            }
        }
        // Handle file links
        if (url.includes('/file/d/')) {
            // .../file/d/FILE_ID/view?usp=sharing => .../file/d/FILE_ID/preview
            return url.replace(/\/view.*$/, '/preview');
        }
    }
    // Google Sheets
    if (url.includes('docs.google.com/spreadsheets')) {
        // Recommend publish to web, but allow embed
        if (url.includes('/edit')) {
            return url.replace('/edit', '/preview');
        }
    }
    // Google Forms
    if (url.includes('docs.google.com/forms')) {
        // Use /viewform?embedded=true
        if (!url.includes('embedded=true')) {
            if (url.includes('?')) {
                return url + '&embedded=true';
            } else {
                return url + '?embedded=true';
            }
        }
    }
    // Vimeo
    if (url.includes('vimeo.com/')) {
        // vimeo.com/VIDEO_ID => player.vimeo.com/video/VIDEO_ID
        const match = url.match(/vimeo.com\/(\d+)/);
        if (match) {
            return 'https://player.vimeo.com/video/' + match[1];
        }
    }
    return url;
}

function loadToolkit(responseUrl, formUrl, isApproved, cardElement, toolkitTitle = '', type) {
    if (!isApproved) {
        Swal.fire({
            title: 'Pending Approval',
            text: 'This toolkit is currently pending administrator approval.',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    // Remove active class from previous card
    if (activeCard) {
        activeCard.classList.remove('active');
    }
    
    // Add active class to current card
    cardElement.classList.add('active');
    activeCard = cardElement;
    
    const iframe = document.getElementById('toolkitFrame');
    const placeholder = document.getElementById('placeholderMessage');
    
    // Function to check if URL is valid
    function isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    }

    // Show iframe and hide placeholder
    iframe.classList.remove('hidden');
    placeholder.classList.add('hidden');
    
    // Create a loading message
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'iframeLoading';
    loadingDiv.className = 'flex items-center justify-center absolute inset-0 bg-white bg-opacity-90 z-10';
    loadingDiv.innerHTML = `
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-2"></div>
            <div class="text-gray-600">Loading content...</div>
        </div>
    `;
    iframe.parentNode.appendChild(loadingDiv);

    // Set up iframe load event
    iframe.onload = function() {
        const loadingElement = document.getElementById('iframeLoading');
        if (loadingElement) {
            loadingElement.remove();
        }
    };

    // Set iframe source - use responseUrl as the primary content
    let embedUrl = '';
    if (responseUrl && responseUrl !== '#') {
        embedUrl = getEmbeddableUrl(responseUrl, type);
    } else if (formUrl && formUrl !== '#') {
        embedUrl = getEmbeddableUrl(formUrl, type);
    }
    if (embedUrl) {
        iframe.src = embedUrl;
    } else {
        // If no valid URL, show placeholder
        iframe.classList.add('hidden');
        placeholder.classList.remove('hidden');
        Swal.fire({
            title: 'No Content Available',
            text: 'This toolkit does not have any content to display.',
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3B82F6'
        });
    }

    // Update Open Form button
    const openFormBtn = document.getElementById('openFormBtn');
    if (isApproved && formUrl && formUrl !== '#') {
        openFormBtn.href = formUrl;
        openFormBtn.style.display = 'inline-flex';
    } else {
        openFormBtn.href = '#';
        openFormBtn.style.display = 'none';
    }
    const openResponseBtn = document.getElementById('openResponseBtn');
    if (isApproved && responseUrl && responseUrl !== '#') {
        openResponseBtn.href = responseUrl;
        openResponseBtn.style.display = 'inline-flex';
    } else {
        openResponseBtn.href = '#';
        openResponseBtn.style.display = 'none';
    }

    // Update selected toolkit title
    const selectedToolkitTitle = document.getElementById('selectedToolkitTitle');
    if (toolkitTitle) {
        selectedToolkitTitle.textContent = toolkitTitle;
        selectedToolkitTitle.style.display = 'inline';
    } else {
        selectedToolkitTitle.textContent = '';
        selectedToolkitTitle.style.display = 'none';
    }

    // Update type-specific button labels and visibility
    if (type === 'Video') {
        openFormBtn.textContent = 'Watch Video';
        openResponseBtn.textContent = 'Watch Video';
        openFormBtn.href = formUrl;
        openResponseBtn.href = responseUrl;
    } else if (type === 'Slides') {
        openFormBtn.textContent = 'View Slides';
        openResponseBtn.textContent = 'View Slides';
        openFormBtn.href = formUrl;
        openResponseBtn.href = responseUrl;
    } else if (type === 'Folder') {
        openFormBtn.style.display = 'none';
        openResponseBtn.style.display = 'none';
        // Show List/Grid toggle
        let folderToggle = document.getElementById('folderViewToggle');
        if (!folderToggle) {
            folderToggle = document.createElement('div');
            folderToggle.id = 'folderViewToggle';
            folderToggle.className = 'flex gap-2';
            folderToggle.innerHTML = `
                <button id="folderListBtn" class="main-action-btn clean-btn compact-btn flex items-center" type="button"><span>List</span></button>
                <button id="folderGridBtn" class="main-action-btn clean-btn compact-btn flex items-center" type="button"><span>Grid</span></button>
            `;
            openFormBtn.parentNode.appendChild(folderToggle);
        } else {
            folderToggle.style.display = 'flex';
        }
        // Add event listeners
        document.getElementById('folderListBtn').onclick = function() {
            const iframe = document.getElementById('toolkitFrame');
            if (iframe.src.includes('#grid')) {
                iframe.src = iframe.src.replace('#grid', '#list');
            } else if (!iframe.src.includes('#list')) {
                iframe.src += '#list';
            }
        };
        document.getElementById('folderGridBtn').onclick = function() {
            const iframe = document.getElementById('toolkitFrame');
            if (iframe.src.includes('#list')) {
                iframe.src = iframe.src.replace('#list', '#grid');
            } else if (!iframe.src.includes('#grid')) {
                iframe.src += '#grid';
            }
        };
    } else if (type === 'Sheet') {
        openFormBtn.textContent = 'Open Sheet';
        openResponseBtn.textContent = 'Open Sheet';
        openFormBtn.href = responseUrl;
        openResponseBtn.href = responseUrl;
    } else {
        openFormBtn.textContent = 'Open Form';
        openResponseBtn.textContent = 'Open Response';
        openFormBtn.href = formUrl;
        openResponseBtn.href = responseUrl;
    }

    // --- BUTTONS/TOGGLES LOGIC ---
    // Only show Open Form/Open Response for Form+Sheet
    if (type === 'Form+Sheet') {
        openFormBtn.style.display = 'inline-flex';
        openResponseBtn.style.display = 'inline-flex';
        openFormBtn.textContent = 'Open Form';
        openResponseBtn.textContent = 'Open Response';
        // Remove List/Grid toggle if present
        const folderToggle = document.getElementById('folderViewToggle');
        if (folderToggle) folderToggle.remove();
    } else if (type === 'Form') {
        openFormBtn.style.display = 'inline-flex';
        openResponseBtn.style.display = 'none';
        openFormBtn.textContent = 'Open Form';
        // Remove List/Grid toggle if present
        const folderToggle = document.getElementById('folderViewToggle');
        if (folderToggle) folderToggle.remove();
    } else if (type === 'Sheet') {
        openFormBtn.style.display = 'inline-flex';
        openResponseBtn.style.display = 'none';
        openFormBtn.textContent = 'Open Sheet';
        // Remove List/Grid toggle if present
        const folderToggle = document.getElementById('folderViewToggle');
        if (folderToggle) folderToggle.remove();
    } else if (type === 'Video') {
        openFormBtn.style.display = 'inline-flex';
        openResponseBtn.style.display = 'none';
        openFormBtn.textContent = 'Watch Video';
        // Remove List/Grid toggle if present
        const folderToggle = document.getElementById('folderViewToggle');
        if (folderToggle) folderToggle.remove();
    } else if (type === 'Slides') {
        openFormBtn.style.display = 'inline-flex';
        openResponseBtn.style.display = 'none';
        openFormBtn.textContent = 'Open Slides';
        // Remove List/Grid toggle if present
        const folderToggle = document.getElementById('folderViewToggle');
        if (folderToggle) folderToggle.remove();
    } else if (type === 'Folder') {
        openFormBtn.style.display = 'none';
        openResponseBtn.style.display = 'none';
        // Show List/Grid toggle
        let folderToggle = document.getElementById('folderViewToggle');
        if (!folderToggle) {
            folderToggle = document.createElement('div');
            folderToggle.id = 'folderViewToggle';
            folderToggle.className = 'flex gap-2';
            folderToggle.innerHTML = `
                <button id="folderListBtn" class="main-action-btn clean-btn compact-btn flex items-center" type="button"><span>List</span></button>
                <button id="folderGridBtn" class="main-action-btn clean-btn compact-btn flex items-center" type="button"><span>Grid</span></button>
            `;
            openFormBtn.parentNode.appendChild(folderToggle);
        } else {
            folderToggle.style.display = 'flex';
        }
        // Add event listeners
        document.getElementById('folderListBtn').onclick = function() {
            const iframe = document.getElementById('toolkitFrame');
            if (iframe.src.includes('#grid')) {
                iframe.src = iframe.src.replace('#grid', '#list');
            } else if (!iframe.src.includes('#list')) {
                iframe.src += '#list';
            }
        };
        document.getElementById('folderGridBtn').onclick = function() {
            const iframe = document.getElementById('toolkitFrame');
            if (iframe.src.includes('#list')) {
                iframe.src = iframe.src.replace('#list', '#grid');
            } else if (!iframe.src.includes('#grid')) {
                iframe.src += '#grid';
            }
        };
    } else {
        openFormBtn.style.display = 'none';
        openResponseBtn.style.display = 'none';
        // Remove List/Grid toggle if present
        const folderToggle = document.getElementById('folderViewToggle');
        if (folderToggle) folderToggle.remove();
    }
}

function openAddToolkitModal(toolkit = null) {
    console.log('Opening modal...');
    const modal = document.getElementById('addToolkitModal');
    
    if (!modal) {
        console.error('Modal element not found!');
        return;
    }
    
    const form = modal.querySelector('form');
    const title = modal.querySelector('h3');
    
    // Add form submission handler
    form.onsubmit = function(e) {
        e.preventDefault(); // Prevent default form submission
        const formData = new FormData(form);
        const type = form.type.value;
        let valid = true;
        if (!formData.get('sales_title')) valid = false;
        if (type === 'Video' || type === 'Slides' || type === 'Folder') {
            if (!formData.get('form_url')) valid = false;
        } else if (type === 'Sheet') {
            if (!formData.get('response_url')) valid = false;
        } else if (type === 'Form') {
            if (!formData.get('form_url')) valid = false;
        } else {
            // Form + Sheet
            if (!formData.get('form_url') || !formData.get('response_url')) valid = false;
        }
        if (!valid) {
            Swal.fire({
                title: 'Error',
                text: 'Please fill in all required fields',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3B82F6'
            });
            return false;
        }
        // Only show additional charges warning for new toolkits
        if (!formData.get('toolkit_id')) {
            // Show confirmation dialog for new toolkits
            Swal.fire({
                title: 'Additional Charges',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Adding a new toolkit will incur additional charges:</p>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Storage fees may apply</li>
                            <li>Processing fees for content</li>
                            <li>Additional user access fees</li>
                        </ul>
                        <p>Do you want to proceed?</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#EF4444',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
        } else {
            // Directly submit for edits
            submitForm();
        }
        return false;
    };

    function submitForm() {
        // Show processing message
        Swal.fire({
            title: 'Saving Toolkit',
            text: 'Please wait while we process your request...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit the form
        form.submit();
    }
    
    if (toolkit) {
        title.textContent = 'Edit Toolkit';
        form.sales_title.value = toolkit.title;
        form.form_url.value = toolkit.formUrl;
        form.response_url.value = toolkit.responseUrl;
        form.toolkit_id.value = toolkit.id;
        form.type.value = toolkit.type;
        
        // Select the correct icon
        const iconOption = modal.querySelector(`[data-value="${toolkit.icon}"]`);
        if (iconOption) {
            selectIcon(iconOption);
        }
        // Ensure correct fields are shown for the type
        updateToolkitTypeFields();
    } else {
        title.textContent = 'Add New Toolkit';
        form.reset();
        form.toolkit_id.value = '';
        form.type.value = 'Form+Sheet';
        
        // Select the default icon
        const defaultIcon = modal.querySelector('.icon-option');
        if (defaultIcon) {
            selectIcon(defaultIcon);
        }
        updateToolkitTypeFields();
    }
    
    modal.classList.remove('hidden');
}

function closeAddToolkitModal() {
    console.log('Closing modal...');
    const modal = document.getElementById('addToolkitModal');
    if (modal) {
        modal.classList.add('hidden');
        console.log('Modal hidden');
    } else {
        console.error('Modal element not found when trying to close!');
    }
}

function selectIcon(element) {
    const icons = document.querySelectorAll('.icon-option');
    icons.forEach(icon => icon.classList.remove('selected'));
    element.classList.add('selected');
    document.getElementById('selected_icon').value = element.dataset.value;
}

// --- Modal Dynamic Fields ---
function updateToolkitTypeFields() {
    const type = document.getElementById('toolkit_type_select').value;
    const formGroup = document.getElementById('form_url_group');
    const responseGroup = document.getElementById('response_url_group');
    const formLabel = document.getElementById('form_url_label');
    const responseLabel = document.getElementById('response_url_label');
    const formInput = document.getElementById('form_url_input');
    const responseInput = document.getElementById('response_url_input');
    if (type === 'Video') {
        formGroup.style.display = '';
        responseGroup.style.display = 'none';
        formLabel.textContent = 'Video URL';
        formInput.required = true;
        responseInput.required = false;
        responseInput.removeAttribute('required');
        formInput.name = 'form_url';
        responseInput.name = 'response_url';
        responseInput.value = '';
    } else if (type === 'Slides') {
        formGroup.style.display = '';
        responseGroup.style.display = 'none';
        formLabel.textContent = 'Slides URL';
        formInput.required = true;
        responseInput.required = false;
        responseInput.removeAttribute('required');
        formInput.name = 'form_url';
        responseInput.name = 'response_url';
        responseInput.value = '';
    } else if (type === 'Folder') {
        formGroup.style.display = '';
        responseGroup.style.display = 'none';
        formLabel.textContent = 'Folder URL';
        formInput.required = true;
        responseInput.required = false;
        responseInput.removeAttribute('required');
        formInput.name = 'form_url';
        responseInput.name = 'response_url';
        responseInput.value = '';
    } else if (type === 'Sheet') {
        formGroup.style.display = 'none';
        responseGroup.style.display = '';
        responseLabel.textContent = 'Sheet URL';
        responseInput.required = true;
        formInput.required = false;
        formInput.value = '';
        formInput.name = 'form_url';
        responseInput.name = 'response_url';
    } else if (type === 'Form') {
        formGroup.style.display = '';
        responseGroup.style.display = 'none';
        formLabel.textContent = 'Form URL';
        formInput.required = true;
        responseInput.required = false;
        responseInput.removeAttribute('required');
        responseInput.value = '';
        formInput.name = 'form_url';
        responseInput.name = 'response_url';
    } else {
        // Form + Sheet
        formGroup.style.display = '';
        responseGroup.style.display = '';
        formLabel.textContent = 'Form URL';
        responseLabel.textContent = 'Sheet URL';
        formInput.required = true;
        responseInput.required = true;
        formInput.name = 'form_url';
        responseInput.name = 'response_url';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    updateToolkitTypeFields();
    const typeSelect = document.getElementById('toolkit_type_select');
    if (typeSelect) typeSelect.addEventListener('change', updateToolkitTypeFields);
});
</script>

<style>
/* Custom scrollbar styling */
.custom-scrollbar {
    scrollbar-width: thin;  /* Firefox */
    scrollbar-color: #CBD5E1 transparent;  /* Firefox */
}

.custom-scrollbar::-webkit-scrollbar {
    height: 6px;  /* for horizontal scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #CBD5E1;
    border-radius: 20px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #94A3B8;
}

/* Status badge styles */
.status-badge {
    padding: 2px 8px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-pending {
    background-color: #FEF3C7;
    color: #92400E;
}

.status-approved {
    background-color: #D1FAE5;
    color: #065F46;
}

.icon-option {
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s;
}

.icon-option:hover {
    background-color: #F3F4F6;
}

.icon-option.selected {
    ring-width: 2px;
    ring-color: #3B82F6;
}

.edit-button {
    opacity: 0;
    transition: opacity 0.2s;
    padding: 0.25rem 0.35rem;
    border-radius: 0.375rem;
    background-color: #F3F4F6;
    color: #6B7280;
    border: 1px solid #E5E7EB;
    font-size: 1rem;
    box-shadow: none;
    min-width: unset;
    min-height: unset;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.edit-button:hover {
    background-color: #E5E7EB;
    color: #374151;
}

.toolkit-card:hover .edit-button {
    opacity: 1;
}

.toolkit-card {
    position: relative;
    width: 80px !important;
    height: 80px !important;
    padding: 0 !important;
    margin: 0 auto !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: white;
    border-radius: 0.5rem;
    border: 2px solid transparent;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.toolkit-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.toolkit-card.active {
    border-color: #3B82F6;
    background-color: #F8FAFC;
}

.toolkit-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.5rem;
    z-index: 10;
}

.toolkit-content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.toolkit-footer {
    width: 100%;
    position: absolute;
    bottom: 1rem;
    left: 0;
    padding: 0 1rem;
}

.open-form-button {
    width: 100%;
    padding: 0.5rem;
    background-color: #10B981;
    color: white;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.open-form-button:hover {
    background-color: #059669;
}

.open-form-button i {
    transition: transform 0.2s;
}

.toolkit-card .title {
    font-size: 1rem;
    font-weight: 500;
    color: #1F2937;
    max-width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.toolkit-icon {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.pending-badge {
    position: absolute;
    top: 0.25rem;
    left: 50%;
    transform: translateX(-50%);
    background-color: #FEF3C7;
    color: #92400E;
    padding: 0.15rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 500;
    white-space: nowrap;
    z-index: 2;
    max-width: 90%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
}

.icon-option:hover {
    background-color: #F3F4F6;
}

.icon-option.selected {
    border: 2px solid #3B82F6;
}

.icon-option img {
    width: 32px;
    height: 32px;
    margin-bottom: 0.5rem;
}

.icon-option p {
    font-size: 0.75rem;
    color: #4B5563;
    text-align: center;
}

.no-toolkits {
    text-align: center;
    padding: 2rem;
    background-color: #F9FAFB;
    border-radius: 0.5rem;
    margin: 1rem 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.icon-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    max-height: 200px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.icon-grid::-webkit-scrollbar {
    width: 4px;
}

.icon-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.icon-grid::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.icon-grid::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Add these styles for the loading indicator */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.animate-spin {
    animation: spin 1s linear infinite;
}

.toolkit-icon.large {
    width: 56px;
    height: 56px;
}
.title.large-title {
    font-size: 1.5rem;
    font-weight: 600;
}
.main-action-btn {
    padding: 0.75rem 1.5rem;
    background-color: #10B981;
    color: white;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.15);
}
.main-action-btn:hover {
    background-color: #059669;
    color: #fff;
}

.toolkit-card.small-card {
    width: 120px;
    height: 100px;
    padding: 0.75rem 0.5rem;
    min-width: 120px;
    min-height: 100px;
}
.toolkit-icon.small {
    width: 32px;
    height: 32px;
}
.title.small-title {
    font-size: 1rem;
    font-weight: 500;
    max-width: 110px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.selected-toolkit-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
}
.main-action-btn.small {
    padding: 0.4rem 1rem;
    font-size: 0.95rem;
    border-radius: 0.375rem;
    min-width: unset;
    min-height: unset;
    height: auto;
}

.bg-title {
    background: #f5f5f5;
    border-radius: 0.75rem;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
    box-shadow: 0 1px 3px rgba(0,0,0,0.09);
    display: inline-block;
}

.eform-label {
    display: inline-block;
    font-size: 1rem;
    color: #374151;
    font-weight: 500;
    margin-left: 2px;
    margin-bottom: 2px;
}

.square-card {
    width: 80px !important;
    height: 80px !important;
    min-width: 80px !important;
    min-height: 80px !important;
    max-width: 80px !important;
    max-height: 80px !important;
    padding: 0 !important;
    margin: 0 auto !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.toolkit-card-wrapper {
    width: 80px !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    text-align: center;
}
.toolkit-icon.bigger {
    width: 40px !important;
    height: 40px !important;
    margin: 0 auto;
    display: block;
}
.toolkit-label {
    margin-top: 0.5rem;
    font-size: 0.95rem;
    color: #1F2937;
    font-weight: 500;
    width: 80px;
    max-width: 80px;
    word-break: break-word;
    white-space: normal;
    overflow-wrap: break-word;
    text-align: center;
    display: block;
}
.open-form-bar {
    width: 100%;
    margin-bottom: 1rem;
}
.open-form-bar-bg {
    background: #f3f4f6;
    border-radius: 0.5rem;
    padding: 0.5rem 0.5rem;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    width: 100%;
    gap: 1rem;
}
.left-align-bar {
    justify-content: flex-start !important;
}
.clean-btn {
    background: #fff !important;
    color: #2563eb !important;
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem !important;
    box-shadow: none !important;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.35rem 1rem;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.compact-btn {
    font-size: 0.95rem !important;
    padding: 0.35rem 1rem !important;
}
.clean-btn:hover {
    background: #2563eb !important;
    color: #fff !important;
    border: 1px solid #2563eb !important;
}
/* Add margin below the type dropdown for spacing */
#toolkit_type_select, #toolkit_type_fields input{
    padding:5px;
    margin-bottom: 1rem;
}
</style>
@endsection
