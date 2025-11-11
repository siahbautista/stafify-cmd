const SWAL_PAYROLL_TEMPLATE = `
<div class="input-row">
    <label for="Name" class="swal2-label">Payroll Name: </label>
    <input id="swal-input1" class="swal2-input" placeholder="Payroll Name">
</div>

<div class="input-row">
    <label for="Frequency" class="swal2-label">Payout Frequency: </label>
    <select id="swal-input2" class="swal2-select">
    <option value="">--Select--</option>
    <option value="5">Weekly</option>
    <option value="2">Bi-Weekly</option>
    <option value="1">Monthly</option>
    </select>
</div>
`;

const SWAL_PAYROLL_CATEGORY_TEMPLATE = `
    <input id="swal-input1" class="swal2-input" placeholder="Category Name">
`;

const SWAL_GEOFENCE_TEMPLATE = `
    <div class="input-row">
    <label for="label" class="swal2-label">Label: </label>
    <input id="geo-label" class="swal2-input" type="text" placeholder="Label">
    </div>
    
    <div class="input-row">
    <label for="employee_type" class="swal2-label">Employee Type: </label>
    <select id="geo-employee_type" class="swal2-select">
        <option value="">--Select--</option>
        <option value="Onsite">Onsite</option>
        <option value="Work From Home">Work From Home</option>
    </select>
    </div>
    
    <div class="input-row">
    <label for="Address" class="swal2-label">Address: </label>
    <input id="geo-address" class="swal2-input" type="text" placeholder="Address">
    </div>
    
    <div class="spacer">
    <button class="btn" onclick="getDeviceLocation()">Get Device Location</button>
    <div id="swal2-message" class="swal2-message"></div>
    </div>
    
    <div class="input-row">
    <label for="Latitude" class="swal2-label">Latitude: </label>
    <input id="geo-latitude" class="swal2-input" type="number" placeholder="Latitude">
    </div>
    
    <div class="input-row">
    <label for="Longitude" class="swal2-label">Longitude: </label>
    <input id="geo-longitude" class="swal2-input" type="number" placeholder="Longitude">
    </div>
    
    <div class="input-row">
    <label for="Radius" class="swal2-label">Radius: </label>
    <select id="geo-radius" class="swal2-select">
        <option value="">--Select Radius--</option>
        <option value="50">50m (Single Room, Small Office, Apartment)</option>
        <option value="100">100m (Medium Office, Small Corporate Building)</option>
        <option value="250">250m (Large Office, Business Park, Big House)</option>
        <option value="500">500m (Corporate Campus, Industrial Area)</option>
    </select>
    </div>
`;