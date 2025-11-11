<!-- Performance Evaluation Modal -->
<div id="performanceEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b px-6 py-4 sticky top-0 bg-white z-10">
            <h3 class="text-lg font-semibold" id="evaluationModalTitle">Performance Evaluation</h3>
            <button onclick="closeEvaluationModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="performanceEvaluationForm">
                <input type="hidden" id="evaluationUserId" name="user_id">
                <input type="hidden" id="evaluationId" name="evaluation_id">
                
                <!-- Evaluation Header -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="evaluationDate" class="block text-sm font-medium text-gray-700 mb-1">Evaluation Date</label>
                        <input type="date" id="evaluationDate" name="evaluation_date" class="w-full px-3 py-2 border rounded-md" required>
                    </div>
                    <div>
                        <label for="evaluatorName" class="block text-sm font-medium text-gray-700 mb-1">Evaluator/Supervisor</label>
                        <input type="text" id="evaluatorName" name="evaluator_name" class="w-full px-3 py-2 border rounded-md" required>
                    </div>
                    <div>
                        <label for="evaluationType" class="block text-sm font-medium text-gray-700 mb-1">Evaluation Type</label>
                        <select id="evaluationType" name="evaluation_type" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="">Select Type</option>
                            <option value="1st">1st Evaluation</option>
                            <option value="2nd">2nd Evaluation</option>
                            <option value="3rd">3rd Evaluation</option>
                        </select>
                    </div>
                </div>

                <!-- Performance Criteria -->
                <div class="space-y-4">
                    <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Performance Evaluation Criteria</h4>
                    
                    <!-- Job Performance Section -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700 bg-blue-50 px-3 py-1 rounded">Job Performance</h5>
                        
                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Job Knowledge</label>
                            <div class="star-rating" data-field="job_knowledge">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="jobKnowledge" name="job_knowledge" value="0">
                            <small class="text-gray-500">Understanding of job requirements and procedures</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Productivity</label>
                            <div class="star-rating" data-field="productivity">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="productivity" name="productivity" value="0">
                            <small class="text-gray-500">Amount of work completed efficiently</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Work Quality</label>
                            <div class="star-rating" data-field="work_quality">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="workQuality" name="work_quality" value="0">
                            <small class="text-gray-500">Accuracy and thoroughness of work output</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Technical Skills</label>
                            <div class="star-rating" data-field="technical_skills">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="technicalSkills" name="technical_skills" value="0">
                            <small class="text-gray-500">Proficiency in required technical competencies</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Work Consistency</label>
                            <div class="star-rating" data-field="work_consistency">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="workConsistency" name="work_consistency" value="0">
                            <small class="text-gray-500">Reliability and consistency in performance</small>
                        </div>
                    </div>

                    <!-- Work Attitude Section -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700 bg-green-50 px-3 py-1 rounded">Work Attitude & Behavior</h5>
                        
                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enthusiasm</label>
                            <div class="star-rating" data-field="enthusiasm">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="enthusiasm" name="enthusiasm" value="0">
                            <small class="text-gray-500">Motivation and eagerness towards work</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cooperation</label>
                            <div class="star-rating" data-field="cooperation">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="cooperation" name="cooperation" value="0">
                            <small class="text-gray-500">Willingness to work with others</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Attitude</label>
                            <div class="star-rating" data-field="attitude">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="attitude" name="attitude" value="0">
                            <small class="text-gray-500">General approach and disposition at work</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Initiative</label>
                            <div class="star-rating" data-field="initiative">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="initiative" name="initiative" value="0">
                            <small class="text-gray-500">Self-motivation and proactive behavior</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Work Relations</label>
                            <div class="star-rating" data-field="work_relations">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="workRelations" name="work_relations" value="0">
                            <small class="text-gray-500">Interpersonal relationships with colleagues</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Creativity</label>
                            <div class="star-rating" data-field="creativity">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="creativity" name="creativity" value="0">
                            <small class="text-gray-500">Innovation and creative problem-solving</small>
                        </div>
                    </div>

                    <!-- Attendance & Reliability Section -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700 bg-yellow-50 px-3 py-1 rounded">Attendance & Reliability</h5>
                        
                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Punctuality</label>
                            <div class="star-rating" data-field="punctuality">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="punctuality" name="punctuality" value="0">
                            <small class="text-gray-500">Timeliness in arriving and meeting deadlines</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Attendance</label>
                            <div class="star-rating" data-field="attendance">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="attendance" name="attendance" value="0">
                            <small class="text-gray-500">Regular presence and low absenteeism</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dependability</label>
                            <div class="star-rating" data-field="dependability">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="dependability" name="dependability" value="0">
                            <small class="text-gray-500">Reliability and trustworthiness</small>
                        </div>
                    </div>

                    <!-- Communication Section -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700 bg-purple-50 px-3 py-1 rounded">Communication Skills</h5>
                        
                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Written Communication</label>
                            <div class="star-rating" data-field="written_comm">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="writtenComm" name="written_comm" value="0">
                            <small class="text-gray-500">Clarity and effectiveness in written communication</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verbal Communication</label>
                            <div class="star-rating" data-field="verbal_comm">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="verbalComm" name="verbal_comm" value="0">
                            <small class="text-gray-500">Clarity and effectiveness in verbal communication</small>
                        </div>
                    </div>

                    <!-- Professional Appearance Section -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700 bg-red-50 px-3 py-1 rounded">Professional Appearance</h5>
                        
                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Appearance</label>
                            <div class="star-rating" data-field="appearance">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="appearance" name="appearance" value="0">
                            <small class="text-gray-500">Professional and appropriate appearance</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uniform</label>
                            <div class="star-rating" data-field="uniform">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="uniform" name="uniform" value="0">
                            <small class="text-gray-500">Proper wearing and maintenance of uniform</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Personal Hygiene</label>
                            <div class="star-rating" data-field="personal_hygiene">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="personalHygiene" name="personal_hygiene" value="0">
                            <small class="text-gray-500">Cleanliness and personal grooming standards</small>
                        </div>

                        <div class="evaluation-item">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tidiness</label>
                            <div class="star-rating" data-field="tidiness">
                                <span class="star" data-value="1">★</span>
                                <span class="star" data-value="2">★</span>
                                <span class="star" data-value="3">★</span>
                                <span class="star" data-value="4">★</span>
                                <span class="star" data-value="5">★</span>
                            </div>
                            <input type="hidden" id="tidiness" name="tidiness" value="0">
                            <small class="text-gray-500">Organization and cleanliness of workspace</small>
                        </div>
                    </div>
                </div>

                <!-- Overall Rating Display -->
                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Overall Rating:</span>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-blue-600" id="overallScore">0</span>
                            <span class="text-gray-500">/100</span>
                            <div class="text-sm text-gray-600" id="overallGrade">Not Rated</div>
                        </div>
                    </div>
                </div>

                <!-- Remarks Section -->
                <div class="mt-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Evaluation Remarks</label>
                    <textarea id="remarks" name="remarks" rows="4" class="w-full px-3 py-2 border rounded-md" placeholder="Enter evaluation remarks and feedback..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200" onclick="closeEvaluationModal()">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700" onclick="deleteEvaluation()" id="deleteBtn" style="display: none;">
                        Delete
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="saveEvaluation()">
                        Save Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rates Modal -->
<div id="userRatesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold" id="ratesModalTitle">User Rates</h3>
            <button onclick="closeRatesModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="userRatesForm">
                <input type="hidden" id="rateUserId" name="user_id">
                
                <div class="mb-4">
                    <label for="hourlyRate" class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate (₱)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                        <input type="number" id="hourlyRate" name="hourly_rate" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateRates('hourly')">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="dailyRate" class="block text-sm font-medium text-gray-700 mb-1">Daily Rate (₱)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                        <input type="number" id="dailyRate" name="daily_rate" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateRates('daily')">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="monthlyRate" class="block text-sm font-medium text-gray-700 mb-1">Monthly Rate (₱)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                        <input type="number" id="monthlyRate" name="monthly_rate" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateRates('monthly')">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200" onclick="closeRatesModal()">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="saveUserRates()">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Settings Modal -->
<div id="userSettingsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold" id="settingsModalTitle">User Settings</h3>
            <button onclick="closeSettingsModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="userSettingsForm">
                <input type="hidden" id="settingsUserId" name="user_id">
                
                <!-- Engagement Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engagement Status</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="engagement_status" value="full_time" class="form-radio text-blue-600">
                            <span class="ml-2">Full Time</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="engagement_status" value="part_time" class="form-radio text-blue-600">
                            <span class="ml-2">Part Time</span>
                        </label>
                    </div>
                </div>
                
                <!-- User Type -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="user_type" value="employee" class="form-radio text-blue-600" onchange="updateWageStatusOptions(this.value)">
                            <span class="ml-2">Employee</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="user_type" value="isp" class="form-radio text-blue-600" onchange="updateWageStatusOptions(this.value)">
                            <span class="ml-2">ISP</span>
                        </label>
                    </div>
                </div>
                
                <!-- User Status -->
                <div class="mb-4">
                    <label for="userStatus" class="block text-sm font-medium text-gray-700 mb-2">User Status</label>
                    <select id="userStatus" name="user_status" class="w-full px-3 py-2 border rounded-md">
                        <option value="active">Active</option>
                        <option value="awol">AWOL</option>
                        <option value="blacklisted">Blacklisted</option>
                        <option value="resigned">Resigned</option>
                        <option value="transferred">Transferred</option>
                        <option value="disengaged">Disengaged</option>
                        <option value="engaged">Engaged</option>
                    </select>
                </div>
                
                <!-- SIL Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">SIL Status</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="sil_status" value="1" class="form-radio text-blue-600">
                            <span class="ml-2">On</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="sil_status" value="0" class="form-radio text-blue-600">
                            <span class="ml-2">Off</span>
                        </label>
                    </div>
                </div>
                
                <!-- Wage Status - Now Dynamic -->
                <div class="mb-6">
                    <label id="wageStatusLabel" class="block text-sm font-medium text-gray-700 mb-2">Wage Status</label>
                    <div id="wageStatusContainer">
                        <!-- Default Employee Options -->
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="wage_type" value="mwe" class="form-radio text-blue-600">
                                <span class="ml-2">MWE (Minimum Wage Earner)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="wage_type" value="non_mwe" class="form-radio text-blue-600">
                                <span class="ml-2">Non-MWE</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200" onclick="closeSettingsModal()">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="saveUserSettings()">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Files Modal -->
<div id="userFilesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold" id="filesModalTitle">User Files</h3>
            <button onclick="closeFilesModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div id="filesLoading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading files...</p>
            </div>
            
            <div id="filesContent" class="hidden">
                <div id="filesList" class="space-y-4">
                    <!-- File links will be dynamically inserted here -->
                </div>
                
                <div id="noFilesMessage" class="hidden text-center py-4 text-gray-500">
                    No files found for this user.
                </div>
            </div>
            
            <div id="filesError" class="hidden text-center py-4 text-red-500">
                Error loading files. Please try again later.
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200" onclick="closeFilesModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Fringe Benefits Modal -->
<div id="fringeBenefitsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center border-b px-4 py-3">
            <h3 class="text-lg font-semibold" id="fringeModalTitle">Fringe Benefits</h3>
            <button onclick="closeFringeBenefitsModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-4 overflow-y-auto max-h-[75vh]">
            <form id="fringeBenefitsForm">
                <input type="hidden" id="fringeUserId" name="user_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Fringe Benefits Fields -->
                    <div class="mb-4">
                        <label for="hazardPay" class="block text-sm font-medium text-gray-700 mb-1">Hazard Pay (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="hazardPay" name="hazard_pay" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="fixedRepAllowance" class="block text-sm font-medium text-gray-700 mb-1">Fixed Representation Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="fixedRepAllowance" name="fixed_representation_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="fixedTransAllowance" class="block text-sm font-medium text-gray-700 mb-1">Fixed Transportation Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="fixedTransAllowance" name="fixed_transportation_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="fixedHousingAllowance" class="block text-sm font-medium text-gray-700 mb-1">Fixed Housing Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="fixedHousingAllowance" name="fixed_housing_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="vehicleAllowance" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="vehicleAllowance" name="vehicle_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="educationalAssistance" class="block text-sm font-medium text-gray-700 mb-1">Educational Assistance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="educationalAssistance" name="educational_assistance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="medicalAssistance" class="block text-sm font-medium text-gray-700 mb-1">Medical Assistance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="medicalAssistance" name="medical_assistance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="insurance" class="block text-sm font-medium text-gray-700 mb-1">Insurance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="insurance" name="insurance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="membership" class="block text-sm font-medium text-gray-700 mb-1">Membership (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="membership" name="membership" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="householdPersonnel" class="block text-sm font-medium text-gray-700 mb-1">Household Personnel (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="householdPersonnel" name="household_personnel" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="vacationExpense" class="block text-sm font-medium text-gray-700 mb-1">Vacation Expense (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="vacationExpense" name="vacation_expense" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="travelExpense" class="block text-sm font-medium text-gray-700 mb-1">Travel Expense (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="travelExpense" name="travel_expense" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="commissions" class="block text-sm font-medium text-gray-700 mb-1">Commissions (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="commissions" name="commissions" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="profitSharing" class="block text-sm font-medium text-gray-700 mb-1">Profit Sharing (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="profitSharing" name="profit_sharing" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="fees" class="block text-sm font-medium text-gray-700 mb-1">Fees (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="fees" name="fees" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="totalTaxable13" class="block text-sm font-medium text-gray-700 mb-1">Total Taxable 13th Month Pay (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="totalTaxable13" name="total_taxable_13" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="otherTaxable" class="block text-sm font-medium text-gray-700 mb-1">Other Taxable Benefits (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="otherTaxable" name="other_taxable" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalBenefits()">
                        </div>
                    </div>
                </div>
                
                <!-- Total Taxable Benefits (calculated field) -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <label class="text-sm font-medium text-gray-700">Total Taxable Benefits (₱):</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="totalTaxableBenefits" name="total_taxable_benefits" class="pl-8 pr-4 py-2 border rounded-md bg-gray-100" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200" onclick="closeFringeBenefitsModal()">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="saveFringeBenefits()">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- De Minimis Benefits Modal -->
<div id="deMinimisModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold" id="deminimisModalTitle">De Minimis Benefits</h3>
            <button onclick="closeDeMinimisModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="deminimisForm">
                <input type="hidden" id="deminimisUserId" name="user_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- De Minimis Benefits Fields -->
                    <div class="mb-4">
                        <label for="riceSubsidy" class="block text-sm font-medium text-gray-700 mb-1">Rice Subsidy (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="riceSubsidy" name="rice_subsidy" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="mealAllowance" class="block text-sm font-medium text-gray-700 mb-1">Meal Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="mealAllowance" name="meal_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="uniformClothing" class="block text-sm font-medium text-gray-700 mb-1">Uniform/Clothing (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="uniformClothing" name="uniform_clothing" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="laundryAllowance" class="block text-sm font-medium text-gray-700 mb-1">Laundry Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="laundryAllowance" name="laundry_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="medicalAllowance" class="block text-sm font-medium text-gray-700 mb-1">Medical Allowance (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="medicalAllowance" name="medical_allowance" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="collectiveBargaining" class="block text-sm font-medium text-gray-700 mb-1">Collective Bargaining Agreement (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="collectiveBargaining" name="collective_bargaining_agreement" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="totalNonTaxable13" class="block text-sm font-medium text-gray-700 mb-1">Total Non-Taxable 13th Month Pay (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="totalNonTaxable13" name="total_non_taxable_13" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="serviceIncentiveLeave" class="block text-sm font-medium text-gray-700 mb-1">Service Incentive Leave (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="serviceIncentiveLeave" name="service_incentive_leave" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="paidTimeOff" class="block text-sm font-medium text-gray-700 mb-1">Paid Time Off (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="paidTimeOff" name="paid_time_off" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="otherAllowances" class="block text-sm font-medium text-gray-700 mb-1">Other Allowances (₱)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="otherAllowances" name="other_allowances" class="w-full pl-8 pr-4 py-2 border rounded-md" step="0.01" min="0" onchange="calculateTotalDeMinimis()">
                        </div>
                    </div>
                </div>
                
                <!-- Total Non-Taxable Benefits (calculated field) -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <label class="text-sm font-medium text-gray-700">Total Non-Taxable Benefits (₱):</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₱</span>
                            <input type="number" id="totalNonTaxableBenefits" name="total_non_taxable_benefits" class="pl-8 pr-4 py-2 border rounded-md bg-gray-100" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200" onclick="closeDeMinimisModal()">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="saveDeMinimis()">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
