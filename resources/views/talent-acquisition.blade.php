@extends('layouts.app')

@section('title', 'Talent Acquisition')
@section('description', 'Manage talent acquisition pipelines.')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.lordicon.com/lordicon.js"></script>

<div class="px-0">
    <div class="mb-6">
        <div class="icon-category-selector">

             <div class="category-icon" onclick="openToolkitModal()" style="border: 2px dashed #d1d5db; background: transparent;">
                <i class="fas fa-plus fa-2x text-gray-400" style="margin-bottom: 5px;"></i>
                <span style="color: #9ca3af;">Add New</span>
            </div>
            
            @foreach($toolkits as $toolkit)
                <div class="category-icon relative group"
                     onclick="selectToolkit(this, {{ $toolkit->id }}, '{{ addslashes($toolkit->type) }}', '{{ addslashes($toolkit->sales_title) }}', '{{ addslashes($toolkit->form_url) }}', '{{ addslashes($toolkit->response_url) }}', {{ $toolkit->is_approved ? 'true' : 'false' }})">
                    
                    @if($toolkit->user_id == auth()->user()->user_id)
                        <button class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity p-1 bg-gray-100 rounded text-gray-500 hover:text-blue-600 z-10"
                                onclick="event.stopPropagation(); openToolkitModal({{ json_encode($toolkit) }})">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                    @endif

                    @if(!$toolkit->is_approved)
                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-yellow-100 text-yellow-800 text-[10px] px-2 py-0.5 rounded-full border border-yellow-300">Pending</span>
                    @endif

                    @if($toolkit->icon && file_exists(public_path('HRIS/hr-toolkit/assets/crm_hr/' . basename($toolkit->icon))))
                        <img src="{{ asset('HRIS/hr-toolkit/assets/crm_hr/' . basename($toolkit->icon)) }}" alt="" style="width: 40px; height: 40px; margin-bottom: 5px;">
                    @else
                        <i class="fas fa-folder text-3xl text-blue-300" style="margin-bottom: 5px;"></i>
                    @endif
                    <span title="{{ $toolkit->sales_title }}">
                        {{ Str::limit($toolkit->sales_title) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <hr class="my-6 border-gray-200">

    <div id="quick-links-container" class="mb-6 flex flex-wrap items-center gap-3 w-full" style="display: none;"></div>

    <div id="data-display-container" class="bg-white rounded-lg shadow-md p-4 border border-gray-100 relative" style="display: none;">
        <div class="ta-filters-container" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center; background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div class="dropdowns-wrapper" style="display: flex; flex-wrap: wrap; gap: 10px; flex: 1;">
                <select id="sheetDropdown" class="filter-select" style="display:none; font-weight:600; color:#1F5497;" onchange="handleFilterChange()"></select>
                <select id="roleFilterDropdown" class="filter-select" onchange="handleFilterChange()"><option value="">All Roles</option></select>
                <select id="statusFilterDropdown" class="filter-select" onchange="handleFilterChange()"><option value="">All Statuses</option></select>
            </div>
            <div class="ta-search-bar-wrapper" style="display: flex; max-width: 400px; width: 100%;">
                <input type="text" id="ta-searchBar" placeholder="Search name..." style="flex: 1; padding: 8px 12px; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px;" onkeyup="if(event.key==='Enter') handleFilterChange()">
                <button type="button" onclick="handleFilterChange()" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 0 6px 6px 0;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="card-container" id="staffCardContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;"></div>

        <div id="noDataMessage" class="hidden text-center py-12 text-gray-400">
            <i class="fas fa-inbox text-5xl mb-4"></i>
            <p>No data available.</p>
        </div>
        
        <div id="paginationControls" class="justify-end items-center gap-3 mt-6 pt-4 border-t border-gray-100 font-medium text-gray-600">
            <div class="flex items-center gap-2 mr-4">
                 <label for="itemsPerPage" class="text-sm">Show:</label>
                 <select id="itemsPerPage" onchange="handleFilterChange()" class="border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 p-1.5">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <button id="prevPageBtn" onclick="changePage(-1)" class="pagination-btn" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
             <span id="pageInfo" class="min-w-[100px] text-center text-sm">Page 1 of 1</span>
            <button id="nextPageBtn" onclick="changePage(1)" class="pagination-btn" disabled>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <div id="placeholderMessage" class="flex flex-col items-center justify-center h-96 bg-white rounded-lg border-2 border-dashed border-gray-300 text-gray-400">
        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" delay="5000" colors="primary:#e5e7eb,secondary:#9ca3af" style="width:80px;height:80px"></lord-icon>
        <p class="mt-4 text-lg font-medium">Select a category above to view talent</p>
    </div>
</div>

<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden flex-col items-center justify-center text-white">
    <div class="w-12 h-12 border-4 border-blue-400 border-t-transparent rounded-full animate-spin mb-4"></div><p>Loading data...</p>
</div>
<style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>

<div id="addToolkitModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen px-4 text-center flex items-center justify-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeToolkitModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-medium text-gray-800">Manage Category</h3>
                <button onclick="closeToolkitModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="toolkitForm" method="POST" action="{{ route('talent-acquisition-toolkit.store') }}" class="mt-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="icon" id="selected_icon" value="communication.gif">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="sales_title" required class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" maxlength="50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="toolkit_type_select" class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required onchange="updateToolkitTypeFields()">
                            <option value="Talent Pipeline (Sheet)" class="font-bold bg-blue-50">Talent Pipeline (Sheet)</option>
                            <option disabled>──────────</option>
                            <option value="Form">Google Form Link</option>
                            <option value="Sheet">Google Sheet Link</option>
                            <option value="Form+Sheet">Form + Sheet Links</option>
                            <option value="Folder">Google Drive Folder</option>
                            <option value="Video">Video URL</option>
                            <option value="Slides">Slides URL</option>
                        </select>
                    </div>
                    <div id="toolkit_type_fields" class="bg-gray-50 p-3 rounded-md border border-gray-200">
                        <div id="generic_url_group">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="generic_url_label">URL</label>
                            <input type="url" name="form_url" id="generic_url_input" class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div id="sheet_url_group" class="mt-3" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="sheet_url_label">Response Sheet URL</label>
                            <input type="url" name="response_url" id="sheet_url_input" class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1" id="sheet_hint"></p>
                        </div>

                        <div id="pipeline_quick_links" class="mt-4 pt-4 border-t border-gray-200" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Links (Forms, Sheets, etc.)</label>
                            <div id="quick_links_container" class="space-y-2 mb-3"></div>
                            <button type="button" onclick="addQuickLinkInput()" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                <i class="fas fa-plus-circle mr-1"></i> Add Link
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Icon</label>
                        <div class="icon-grid border rounded-md p-2 max-h-40 overflow-y-auto grid grid-cols-4 gap-2">
                            @if(isset($icons))
                                @foreach($icons as $icon)
                                    <div class="icon-option flex flex-col items-center p-2 rounded cursor-pointer hover:bg-blue-50" 
                                         onclick="selectIcon(this)" data-value="{{ $icon }}">
                                        <img src="{{ asset('HRIS/hr-toolkit/assets/crm_hr/' . $icon) }}" class="w-8 h-8 mb-1">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-200">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="docViewerModal" style="display: none; position: fixed; z-index: 1001; inset: 0; background: rgba(0,0,0,0.8);">
    <div style="position: absolute; inset: 40px; background: white; border-radius: 8px; display: flex; flex-direction: column; overflow: hidden;">
        <div style="padding: 15px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h2 id="docViewerTitle" style="font-size: 1.1rem; font-weight: 600;">Document Preview</h2>
            <div style="display: flex; gap: 15px; align-items: center;">
                <a id="openExternalLink" href="#" target="_blank" style="color: #2563eb; text-decoration: none; font-size: 0.9rem;"><i class="fas fa-external-link-alt mr-1"></i> Open in New Tab</a>
                <span onclick="closeDocViewer()" style="font-size: 24px; cursor: pointer; color: #6b7280;">&times;</span>
            </div>
        </div>
        <div style="flex: 1; background: #e5e5e5;">
            <iframe id="docViewerFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    </div>
</div>

@if(session('success')) <script>Swal.fire({ title: 'Success', text: '{{ session('success') }}', icon: 'success', confirmButtonColor: '#2563eb' });</script> @endif
@if($errors->any()) <script>Swal.fire({ title: 'Error!', html: '<ul>'+@json($errors->all()).map(e=>`<li>${e}</li>`).join('')+'</ul>', icon: 'error', confirmButtonColor: '#2563eb' });</script> @endif

<script>
let state = { toolkitId: null, sheet: '', page: 1, totalPages: 1 };

function selectToolkit(el, id, type, title, fUrl, rUrl, isApproved) {
    if (!isApproved) { Swal.fire('Pending', 'Waiting approval.', 'info'); return; }
    activate(el, title);
    state = { toolkitId: id, page: 1, sheet: '' };
    const ql = document.getElementById('quick-links-container');
    ql.innerHTML = ''; 
    document.getElementById('sheetDropdown').style.display = 'none';

    if (type === 'Talent Pipeline (Sheet)') {
        // Render quick links from JSON
        try {
            if (fUrl && fUrl !== 'null') {
                const links = JSON.parse(fUrl);
                if (Array.isArray(links)) {
                    links.forEach(link => {
                        let icon = 'fas fa-link';
                        if (link.type === 'form') icon = 'fas fa-file-alt';
                        else if (link.type === 'sheet') icon = 'fas fa-table';
                        addLink(link.title, link.url, icon);
                    });
                }
            }
        } catch (e) {
             // Fallback for legacy data
             if (fUrl && fUrl !== 'null' && !fUrl.startsWith('[')) addLink('Form Link', fUrl, 'fas fa-file-alt');
        }
        ql.style.display = ql.children.length > 0 ? 'flex' : 'none';
        fetchData();
    } else if (type === 'Sheet' && rUrl.includes('spreadsheets')) {
        ql.style.display = (fUrl && fUrl !== 'null') ? 'flex' : 'none';
        if (fUrl && fUrl !== 'null') addLink('Form Link', fUrl, 'fas fa-file-alt');
        fetchData();
    } else {
        document.getElementById('data-display-container').style.display = 'none';
        ql.style.display = 'flex';
        // Handle other types that just show links
        if (fUrl && fUrl!=='null') addLink(type.includes('Video')?'Watch Video':(type.includes('Folder')?'Open Folder':'Open Link'), fUrl, type.includes('Video')?'fas fa-play':'fas fa-link');
        if (rUrl && rUrl!=='null') addLink('Open Sheet', rUrl, 'fas fa-table');
    }
}

function activate(el, title) {
    document.querySelectorAll('.category-icon').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('placeholderMessage').style.display = 'none';
    document.getElementById('data-display-container').style.display = 'block';
}

function addLink(text, url, icon) {
    const a = document.createElement('a');
    a.href = url; a.target = '_blank'; a.className = 'quick-link-btn';
    a.innerHTML = `<i class="${icon} mr-2"></i>${text}`;
    document.getElementById('quick-links-container').appendChild(a);
}

async function fetchData() {
    if (!state.toolkitId) return; // Don't fetch if no toolkit selected
    document.getElementById('loading-overlay').style.display = 'flex';
    const p = new URLSearchParams({
        toolkit_id: state.toolkitId,
        page: state.page, 
        items: document.getElementById('itemsPerPage').value,
        role: document.getElementById('roleFilterDropdown').value, 
        status: document.getElementById('statusFilterDropdown').value,
        search: document.getElementById('ta-searchBar').value
    });
    if (state.sheet) p.append('sheet', state.sheet);

    try {
        const res = await fetch(`{{ route('api.talent-acquisition.data') }}?${p}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        // Update Sheet Dropdown
        const dd = document.getElementById('sheetDropdown');
        if (data.sheets && data.sheets.length > 0) {
             // Only repopulate if it's a new toolkit load or different sheets
             if (state.page === 1 || dd.options.length === 0 || dd.options[0].value !== data.sheets[0]) {
                 dd.innerHTML = data.sheets.map(s => `<option value="${s}" ${s === (data.currentSheet || state.sheet) ? 'selected' : ''}>${s}</option>`).join('');
                 state.sheet = data.currentSheet || state.sheet || data.sheets[0];
             }
             dd.style.display = 'inline-block';
        } else {
            dd.style.display = 'none';
        }

        renderCards(data.data);
        updatePagination(data.pagination);
        if (state.page === 1) updateFilters(data.filters);
    } catch (err) {
        console.error(err); document.getElementById('staffCardContainer').innerHTML = ''; document.getElementById('noDataMessage').style.display = 'block';
    } finally {
        document.getElementById('loading-overlay').style.display = 'none';
    }
}

function renderCards(staff) {
    const container = document.getElementById('staffCardContainer');
    container.innerHTML = '';
    if (staff.length === 0) {
        document.getElementById('noDataMessage').style.display = 'block';
        document.getElementById('paginationControls').style.display = 'none'; // Hide pagination if no data
        return;
    } else {
        document.getElementById('noDataMessage').style.display = 'none';
        document.getElementById('paginationControls').style.display = 'flex'; // Show pagination if data exists
        staff.forEach(p => {
            container.innerHTML += `
                <div class="staff-card">
                    <div class="card-header">
                        <div class="status-badge status-dropdown-container" style="background-color: ${getStatusColor(p.status)};">
                            <span class="current-status">${p.status||'Pending'}</span>
                            <i class="fas fa-chevron-down status-caret"></i>
                            <div class="status-dropdown" data-email="${p.email}">
                                ${['Initial Interview','Client Interview','Hiring Manager Interview','Re-apply','Scheduled','Pending','Ringing','Cannot Be Reached','Employed Already','Not Interested','Withdrawn','Shortlisted','Done Interview','Hired','Engage','Rejected','Re-schedule']
                                    .map(s => `<div class="status-option" data-val="${s}">${s}</div>`).join('')}
                            </div>
                        </div>
                        <div class="profile-image-container" onclick="showImage('${p.photoUrl}')">
                            <img src="${p.photoUrl}" onerror="this.src='https://cdn.vectorstock.com/i/500p/08/19/gray-photo-placeholder-icon-design-ui-vector-35850819.jpg'">
                        </div>
                    </div>
                    <div class="card-body">
                        <h2 class="staff-name" title="${p.firstName} ${p.lastName}">${p.firstName} ${p.lastName}</h2>
                        <div class="info-container">
                            <div class="info-left">
                                <p style="color: #3b82f6; font-weight: 600; margin-bottom: 10px;">${p.applyingAs||'N/A'}</p>
                                <p><strong>Age:</strong> ${p.age||'N/A'}</p>
                                <p><strong>Gender:</strong> ${p.gender||'N/A'}</p>
                                <p><strong>Phone:</strong> ${p.contact||'N/A'}</p>
                                <p><strong>Email:</strong> ${p.email||'N/A'}</p>
                                <p><strong>Address:</strong> ${p.address||'N/A'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <table><tr>
                            <td class="btn-border"><a class="btn-border" onclick="openDoc('${p.pdfUrl}','PDF')" ${!p.pdfUrl?'disabled style="opacity:0.5; pointer-events:none"':''}>PDF</a></td>
                            <td class="btn-border"><a class="btn-border" onclick="openDoc('${p.docUrl}','DOCS')" ${!p.docUrl?'disabled style="opacity:0.5; pointer-events:none"':''}>DOCS</a></td>
                            <td class="btn-border"><a class="btn-border" onclick="openDoc('${p.editUrl}','EDIT')" ${!p.editUrl?'disabled style="opacity:0.5; pointer-events:none"':''}>EDIT</a></td>
                        </tr></table>
                    </div>
                </div>`;
        });
    }
    attachDropdownListeners();
}

function attachDropdownListeners() {
    document.querySelectorAll('.status-dropdown-container').forEach(c => {
        c.onclick = (e) => { e.stopPropagation(); document.querySelectorAll('.status-dropdown-container.active').forEach(o => o!==c && o.classList.remove('active')); c.classList.toggle('active'); };
    });
    document.querySelectorAll('.status-option').forEach(o => {
        o.onclick = (e) => {
            e.stopPropagation();
            const c = o.closest('.status-dropdown-container'), email = o.parentElement.dataset.email, val = o.dataset.val;
            c.querySelector('.current-status').textContent = val; c.style.backgroundColor = getStatusColor(val); c.classList.remove('active');
            updateStatus(email, val);
        };
    });
    document.onclick = () => document.querySelectorAll('.status-dropdown-container.active').forEach(c => c.classList.remove('active'));
}

async function updateStatus(email, status) {
    try {
        const res = await fetch("{{ route('api.talent-acquisition.update-status') }}", {
            method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ dataType: 'dynamic', sheetName: state.sheet, candidateEmail: email, newStatus: status })
        });
        if(!(await res.json()).success) throw new Error();
        Swal.fire({icon: 'success', title: 'Updated', toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000});
    } catch(e) { Swal.fire('Error', 'Failed to update status.', 'error'); }
}

function handleFilterChange() { state.page = 1; state.sheet = document.getElementById('sheetDropdown').value; fetchData(); }
function changePage(d) { state.page += d; fetchData(); }
function updatePagination(p) {
    state.totalPages = p.totalPages;
    document.getElementById('pageInfo').innerText = `Page ${p.currentPage} of ${p.totalPages}`;
    document.getElementById('prevPageBtn').disabled = p.currentPage <= 1;
    document.getElementById('nextPageBtn').disabled = p.currentPage >= p.totalPages;
}
function updateFilters(f) {
    const fill = (id, o) => document.getElementById(id).innerHTML = '<option value="">All</option>' + o.map(v => `<option value="${v}">${v}</option>`).join('');
    fill('roleFilterDropdown', f.applyingAsOptions); fill('statusFilterDropdown', f.statusOptions);
}
function getStatusColor(s) {
    if(!s) return '#9E9E9E'; s=s.toLowerCase();
    if(s.includes('hired')||s.includes('active')||s==='applied') return '#4CAF50';
    if(s.includes('interview')) return '#FF9800';
    if(s.includes('scheduled')) return '#2196F3';
    if(s.includes('reject')||s.includes('withdrawn')) return '#F44336';
    if(s.includes('shortlisted')||s.includes('ringing')||s.includes('re-apply')) return '#03A9F4';
    if(s.includes('not yet')||s.includes('initial')||s.includes('pending')) return '#FFC107';
    return '#9E9E9E';
}

function openToolkitModal(data = null) {
    const f = document.getElementById('toolkitForm'); f.reset();
    document.getElementById('quick_links_container').innerHTML = ''; 
    if (data) {
        f.action = "{{ route('talent-acquisition-toolkit.update', ['id' => 'REPLACE_ID']) }}".replace('REPLACE_ID', data.id);
        f.querySelector('input[name="_method"]').value = 'PUT';
        f.sales_title.value = data.sales_title;
        f.type.value = data.type;
        
        if (data.type === 'Talent Pipeline (Sheet)') {
            f.response_url.value = data.response_url;
            try {
                const links = JSON.parse(data.form_url);
                if (Array.isArray(links)) links.forEach(link => addQuickLinkInput(link));
            } catch (e) { /* ignore legacy data error */ }
        } else {
             if(data.form_url && !data.form_url.startsWith('[')) f.form_url.value = data.form_url;
             if(data.response_url) f.response_url.value = data.response_url;
        }
    } else {
        f.action = "{{ route('talent-acquisition-toolkit.store') }}";
        f.querySelector('input[name="_method"]').value = 'POST';
    }
    updateToolkitTypeFields();
    document.getElementById('addToolkitModal').classList.remove('hidden');
}

function closeToolkitModal() { document.getElementById('addToolkitModal').classList.add('hidden'); }

function updateToolkitTypeFields() {
    const t = document.getElementById('toolkit_type_select').value;
    const genGroup = document.getElementById('generic_url_group');
    const sheetGroup = document.getElementById('sheet_url_group');
    const pipeLinks = document.getElementById('pipeline_quick_links');
    const genLabel = document.getElementById('generic_url_label');
    const sheetLabel = document.getElementById('sheet_url_label');
    const sheetHint = document.getElementById('sheet_hint');

    genGroup.style.display = 'block'; sheetGroup.style.display = 'none'; pipeLinks.style.display = 'none'; sheetHint.textContent = '';

    if (t === 'Talent Pipeline (Sheet)') {
        genGroup.style.display = 'none';
        sheetGroup.style.display = 'block';
        sheetLabel.textContent = 'Main Talent Sheet URL';
        sheetHint.textContent = 'This sheet will be parsed for cards. All tabs will be available via filters.';
        pipeLinks.style.display = 'block';
    } else if (t === 'Sheet') {
         genGroup.style.display = 'none'; sheetGroup.style.display = 'block'; sheetLabel.textContent = 'Google Sheet URL';
    } else if (t === 'Form') {
        genLabel.textContent = 'Google Form URL';
    } else if (t === 'Form+Sheet') {
        genLabel.textContent = 'Google Form URL'; sheetGroup.style.display = 'block'; sheetLabel.textContent = 'Response Sheet URL';
    } else {
        genLabel.textContent = t + ' URL';
    }
}

// ==================================================
// === QUICK LINK UI FIX ============================
// ==================================================
function addQuickLinkInput(data = null) {
    const container = document.getElementById('quick_links_container');
    const div = document.createElement('div');
    
    // ** UI FIX: Changed to stacked (grid) layout, added border, and relative positioning for delete button **
    div.className = 'relative grid grid-cols-1 gap-y-3 mt-3 border rounded-md p-3 bg-gray-50';
    div.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 text-red-400 hover:text-red-600 bg-white rounded-full p-1 leading-none shadow border" title="Remove link">
            <i class="fas fa-times text-xs" style="width: 1em; height: 1em; vertical-align: middle;"></i>
        </button>
        
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Title</label>
            <input type="text" name="ql_title[]" placeholder="e.g. Back Office Form" class="w-full p-2 border rounded-md text-sm" required value="${data ? data.title : ''}">
        </div>
        
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">URL</label>
            <input type="url" name="ql_url[]" placeholder="https://..." class="w-full p-2 border rounded-md text-sm" required value="${data ? data.url : ''}">
        </div>
        
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="ql_type[]" class="w-full p-2 border rounded-md text-sm">
                <option value="form" ${data && data.type === 'form' ? 'selected' : ''}>Form</option>
                <option value="sheet" ${data && data.type === 'sheet' ? 'selected' : ''}>Sheet</option>
                <option value="link" ${data && data.type === 'link' ? 'selected' : ''}>Other</option>
            </select>
        </div>
    `;
    container.appendChild(div);
}
// ==================================================
// === END QUICK LINK UI FIX ========================
// ==================================================

function selectIcon(el) {
    document.querySelectorAll('.icon-option').forEach(i => i.classList.remove('bg-blue-100', 'border-blue-500'));
    el.classList.add('bg-blue-100', 'border-blue-500');
    document.getElementById('selected_icon').value = el.dataset.value;
}
function showImage(url) { Swal.fire({ imageUrl: url, showConfirmButton: false, background: 'transparent', padding: 0, width: 'auto' }); }
function openDoc(url, type) {
    if (!url || url === 'null') { Swal.fire('Not Available', `The ${type} document is not available.`, 'info'); return; }
    document.getElementById('docViewerModal').style.display = 'block';
    document.getElementById('docViewerFrame').src = url;
    document.getElementById('docViewerTitle').textContent = `${type} Preview`;
    document.getElementById('openExternalLink').href = url;
}
function closeDocViewer() { document.getElementById('docViewerModal').style.display = 'none'; document.getElementById('docViewerFrame').src = ''; }
</script>

<style>
.icon-category-selector { display: flex; flex-wrap: wrap; gap: 20px; align-items: center; }
.category-icon { display: flex; flex-direction: column; align-items: center; cursor: pointer; padding: 15px 20px; border-radius: 8px; transition: all 0.3s ease; flex: 0 0 auto; min-width: auto; border: 2px solid transparent; width: 120px; }
.category-icon:hover { background-color: #f0f0f0; transform: translateY(-3px); }
.category-icon.active { background-color: #e0f7fa; border-bottom: 3px solid #08a88a; }
.category-icon span { margin-top: 5px; font-size: 14px; font-weight: 500; color: #333; text-align: center; }
.quick-link-btn { display: inline-flex; align-items: center; padding: 8px 16px; background: white; color: #1f5497; font-weight: 600; font-size: 13px; border-radius: 6px; border: 1px solid #bae6fd; text-decoration: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; flex-shrink: 0; }
.quick-link-btn:hover { background: #f0f9ff; transform: translateY(-1px); }
.filter-select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; }
.status-dropdown-container { position: absolute; top: 10px; right: 50%; transform: translateX(50%); z-index: 10; padding: 5px 10px; border-radius: 12px; color: white; font-size: 12px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; white-space: nowrap; }
.status-caret { margin-left: 5px; font-size: 10px; }
.status-dropdown { display: none; position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ddd; border-radius: 4px; padding: 5px 0; z-index: 20; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 150px; max-height: 200px; overflow-y: auto; }
.status-dropdown-container.active .status-dropdown { display: block; }
.status-option { padding: 8px 12px; color: #333; cursor: pointer; font-size: 12px; }
.status-option:hover { background: #f5f5f5; }
.card-body { padding: 15px; }
.staff-name { font-size: 1.25rem; font-weight: 600; color: #333; margin-bottom: 10px; text-align: center; margin-top: 10px; }
.info-container p { font-size: 0.9rem; color: #555; margin-bottom: 5px; }
.icon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 1rem; }
.icon-option { display: flex; flex-direction: column; align-items: center; padding: 0.75rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; border: 2px solid transparent; }
.icon-option:hover { background-color: #F3F4F6; }
.icon-option.selected { border-color: #3B82F6; background-color: #eff6ff; }
.pagination-btn { padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; background-color: white; transition: all 0.2s; }
.pagination-btn:hover:not(:disabled) { background-color: #f9fafb; border-color: #d1d5db; }
.pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
@endsection