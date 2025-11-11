document.addEventListener('DOMContentLoaded', function() {
  initializeTimeline();

  // Modal controls for Add Event
  const addEventModal = document.getElementById('addEventModal');
  const openAddEventBtn = document.getElementById('openAddEventBtn');
  const closeAddEventModal = document.getElementById('closeAddEventModal');
  const cancelAddEvent = document.getElementById('cancelAddEvent');

  // Function to show the Add Event Modal
  function showAddEventModal() {
    addEventModal.style.opacity = "1";
    addEventModal.style.visibility = "visible";
    addEventModal.style.transform = "translateY(0)";

    // Set default date to today
    document.getElementById('eventDate').valueAsDate = new Date();
  }

  // Function to hide the Add Event Modal
  function hideAddEventModal() {
    addEventModal.style.opacity = "0";
    addEventModal.style.visibility = "hidden";
    addEventModal.style.transform = "translateY(20px)";
    document.getElementById('eventAddForm').reset();
  }

  // Event listeners for modal buttons
  if (openAddEventBtn) {
    openAddEventBtn.addEventListener('click', showAddEventModal);
  }
  
  if (closeAddEventModal) {
    closeAddEventModal.addEventListener('click', hideAddEventModal);
  }
  
  if (cancelAddEvent) {
    cancelAddEvent.addEventListener('click', hideAddEventModal);
  }
  
  // Modal handling
  const assignShiftModal = document.getElementById('assignShiftModal');
  const openAssignShiftBtn = document.getElementById('openAssignShiftBtn');
  const closeAssignShiftModal = document.getElementById('closeAssignShiftModal');
  const cancelAssignShift = document.getElementById('cancelStep1');
  const cancelStep2 = document.getElementById('cancelStep2');
  
  // Step navigation
  const step1Content = document.getElementById('step1Content');
  const step2Content = document.getElementById('step2Content');
  const step1Indicator = document.getElementById('step1Indicator');
  const step2Indicator = document.getElementById('step2Indicator');
  const goToStep2 = document.getElementById('goToStep2');
  const backToStep1 = document.getElementById('backToStep1');
  
  // User selection elements
  const userSearch = document.getElementById('userSearch');
  const userCheckboxes = document.querySelectorAll('.user-checkbox');
  const selectedCountElement = document.querySelector('.selected-count');
  
  // Week picker and schedule table
  const weekPicker = document.getElementById('weekPicker');
  const weeklyScheduleTable = document.getElementById('weeklyScheduleTable');
  const templateButtons = document.querySelectorAll('.template-btn');
  
  // For access level 2, initialize timeline view as well
  const timelineEl = document.getElementById('shift-timeline');
  if (timelineEl && timelineEl.getAttribute('data-shifts')) {
    initializeTimeline();
  }

  // View toggle functionality (only for access level 2)
  const calendarViewBtn = document.getElementById('calendar-view-btn');
  const timelineViewBtn = document.getElementById('timeline-view-btn');
  
  
  if (calendarViewBtn && timelineViewBtn) {
    const calendarContent = document.getElementById('calendar-content');
    const timelineContent = document.getElementById('timeline-content');
    
    calendarViewBtn.addEventListener('click', function() {
      // Activate calendar view
      calendarContent.classList.remove('hidden');
      calendarContent.classList.add('block');
      timelineContent.classList.add('hidden');
      timelineContent.classList.remove('block');
      
      // Update button styles
      calendarViewBtn.classList.remove('btn-outline-primary');
      calendarViewBtn.classList.add('btn-primary', 'active');
      timelineViewBtn.classList.remove('btn-primary', 'active');
      timelineViewBtn.classList.add('btn-outline-primary');
      
      // Trigger resize event to properly render calendar
      if (window.shiftCalendar) {
        window.shiftCalendar.updateSize();
      }
    });
    
    timelineViewBtn.addEventListener('click', function() {
        // Activate timeline view
        timelineContent.classList.remove('hidden');
        timelineContent.classList.add('block');
        calendarContent.classList.add('hidden');
        calendarContent.classList.remove('block');
        
        // Update button styles
        timelineViewBtn.classList.remove('btn-outline-primary');
        timelineViewBtn.classList.add('btn-primary', 'active');
        calendarViewBtn.classList.remove('btn-primary', 'active');
        calendarViewBtn.classList.add('btn-outline-primary');
        
        // Trigger resize event to properly render timeline (if needed)
        if (window.shiftTimeline) {
            window.shiftTimeline.redraw();
        }
    });
  }
  
  // Set default week to current week
  if (weekPicker) {
    const now = new Date();
    const year = now.getFullYear();
    // Calculate week number (ISO)
    const firstDayOfYear = new Date(year, 0, 1);
    const pastDaysOfYear = (now - firstDayOfYear) / 86400000;
    const weekNum = Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    weekPicker.value = `${year}-W${String(weekNum).padStart(2, '0')}`;
    
    // Generate schedule table when week changes
    weekPicker.addEventListener('change', generateWeeklySchedule);
  }
  
  // Initialize user search and filter
  if (userSearch) {
    userSearch.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const userRows = document.querySelectorAll('.user-row');
      
      userRows.forEach(row => {
        const name = row.children[1].textContent.toLowerCase();
        const dept = row.children[2].textContent.toLowerCase();
        const position = row.children[3].textContent.toLowerCase();
        
        if (name.includes(searchTerm) || dept.includes(searchTerm) || position.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
  
  // Count selected users
  userCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
  });
  
  // Handle template buttons
  templateButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      const template = this.getAttribute('data-template');
      applyShiftTemplate(template);
    });
  });
  
  // Open modal
  if (openAssignShiftBtn) {
    openAssignShiftBtn.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      if (assignShiftModal) {
        assignShiftModal.style.opacity = "1";
        assignShiftModal.style.visibility = "visible";
        assignShiftModal.style.transform = "translateY(0)";
        
        // Always start with step 1
        showStep(1);
        
        // Initialize department filters
        initializeDepartmentFilters();
        
        // Reset user selection
        userCheckboxes.forEach(checkbox => {
          checkbox.checked = false;
        });
        updateSelectedCount();
      }
    });
  }
  
  // Navigation between steps
  if (goToStep2) {
    goToStep2.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      // Verify at least one user is selected
      const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
      if (selectedUsers.length === 0) {
        alert('Please select at least one user.');
        return;
      }
      
      // Generate weekly schedule based on current week
      generateWeeklySchedule();
      
      // Display selected users summary
      updateSelectedUsersSummary();
      
      // Show step 2
      showStep(2);
    });
  }
  
  if (backToStep1) {
    backToStep1.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      showStep(1);
    });
  }
  
  // Close modal buttons
  if (closeAssignShiftModal) {
    closeAssignShiftModal.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      if (assignShiftModal) {
        assignShiftModal.style.opacity = "0";
        assignShiftModal.style.visibility = "hidden";
        assignShiftModal.style.transform = "translateY(20px)";
      }
    });
  }
  
  if (cancelAssignShift) {
    cancelAssignShift.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      if (assignShiftModal) {
        assignShiftModal.style.opacity = "0";
        assignShiftModal.style.visibility = "hidden";
        assignShiftModal.style.transform = "translateY(20px)";
      }
    });
  }
  
  if (cancelStep2) {
    cancelStep2.addEventListener('click', function(e) {
      // Prevent form submission
      e.preventDefault();
      if (assignShiftModal) {
        assignShiftModal.style.opacity = "0";
        assignShiftModal.style.visibility = "hidden";
        assignShiftModal.style.transform = "translateY(20px)";
      }
    });
  }
  
  // Close modal when clicking outside
  window.addEventListener('click', function(event) {
    if (event.target === assignShiftModal) {
      assignShiftModal.style.opacity = "0";
      assignShiftModal.style.visibility = "hidden";
      assignShiftModal.style.transform = "translateY(20px)";
    }
  });
  
  // Add event listeners for tabs (unchanged)
  const tabs = document.querySelectorAll('[role="tab"]');
  const tabContents = document.querySelectorAll('[role="tabpanel"]');
  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      // Hide all tab contents
      tabContents.forEach(content => {
        content.classList.add('hidden');
        content.classList.remove('block');
      });
      
      // Show the selected tab content
      const target = document.querySelector(this.getAttribute('data-tabs-target'));
      target.classList.remove('hidden');
      target.classList.add('block');
      
      // Update active state
      tabs.forEach(t => {
        t.setAttribute('aria-selected', 'false');
        t.classList.remove('border-blue-600');
        t.classList.add('border-transparent');
      });
      
      this.setAttribute('aria-selected', 'true');
      this.classList.remove('border-transparent');
      this.classList.add('border-blue-600');
      
      // Trigger resize event for calendar and timeline
      window.dispatchEvent(new Event('resize'));
    });
  });
  
  // Function to show a specific step
  function showStep(stepNumber) {
    if (stepNumber === 1) {
      step1Content.classList.remove('hidden');
      step2Content.classList.add('hidden');
      step1Indicator.classList.add('active');
      step2Indicator.classList.remove('active');
    } else if (stepNumber === 2) {
      step1Content.classList.add('hidden');
      step2Content.classList.remove('hidden');
      step1Indicator.classList.remove('active');
      step2Indicator.classList.add('active');
    }
  }
  
  // Function to update selected users count
  function updateSelectedCount() {
    const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
    if (selectedCountElement) {
      selectedCountElement.textContent = selectedUsers.length;
    }
  }
  
  // Function to initialize department filters
  function initializeDepartmentFilters() {
    const departmentFilters = document.getElementById('departmentFilters');
    if (!departmentFilters) return;
    
    // Clear existing filters
    departmentFilters.innerHTML = '';
    
    // Get unique departments
    const departments = new Set();
    document.querySelectorAll('.user-row').forEach(row => {
      departments.add(row.children[2].textContent);
    });
    
    // Add "All" option
    const allBtn = document.createElement('button');
    allBtn.textContent = 'All';
    allBtn.className = 'btn btn-sm btn-outline filter-btn active';
    allBtn.setAttribute('data-department', 'all');
    allBtn.setAttribute('type', 'button'); // Important: set type to button
    departmentFilters.appendChild(allBtn);
    
    // Add department options
    departments.forEach(dept => {
      const btn = document.createElement('button');
      btn.textContent = dept;
      btn.className = 'btn btn-sm btn-outline filter-btn';
      btn.setAttribute('data-department', dept);
      btn.setAttribute('type', 'button'); // Important: set type to button
      departmentFilters.appendChild(btn);
    });
    
    // Add event listeners to filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        // Prevent form submission
        e.preventDefault();
        
        // Update active state
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const selectedDept = this.getAttribute('data-department');
        filterUsersByDepartment(selectedDept);
      });
    });
  }
  
  // Function to filter users by department
  function filterUsersByDepartment(department) {
    const userRows = document.querySelectorAll('.user-row');
    
    userRows.forEach(row => {
      const rowDept = row.children[2].textContent;
      if (department === 'all' || rowDept === department) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }
  
  // Function to generate weekly schedule table
  function generateWeeklySchedule() {
    if (!weekPicker || !weeklyScheduleTable) return;
    
    const weekValue = weekPicker.value;
    if (!weekValue) return;
    
    const [year, week] = weekValue.split('-W');
    
    // Calculate first day of the week (Monday)
    const firstDay = getFirstDayOfWeek(parseInt(year), parseInt(week));
    
    // Generate table rows for each day
    const tbody = weeklyScheduleTable.querySelector('tbody');
    tbody.innerHTML = '';
    
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
    for (let i = 0; i < 7; i++) {
      const day = new Date(firstDay);
      day.setDate(firstDay.getDate() + i);
      
      // Format the date properly using local components
      const year = day.getFullYear();
      const month = String(day.getMonth() + 1).padStart(2, '0');
      const dayOfMonth = String(day.getDate()).padStart(2, '0');
      const formattedDate = `${year}-${month}-${dayOfMonth}`;
      
      const row = document.createElement('tr');
      row.className = 'day-row';
      row.innerHTML = `
        <td class="p-2">${days[i]}</td>
        <td class="p-2">${day.toLocaleDateString()}</td>
        <td class="p-2 text-center">
          <input type="checkbox" name="assign_day[${formattedDate}]" class="day-checkbox">
        </td>
        <td class="p-2">
          <input type="time" name="start_time[${formattedDate}]" class="form-control start-time" disabled>
        </td>
        <td class="p-2">
          <input type="time" name="end_time[${formattedDate}]" class="form-control end-time" disabled>
        </td>
        <td class="p-2">
          <div class="break-controls" style="display: flex; flex-direction: column; gap: 5px;">
            <label style="display: flex; align-items: center; gap: 5px; font-size: 12px;">
              <input type="checkbox" name="include_break[${formattedDate}]" class="include-break-checkbox" disabled>
              <span>Include Break</span>
            </label>
            <div class="break-duration-group" style="display: none; flex-direction: column; gap: 3px;">
              <label style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                <input type="radio" name="break_duration[${formattedDate}]" value="60" class="break-duration-radio" disabled>
                <span>1 hour</span>
              </label>
              <label style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                <input type="radio" name="break_duration[${formattedDate}]" value="15" class="break-duration-radio" disabled>
                <span>15 mins</span>
              </label>
              <label style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                <input type="radio" name="break_duration[${formattedDate}]" value="custom" class="break-duration-radio break-custom-radio" disabled>
                <span>Custom:</span>
                <input type="number" name="break_custom_minutes[${formattedDate}]" class="break-custom-input" placeholder="mins" min="0" max="480" style="width: 60px; padding: 2px 5px; font-size: 11px;" disabled>
              </label>
            </div>
          </div>
        </td>
        <td class="p-2">
          <input type="text" name="location[${formattedDate}]" class="form-control location" disabled>
        </td>
        <td class="p-2">
          <input type="text" name="notes[${formattedDate}]" class="form-control notes" disabled>
        </td>
      `;
      
      tbody.appendChild(row);
    }
    
    // Add event listeners to day checkboxes
    const dayCheckboxes = document.querySelectorAll('.day-checkbox');
    dayCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        const row = this.closest('tr');
        const inputs = row.querySelectorAll('input:not(.day-checkbox)');
        
        inputs.forEach(input => {
          input.disabled = !this.checked;
        });
      });
    });
    
    // Add event listeners to break checkboxes
    const breakCheckboxes = document.querySelectorAll('.include-break-checkbox');
    breakCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        const row = this.closest('tr');
        const breakDurationGroup = row.querySelector('.break-duration-group');
        const breakRadios = row.querySelectorAll('.break-duration-radio');
        const customInput = row.querySelector('.break-custom-input');
        
        if (this.checked) {
          breakDurationGroup.style.display = 'flex';
          // Enable break duration options
          breakRadios.forEach(radio => {
            radio.disabled = false;
          });
          if (customInput) {
            customInput.disabled = false;
          }
        } else {
          breakDurationGroup.style.display = 'none';
          // Disable and uncheck break duration options
          breakRadios.forEach(radio => {
            radio.disabled = true;
            radio.checked = false;
          });
          if (customInput) {
            customInput.disabled = true;
            customInput.value = '';
          }
        }
      });
    });
    
    // Add event listeners to custom break radio buttons
    const customBreakRadios = document.querySelectorAll('.break-custom-radio');
    customBreakRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const row = this.closest('tr');
        const customInput = row.querySelector('.break-custom-input');
        if (this.checked && customInput) {
          customInput.focus();
        }
      });
    });
  }
  
  function getFirstDayOfWeek(year, week) {
    // This is a more reliable algorithm to get the first day of the week
    // January 4th is always in the first week of the year (ISO 8601)
    const jan4 = new Date(year, 0, 4);
    // Get day of week for January 4th (0 = Sunday, 1 = Monday, etc.)
    const jan4DayOfWeek = jan4.getDay() || 7; // Convert Sunday from 0 to 7
    
    // Calculate the date of the Monday in the first week
    const firstMonday = new Date(year, 0, 4 - jan4DayOfWeek + 1);
    
    // Add the required number of weeks
    const targetMonday = new Date(firstMonday);
    targetMonday.setDate(firstMonday.getDate() + (week - 1) * 7);
    
    return targetMonday;
  }
  
  // Function to apply shift templates
  function applyShiftTemplate(template) {
    // Get all checked day rows
    const checkedDays = document.querySelectorAll('.day-checkbox:checked');
    
    if (checkedDays.length === 0) {
      alert('Please select at least one day first.');
      return;
    }
    
    checkedDays.forEach(checkbox => {
      const row = checkbox.closest('tr');
      const startTimeInput = row.querySelector('.start-time');
      const endTimeInput = row.querySelector('.end-time');
      const locationInput = row.querySelector('.location');
      
      // Apply template values
      switch(template) {
        case 'morning':
          startTimeInput.value = '08:00';
          endTimeInput.value = '17:00';
          locationInput.value = 'Main Office';
          break;
        case 'afternoon':
          startTimeInput.value = '17:00';
          endTimeInput.value = '01:00';
          locationInput.value = 'Main Office';
          break;
        case 'night':
          startTimeInput.value = '21:00';
          endTimeInput.value = '06:00';
          locationInput.value = 'Main Office';
          break;
        case 'custom':
          startTimeInput.value = '00:00';
          endTimeInput.value = '00:00';
          break;
      }
    });
  }
  
  // Function to update selected users summary
  function updateSelectedUsersSummary() {
    const summaryDiv = document.getElementById('selectedUsersSummary');
    if (!summaryDiv) return;
    
    const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
    summaryDiv.innerHTML = '';
    
    if (selectedUsers.length === 0) {
      summaryDiv.textContent = 'No users selected';
      return;
    }
    
    // Create list of selected users
    const userList = document.createElement('ul');
    userList.className = 'selectedUsers';
    
    selectedUsers.forEach(checkbox => {
      const row = checkbox.closest('tr');
      const name = row.children[1].textContent;
      const dept = row.children[2].textContent;
      const position = row.children[3].textContent;
      
      const userId = checkbox.value;
      
      const li = document.createElement('li');
      li.textContent = `${name} (${dept} - ${position})`;
      li.className = 'selected-user-item';
      
      // Add hidden input to form to include user ID
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'selected_users[]';
      hiddenInput.value = userId;
      li.appendChild(hiddenInput);
      
      userList.appendChild(li);
    });
    
    summaryDiv.appendChild(userList);
  }
});

// Modify the initializeCalendar function to include events
const originalInitializeCalendar = window.initializeCalendar;
  
window.initializeCalendar = function() {
  const calendarEl = document.getElementById('shift-calendar');
  if (!calendarEl) {
    console.error("Calendar element not found");
    return;
  }

  // Destroy any existing calendar instance before creating a new one
  if (calendarEl._fullCalendar) {
    calendarEl._fullCalendar.destroy();
  }
  
  // Get access level from data attribute
  const accessLevel = parseInt(calendarEl.getAttribute('data-access-level') || '0');
  
  // Get shifts data from the data attribute
  let shiftsData = [];
  try {
    shiftsData = JSON.parse(calendarEl.getAttribute('data-shifts') || '[]');
  } catch (e) {
    console.error("Error parsing shifts data:", e);
  }
  
  // Get events data from the data attribute
  let eventsData = [];
  try {
    eventsData = JSON.parse(calendarEl.getAttribute('data-events') || '[]');
  } catch (e) {
    console.error("Error parsing events data:", e);
  }
  
  // Add user filter for access level 2
  let selectedUserId = null;
  if (accessLevel === 2) {
    // Create user filter dropdown
    const filterContainer = document.createElement('div');
    filterContainer.className = 'user-filter-container';
    filterContainer.style.marginBottom = '15px';
    
    const userSelect = document.createElement('select');
    userSelect.id = 'user-filter-select';
    userSelect.className = 'form-select !w-[300px] !pr-7';
    
    // Get unique users from shifts data
    const users = [];
    const userIds = new Set();
    
    shiftsData.forEach(shift => {
      if (!userIds.has(shift.user_id)) {
        userIds.add(shift.user_id);
        users.push({
          id: shift.user_id,
          name: shift.employee_name
        });
      }
    });
    
    // Sort users alphabetically
    users.sort((a, b) => a.name.localeCompare(b.name));
    
    // Add "All Users" option
    const allOption = document.createElement('option');
    allOption.value = '';
    allOption.textContent = 'Select User';
    userSelect.appendChild(allOption);
    
    // Add user options
    users.forEach(user => {
      const option = document.createElement('option');
      option.value = user.id;
      option.textContent = user.name;
      userSelect.appendChild(option);
    });
    
    // Create label
    const label = document.createElement('label');
    label.htmlFor = 'user-filter-select';
    label.textContent = 'Filter by User:';
    label.className = 'form-label';
    
    // Append elements
    filterContainer.appendChild(label);
    filterContainer.appendChild(userSelect);
    
    // Insert filter before the calendar
    calendarEl.parentNode.insertBefore(filterContainer, calendarEl);
    
    // Add change event listener
    userSelect.addEventListener('change', function() {
      selectedUserId = this.value;
      updateCalendarEvents();
    });
  }
  
  // Function to update calendar events based on filter
  function updateCalendarEvents() {
    if (calendarEl._fullCalendar) {
      // Remove all shift events
      const events = calendarEl._fullCalendar.getEvents();
      events.forEach(event => {
        if (event.id && event.id.startsWith('shift_')) {
          event.remove();
        }
      });
      
      // Filter shifts based on selected user
      let filteredShifts = shiftsData;
      if (accessLevel === 2 && selectedUserId) {
        filteredShifts = shiftsData.filter(shift => shift.user_id == selectedUserId);
      }
      
      // Add filtered shift events back to calendar
      const shiftEvents = filteredShifts.map(shift => {
        const startDateTime = `${shift.shift_date}T${shift.start_time}`;
        const endDateTime = `${shift.shift_date}T${shift.end_time}`;
        
        const startDate = new Date(startDateTime);
        const endDate = new Date(endDateTime);
        
        const formatTime = (timeStr) => {
          const [hours, minutes] = timeStr.split(':');
          const date = new Date();
          date.setHours(parseInt(hours), parseInt(minutes), 0);
          return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        };
        
        const displayTime = `${formatTime(shift.start_time)} - ${formatTime(shift.end_time)}`;
        
        if (endDate < startDate) {
          endDate.setDate(endDate.getDate() + 1);
        }
        
        return {
          id: 'shift_' + shift.shift_id,
          title: displayTime,
          start: startDateTime,
          end: endDate.toISOString(),
          extendedProps: {
            type: 'shift',
            employeeName: shift.employee_name,
            shiftType: shift.shift_type,
            location: shift.location,
            assignedBy: shift.assigned_by_name,
            notes: shift.notes
          },
          backgroundColor: getShiftTypeColor(shift.shift_type),
          display: 'block',
          timeText: '',
          classNames: ['shift-bar-event']
        };
      });
      
      // Add filtered events to calendar
      shiftEvents.forEach(event => {
        calendarEl._fullCalendar.addEvent(event);
      });
    }
  }

  // Convert shifts to calendar events - for initial load
  const shiftEvents = accessLevel === 2 ? [] : shiftsData.map(shift => {
    // Create full datetime strings for start and end
    const startDateTime = `${shift.shift_date}T${shift.start_time}`;
    const endDateTime = `${shift.shift_date}T${shift.end_time}`;
    
    // Create Date objects to check if end time is before start time (indicating overnight shift)
    const startDate = new Date(startDateTime);
    const endDate = new Date(endDateTime);
    
    // Format times for display (e.g., "9:00 AM - 5:00 PM")
    const formatTime = (timeStr) => {
      const [hours, minutes] = timeStr.split(':');
      const date = new Date();
      date.setHours(parseInt(hours), parseInt(minutes), 0);
      return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    };
    
    const displayTime = `${formatTime(shift.start_time)} - ${formatTime(shift.end_time)}`;
    
    // If end time is earlier than start time, assume it's the next day
    if (endDate < startDate) {
      // Add one day to the end date
      endDate.setDate(endDate.getDate() + 1);
    }
    
    return {
      id: 'shift_' + shift.shift_id,
      title: displayTime, // Show the time range
      start: startDateTime,
      end: endDate.toISOString(), // Use the modified end date if it spans to next day
      extendedProps: {
        type: 'shift',
        employeeName: shift.employee_name,
        shiftType: shift.shift_type,
        location: shift.location,
        assignedBy: shift.assigned_by_name,
        notes: shift.notes
      },
      backgroundColor: getShiftTypeColor(shift.shift_type),
      display: 'block',
      timeText: '', 
      classNames: ['shift-bar-event']
    };
  });

  const eventStyles = document.createElement('style');
  eventStyles.textContent = `
    .fc-event-time {
      display: none !important;
    }
  `;
  document.head.appendChild(eventStyles);

  // Convert custom events to calendar events
  const customEvents = eventsData.map(event => {
    return {
      id: 'event_' + event.event_id,
      title: event.event_title,
      start: `${event.event_date}T${event.start_time}`,
      end: `${event.event_date}T${event.end_time}`,
      extendedProps: {
        type: 'custom',
        location: event.event_location,
        createdBy: event.created_by_name,
        eventType: event.event_type,
        description: event.event_description
      },
      backgroundColor: getEventTypeColor(event.event_type),
      borderColor: getEventTypeColor(event.event_type),
      display: 'block',
      classNames: ['event-bar-custom']
    };
  });
  
  // Add our holiday events - these will show for all access levels
  const holidays2025 = [
    // Regular Holidays
    {
      title: 'New Year\'s Day',
      start: '2025-01-01',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Maundy Thursday',
      start: '2025-04-17',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Good Friday',
      start: '2025-04-18',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Araw ng Kagitingan',
      start: '2025-04-09',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Labor Day',
      start: '2025-05-01',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Independence Day',
      start: '2025-06-12',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'National Heroes Day',
      start: '2025-08-25',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Bonifacio Day', 
      start: '2025-11-30',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Christmas Day',
      start: '2025-12-25',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    {
      title: 'Rizal Day',
      start: '2025-12-30',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(255, 215, 0, 0.3)',
      borderColor: '#FFD700',
      classNames: ['holiday-cell', 'regular-holiday']
    },
    
    // Special Non-working Holidays
    {
      title: 'Chinese New Year',
      start: '2025-01-29',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'EDSA People Power Revolution',
      start: '2025-02-25',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'Black Saturday',
      start: '2025-04-19',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'Eid al-Fitr',
      start: '2025-03-31',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'Eid al-Adha',
      start: '2025-06-28',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'All Saints\' Day',
      start: '2025-11-01',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'All Souls\' Day',
      start: '2025-11-02',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'Feast of the Immaculate Conception',
      start: '2025-12-08',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    },
    {
      title: 'New Year\'s Eve',
      start: '2025-12-31',
      allDay: true,
      display: 'background',
      backgroundColor: 'rgba(250, 168, 135, 0.3)',
      borderColor: '#FFA500',
      classNames: ['holiday-cell', 'special-holiday']
    }
  ];
  
  // Create a legend for the holidays
  const legendDiv = document.createElement('div');
  legendDiv.className = 'holiday-legend';
  legendDiv.innerHTML = `
    <div class="legend-item">
      <div class="legend-box" style="background-color: #FFD700;"></div>
      <span>Regular Holiday</span>
    </div>
    <div class="legend-item">
      <div class="legend-box" style="background-color: #FFA500;"></div>
      <span>Special Non-working Holiday</span>
    </div>
  `;
  
  // Merge all events
  const allEvents = [...shiftEvents, ...customEvents, ...holidays2025];
  
  // Initialize calendar with combined events
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'title',
      center: '',
      right: 'prev,next today'
    },
    events: allEvents,
    eventTimeFormat: {
      hour: '2-digit',
      minute: '2-digit',
      meridiem: 'short'
    },
    eventClick: function(info) {
      // Only show details for non-holiday events
      if (!info.event.classNames.includes('holiday-cell')) {
        if (info.event.extendedProps.type === 'shift') {
          showShiftEventDetails(info.event);
        } else if (info.event.extendedProps.type === 'custom') {
          showCustomEventDetails(info.event);
        }
      }
    },
    // Add day cell render hooks to highlight holiday days
    dayCellDidMount: function(info) {
      // Get all holiday dates
      const holidayDates = holidays2025.map(h => h.start);
      
      // Format the current cell's date to match our holiday format
      const cellDateStr = info.date.toISOString().split('T')[0];
      
      // Check if this date is a holiday
      if (holidayDates.includes(cellDateStr)) {
        info.el.classList.add('holiday-day');
        
        // Add holiday names as tooltips
        const matchingHolidays = holidays2025.filter(h => h.start === cellDateStr);
        if (matchingHolidays.length > 0) {
          const titles = matchingHolidays.map(h => h.title).join(', ');
          info.el.setAttribute('title', titles);
        }
      }
    }
  });
  
  // Store the reference to FullCalendar for later use
  calendarEl._fullCalendar = calendar;
  
  // Render the calendar
  calendar.render();
  
  // Add the legend below the header toolbar
  setTimeout(() => {
    const toolbarEl = calendarEl.querySelector('.fc-header-toolbar');
    if (toolbarEl) {
      toolbarEl.parentNode.insertBefore(createLegend(), toolbarEl.nextSibling);
    }
  }, 0);
}

// Function to create an enhanced legend including event types
function createLegend() {
  const legendDiv = document.createElement('div');
  legendDiv.className = 'holiday-legend';
  
  // Get access level from window or from calendar element
  const calendarEl = document.getElementById('shift-calendar');
  const accessLevel = typeof window.userAccessLevel !== 'undefined' ? 
    window.userAccessLevel : 
    (calendarEl ? parseInt(calendarEl.getAttribute('data-access-level') || '0') : 0);
  
  // Holiday legends are shown for both access levels 2 and 3
  let legendHTML = `
    <div class="legend-item">
      <div class="legend-box" style="background-color: #FFD700;"></div>
      <span>Regular Holiday</span>
    </div>
    <div class="legend-item">
      <div class="legend-box" style="background-color: #FFA500;"></div>
      <span>Special Non-working Holiday</span>
    </div>
  `;
  
  // Add event type legends for both access levels 2 and 3
  legendHTML += `
    <div class="legend-item">
      <div class="legend-box" style="background-color: #E91E63;"></div>
      <span>Meeting</span>
    </div>
    <div class="legend-item">
      <div class="legend-box" style="background-color: #FF5722;"></div>
      <span>Training</span>
    </div>
  `;
  
  // Only show shift type legends for access level 3
  if (accessLevel === 3) {
    legendHTML += `
      <div class="legend-item">
        <div class="legend-box" style="background-color: #4CAF50;"></div>
        <span>Morning Shift</span>
      </div>
      <div class="legend-item">
        <div class="legend-box" style="background-color: #2196F3;"></div>
        <span>Afternoon Shift</span>
      </div>
      <div class="legend-item">
        <div class="legend-box" style="background-color: #673AB7;"></div>
        <span>Night Shift</span>
      </div>
      <div class="legend-item">
        <div class="legend-box" style="background-color: #9E9E9E;"></div>
        <span>Other Shifts</span>
      </div>
    `;
  }
  
  legendDiv.innerHTML = legendHTML;
  return legendDiv;
}

// Function to determine color based on event type
function getEventTypeColor(eventType) {
  const lowerType = (eventType || '').toLowerCase();
  
  if (lowerType === 'meeting') {
    return '#E91E63'; // Pink for meetings
  } else if (lowerType === 'training') {
    return '#FF5722'; // Deep Orange for training
  } else if (lowerType === 'holiday') {
    return '#FFC107'; // Yellow for holiday events
  } else if (lowerType === 'announcement') {
    return '#009688'; // Teal for announcements
  } else {
    return '#607D8B'; // Blue Grey for other event types
  }
}

// Function to determine color based on shift type
function getShiftTypeColor(shiftType) {
  const lowerType = (shiftType || '').toLowerCase();
  
  if (lowerType.includes('morning')) {
    return '#4CAF50'; // Green for morning shifts
  } else if (lowerType.includes('afternoon')) {
    return '#2196F3'; // Blue for afternoon shifts
  } else if (lowerType.includes('night')) {
    return '#673AB7'; // Purple for night shifts
  } else if (lowerType.includes('holiday')) {
    return '#FFC107'; // Yellow for holiday shifts
  } else {
    return '#9E9E9E'; // Grey for other/undefined shift types
  }
}

// Helper function to format date
function formatDate(date) {
  return new Date(date).toLocaleDateString('en-PH', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

// Helper function to format time
function formatTime(date) {
  return new Date(date).toLocaleTimeString('en-PH', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
}

// Helper function to strip HTML tags for alert display
function stripTags(html) {
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return doc.body.textContent || '';
}

// Initialize when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize the calendar
  initializeCalendar();
  
  // If timeline should be displayed for access level 2, initialize it
  const userAccessLevel = typeof window.userAccessLevel !== 'undefined' ? window.userAccessLevel : 0;
  const timelineEl = document.getElementById('shift-timeline');
  
  if (userAccessLevel === 2 && timelineEl) {
    initializeTimeline();
  }
});

function initializeTimeline() {
  const timelineEl = document.getElementById('shift-timeline');
  if (!timelineEl) return;
  
  // Get shifts data from the data attribute
  const shiftsData = JSON.parse(timelineEl.getAttribute('data-shifts') || '[]');
  
  // Get unique employees
  const employees = [...new Set(shiftsData.map(shift => shift.employee_name))];
  
  // Get unique shift types for the legend
  const shiftTypes = [...new Set(shiftsData.map(shift => shift.shift_type))];
  
  // Create timeline groups (one for each employee)
  const groups = employees.map((name, index) => ({
    id: index + 1,
    content: name
  }));
  
  // Function to format time in AM/PM format
  function formatTimeToAMPM(timeStr) {
    // Check if time is already in proper format
    if (timeStr.includes('AM') || timeStr.includes('PM')) {
      return timeStr;
    }
    
    // Assuming timeStr is in 24-hour format like "14:30"
    const [hours, minutes] = timeStr.split(':').map(num => parseInt(num, 10));
    const period = hours >= 12 ? 'PM' : 'AM';
    const formattedHours = hours % 12 || 12; // Convert 0 to 12 for 12 AM
    return `${formattedHours}:${minutes.toString().padStart(2, '0')} ${period}`;
  }
  
  // Create timeline items - occupying the whole day
  const items = shiftsData.map((shift, index) => {
    const employeeIndex = employees.indexOf(shift.employee_name) + 1;
    
    // Create date objects for the shift date
    const shiftDate = new Date(shift.shift_date);
    
    // Set start time to beginning of day (00:00:00)
    const startTime = new Date(shiftDate);
    startTime.setHours(0, 0, 0, 0);
    
    // Set end time to end of day (23:59:59)
    const endTime = new Date(shiftDate);
    endTime.setHours(23, 59, 59, 999);
    
    // Format times in AM/PM format
    const formattedStartTime = formatTimeToAMPM(shift.start_time);
    const formattedEndTime = formatTimeToAMPM(shift.end_time);
    
    // Use formatted start_time and end_time as the content
    const timeDisplay = `${formattedStartTime}-${formattedEndTime}`;
    
    // Get the color for this shift type
    const backgroundColor = getShiftTypeColor(shift.shift_type);
    
    // Determine text color based on background brightness
    const textColor = isColorDark(backgroundColor) ? 'white' : 'black';
    
    return {
      id: shift.shift_id || index,  // Use the shift_id if available
      group: employeeIndex,
      content: timeDisplay,
      title: `<div class="shift-tooltip">
                <div class="shift-type">${shift.shift_type}</div>
                <div class="shift-details">
                  <div><strong>Time:</strong> ${formattedStartTime}-${formattedEndTime}</div>
                  <div><strong>Location:</strong> ${shift.location}</div>
                  ${shift.notes ? `<div><strong>Notes:</strong> ${shift.notes}</div>` : ''}
                </div>
              </div>`,
      start: startTime,
      end: endTime,
      style: `background-color: ${backgroundColor}; color: ${textColor}; border-radius: 4px; padding: 2px 4px; font-weight: 500;`,
      // Store original shift data for later use when clicked
      shift: shift
    };
  });
  
  // Store current week offset (0 = current week)
  let currentWeekOffset = 0;
  
  // Function to calculate week dates based on offset
  function getWeekDates(weekOffset) {
    const now = new Date();
    const dayOfWeek = now.getDay(); // 0 is Sunday, 1 is Monday, etc.
    
    // Determine how many days to go back to reach Monday
    const daysFromMonday = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
    
    const startOfWeek = new Date(now);
    startOfWeek.setHours(0, 0, 0, 0);
    startOfWeek.setDate(now.getDate() - daysFromMonday + (weekOffset * 7)); // Start of week (Monday)
    
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 7); // End of week (next Monday)
    
    return { start: startOfWeek, end: endOfWeek };
  }
  
  // Function to format date as "Mon DD, YYYY"
  function formatDate(date) {
    const options = { weekday: 'short', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
  }
  
  // Function to determine if a color is dark (for text contrast)
  function isColorDark(hexColor) {
    // Remove the # if present
    hexColor = hexColor.replace('#', '');
    
    // Convert hex to RGB
    const r = parseInt(hexColor.substr(0, 2), 16);
    const g = parseInt(hexColor.substr(2, 2), 16);
    const b = parseInt(hexColor.substr(4, 2), 16);
    
    // Calculate luminance (perceived brightness)
    // Using the formula: 0.299*R + 0.587*G + 0.114*B
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    
    // Return true if the color is dark (luminance less than 0.5)
    return luminance < 0.5;
  }
  
  // Function to update timeline range and week indicator
  function updateTimelineRange() {
    const { start, end } = getWeekDates(currentWeekOffset);
    
    // Update timeline range
    timeline.setWindow(start, end, { animation: true });
    
    // Update week indicator
    const endOfDisplayedWeek = new Date(end);
    endOfDisplayedWeek.setDate(end.getDate() - 1); // Go back 1 day to get Sunday
    
    document.getElementById('week-indicator').textContent = 
      `${formatDate(start)} - ${formatDate(endOfDisplayedWeek)}`;
  }
  
  // Create the timeline container
  const container = document.createElement('div');
  container.className = 'timeline-container';
  container.style.cssText = 'height: 100%; display: flex; flex-direction: column;';
  
  // Add the control panel and navigation
  const controlPanel = document.createElement('div');
  controlPanel.className = 'timeline-control-panel';
  controlPanel.style.cssText = 'display: flex; justify-content: space-between; margin-bottom: 15px; align-items: center;';
  
  // Create navigation controls
  const navigationDiv = document.createElement('div');
  navigationDiv.className = 'timeline-navigation';
  navigationDiv.style.cssText = 'display: flex; gap: 10px; align-items: center;';
  
  // Previous week button
  const prevButton = document.createElement('button');
  prevButton.textContent = '← Previous Week';
  prevButton.style.cssText = 'padding: 6px 12px; cursor: pointer; border: 1px solid #ddd; border-radius: 4px; background: #f8f9fa;';
  prevButton.onmouseover = function() { this.style.background = '#e9ecef'; };
  prevButton.onmouseout = function() { this.style.background = '#f8f9fa'; };
  prevButton.onclick = function() {
    currentWeekOffset--;
    updateTimelineRange();
  };
  
  // Current week button
  const currentButton = document.createElement('button');
  currentButton.textContent = 'Current Week';
  currentButton.style.cssText = 'padding: 6px 12px; cursor: pointer; border: 1px solid #0d6efd; border-radius: 4px; background: #0d6efd; color: white;';
  currentButton.onmouseover = function() { this.style.background = '#0b5ed7'; };
  currentButton.onmouseout = function() { this.style.background = '#0d6efd'; };
  currentButton.onclick = function() {
    currentWeekOffset = 0;
    updateTimelineRange();
  };
  
  // Next week button
  const nextButton = document.createElement('button');
  nextButton.textContent = 'Next Week →';
  nextButton.style.cssText = 'padding: 6px 12px; cursor: pointer; border: 1px solid #ddd; border-radius: 4px; background: #f8f9fa;';
  nextButton.onmouseover = function() { this.style.background = '#e9ecef'; };
  nextButton.onmouseout = function() { this.style.background = '#f8f9fa'; };
  nextButton.onclick = function() {
    currentWeekOffset++;
    updateTimelineRange();
  };
  
  // Week indicator display
  const weekIndicator = document.createElement('div');
  weekIndicator.id = 'week-indicator';
  weekIndicator.style.cssText = 'font-weight: bold; padding: 6px 12px; background-color: #f8f9fa; border-radius: 4px; border: 1px solid #ddd;';
  
  // Add elements to the navigation div
  navigationDiv.appendChild(prevButton);
  navigationDiv.appendChild(currentButton);
  navigationDiv.appendChild(nextButton);
  navigationDiv.appendChild(weekIndicator);
  
  // Create legend section
  const legendDiv = document.createElement('div');
  legendDiv.className = 'timeline-legend';
  legendDiv.style.cssText = 'display: flex; gap: 10px; flex-wrap: wrap;';
  
  // Add legend items
  shiftTypes.forEach(shiftType => {
    const color = getShiftTypeColor(shiftType);
    const textColor = isColorDark(color) ? 'white' : 'black';
    
    const legendItem = document.createElement('div');
    legendItem.className = 'legend-item';
    legendItem.style.cssText = 'display: flex; align-items: center; gap: 5px;';
    
    const colorBox = document.createElement('div');
    colorBox.style.cssText = `width: 16px; height: 16px; background-color: ${color}; border-radius: 3px;`;
    
    const label = document.createElement('span');
    label.textContent = shiftType;
    
    legendItem.appendChild(colorBox);
    legendItem.appendChild(label);
    legendDiv.appendChild(legendItem);
  });
  
  // Add navigation and legend to control panel
  controlPanel.appendChild(navigationDiv);
  controlPanel.appendChild(legendDiv);
  
  // Create the actual timeline element
  const timelineContainer = document.createElement('div');
  timelineContainer.id = 'timeline-container';
  timelineContainer.style.cssText = 'flex-grow: 1; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; height: calc(100% - 60px);';
  
  // Append elements to the main container
  container.appendChild(controlPanel);
  container.appendChild(timelineContainer);
  
  // Replace the original element with our enhanced container
  timelineEl.innerHTML = '';
  timelineEl.appendChild(container);
  
  // Initial week dates
  const initialWeek = getWeekDates(currentWeekOffset);
  
  // Create timeline with modified options
  const timeline = new vis.Timeline(timelineContainer, new vis.DataSet(items), new vis.DataSet(groups), {
    stack: false,
    zoomable: false,
    moveable: false,
    orientation: 'top',
    margin: {
      item: 10,
      axis: 5
    },
    format: {
      minorLabels: {
        day: 'ddd D', 
        hour: 'h A',
        minute: 'h:mm A'
      }
    },
    // Set initial view range
    start: initialWeek.start,
    end: initialWeek.end,
    min: undefined,
    max: undefined,
    showCurrentTime: true,
    showMajorLabels: true,
    showMinorLabels: true,
    timeAxis: { scale: 'day', step: 1 }
  });

  // Add custom stylesheet for tooltips
  const styleSheet = document.createElement('style');
  styleSheet.textContent = `
    .shift-tooltip {
      font-family: Arial, sans-serif;
      padding: 8px;
      max-width: 300px;
    }
    .shift-type {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 5px;
      border-bottom: 1px solid #eee;
      padding-bottom: 5px;
    }
    .shift-details {
      font-size: 14px;
      line-height: 1.4;
    }
    .shift-details div {
      margin-bottom: 3px;
    }
    .vis-item {
      border-color: rgba(0,0,0,0.1) !important;
      box-shadow: 0 1px 2px rgba(0,0,0,0.1);
      cursor: pointer;
    }
    .vis-time-axis .vis-text {
      padding: 5px 0;
      color: #555;
    }
    .vis-labelset .vis-label {
      padding: 8px 5px;
      font-weight: 500;
      color: #333;
    }
  `;
  document.head.appendChild(styleSheet);
  
  // Initialize week indicator
  updateTimelineRange();
  
  // Add click event listener to timeline items
  timeline.on('click', function(properties) {
    // Check if the click was on an item
    if (properties.item) {
      // Find the clicked item
      const itemsDataSet = timeline.itemsData.get();
      const clickedItem = itemsDataSet.find(item => item.id == properties.item);
      
      if (clickedItem && clickedItem.shift) {
        // Create a mock event object for showShiftEventDetails
        const mockEvent = {
          title: `${clickedItem.shift.employee_name} (${clickedItem.shift.shift_type})`,
          start: new Date(`${clickedItem.shift.shift_date}T${clickedItem.shift.start_time}`),
          end: new Date(`${clickedItem.shift.shift_date}T${clickedItem.shift.end_time}`),
          extendedProps: {
            location: clickedItem.shift.location,
            assignedBy: clickedItem.shift.assigned_by_name,
            notes: clickedItem.shift.notes
          }
        };
        
        // Show the event details modal
        showTimelineShiftEventDetails(mockEvent);
      }
    }
  });
}

function getShiftTypeColor(shiftType) {
  // Return colors based on shift type
  switch(shiftType) {
    case 'Morning':
      return '#4CAF50'; // Green
    case 'Afternoon':
      return '#2196F3'; // Blue
    case 'Night':
      return '#673AB7'; // Deep Purple
    case 'Custom':
      return '#FF9800'; // Orange
    default:
      return '#9E9E9E'; // Grey
  }
}

function showShiftEventDetails(event) {
  const props = event.extendedProps;
  
  // Safely extract shift type from title
  let employeeName = event.title;
  let shiftType = 'N/A';
  
  if (event.title && event.title.includes('(')) {
    // Extract employee name and shift type
    const titleParts = event.title.split(' (');
    employeeName = titleParts[0];
    
    if (titleParts.length > 1 && titleParts[1]) {
      shiftType = titleParts[1].replace(')', '');
    }
  }
  
  // Create and show a modal with event details
  const detailsHTML = `
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="eventDetailsModal">
      <div class="bg-white rounded-lg w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b p-4">
          <h3 class="text-lg font-semibold">Shift Details</h3>
          <button class="text-gray-500 hover:text-gray-700" onclick="closeEventDetailsModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="p-6">
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Employee:</p>
            <p>${employeeName}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Shift Type:</p>
            <p>${shiftType}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Date:</p>
            <p>${event.start ? event.start.toLocaleDateString() : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Time:</p>
            <p>${event.start ? event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A'} - ${event.end ? event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Location:</p>
            <p>${props && props.location ? props.location : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Assigned By:</p>
            <p>${props && props.assignedBy ? props.assignedBy : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Notes:</p>
            <p>${props && props.notes ? props.notes : 'No notes provided'}</p>
          </div>
          <div class="flex justify-end mt-4">
            <button class="btn btn-primary" onclick="closeEventDetailsModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Insert modal into document
  document.body.insertAdjacentHTML('beforeend', detailsHTML);
}

function showTimelineShiftEventDetails(event) {
  const props = event.extendedProps;
  
  // Safely extract shift type from title
  let employeeName = event.title;
  let shiftType = 'N/A';
  
  if (event.title && event.title.includes('(')) {
    // Extract employee name and shift type
    const titleParts = event.title.split(' (');
    employeeName = titleParts[0];
    
    if (titleParts.length > 1 && titleParts[1]) {
      shiftType = titleParts[1].replace(')', '');
    }
  }
  
  // Create and show a modal with event details
  const detailsHTML = `
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="eventDetailsModal">
      <div class="bg-white rounded-lg w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b p-4">
          <h3 class="text-lg font-semibold">Shift Details</h3>
          <button class="text-gray-500 hover:text-gray-700" onclick="closeEventDetailsModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="p-6">
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Employee:</p>
            <p>${employeeName}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Shift Type:</p>
            <p>${shiftType}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Date:</p>
            <p>${event.start ? event.start.toLocaleDateString() : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Time:</p>
            <p>${event.start ? event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A'} - ${event.end ? event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Location:</p>
            <p>${props && props.location ? props.location : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Assigned By:</p>
            <p>${props && props.assignedBy ? props.assignedBy : 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Notes:</p>
            <p>${props && props.notes ? props.notes : 'No notes provided'}</p>
          </div>
          <div class="flex justify-end mt-4">
            <button class="btn btn-primary" onclick="closeEventDetailsModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Insert modal into document
  document.body.insertAdjacentHTML('beforeend', detailsHTML);
}

function closeEventDetailsModal() {
  const modal = document.getElementById('eventDetailsModal');
  if (modal) {
    modal.remove();
  }
}

function showCustomEventDetails(event) {
  const props = event.extendedProps;
  
  // Create and show a modal with event details
  const detailsHTML = `
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="eventDetailsModal">
      <div class="bg-white rounded-lg w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b p-4">
          <h3 class="text-lg font-semibold">Event Details</h3>
          <button class="text-gray-500 hover:text-gray-700" onclick="closeEventDetailsModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="p-6">
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Title:</p>
            <p>${event.title}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Event Type:</p>
            <p>${props.eventType || 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Date:</p>
            <p>${event.start.toLocaleDateString()}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Time:</p>
            <p>${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Location:</p>
            <p>${props.location || 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Created By:</p>
            <p>${props.createdBy || 'N/A'}</p>
          </div>
          <div class="mb-3">
            <p class="text-gray-700 font-semibold">Description:</p>
            <p>${props.description || 'No description provided'}</p>
          </div>
          <div class="flex justify-end mt-4">
            <button class="btn btn-primary" onclick="closeEventDetailsModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Insert modal into document
  document.body.insertAdjacentHTML('beforeend', detailsHTML);
}
