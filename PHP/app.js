// Initialize default properties on window frame generation
document.addEventListener("DOMContentLoaded", () => {
    const today = new Date().toISOString().split('T')[0];
    if (document.getElementById('review_date')) {
        document.getElementById('review_date').value = today;
    }
    if (document.getElementById('lookup_date')) {
        document.getElementById('lookup_date').value = today;
    }
});

// Primary UI Route switcher
function switchView(view) {
    if (view === 'answer') {
        document.getElementById('answer-view').classList.remove('hidden');
        document.getElementById('review-view').classList.add('hidden');
    } else {
        document.getElementById('answer-view').classList.add('hidden');
        document.getElementById('review-view').classList.remove('hidden');
    }
}

// Sub-UI Route switcher for Review View modes (Date Lookup vs Metrics)
function switchReviewSubView(subView) {
    if (subView === 'lookup') {
        document.getElementById('sub-view-lookup').classList.remove('hidden');
        document.getElementById('sub-view-metrics').classList.add('hidden');
    } else {
        document.getElementById('sub-view-lookup').classList.add('hidden');
        document.getElementById('sub-view-metrics').classList.remove('hidden');
    }
}

// Sub-conditional dynamic field autofocus mapping
function toggleExplanation(show) {
    const box = document.getElementById('explanation-box');
    const textarea = document.getElementById('explanation');
    if (show) {
        box.classList.remove('hidden');
        textarea.focus();
    } else {
        box.classList.add('hidden');
        textarea.value = '';
    }
}

// Sub-conditional dynamic field autofocus mapping for Edit Section
function toggleEditExplanation(show) {
    const box = document.getElementById('edit-explanation-box');
    const textarea = document.getElementById('edit_explanation');
    if (show) {
        box.classList.remove('hidden');
        textarea.focus();
    } else {
        box.classList.add('hidden');
        textarea.value = '';
    }
}

// View Logic A - Process Singular date pull requests (Lookup & Edit)
function fetchSingleDate() {
    const dateVal = document.getElementById('lookup_date').value;
    if (!dateVal) {
        alert("Please select a date first.");
        return;
    }

    fetch(`index.php?fetch=single_date&date=${dateVal}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('single-date-result');
            const editContainer = document.getElementById('edit-entry-container');
            
            if (data.empty) {
                container.innerHTML = `<p style="color: var(--error-color)">No logs parsed on database for ${dateVal}</p>`;
                editContainer.classList.add('hidden');
                return;
            }
            
            // Render View Details
            container.innerHTML = `
                <div style="background:#121212; padding: 15px; border-radius:4px; border: 1px dashed var(--border-color)">
                    <p><strong>Logged Timestamp:</strong> ${data.entry_timestamp || data.review_date} (Time: ${data.entry_time || 'Logged'})</p>
                    <ul>
                        <li>Took Meds: ${data.meds}</li>
                        <li>Slept in Spare Room: ${data.spare_room}</li>
                        <li>Was Sick: ${data.sick}</li>
                        <li>Arrived at Work on Time: ${data.work_on_time}</li>
                        <li>Sent Home Early: ${data.sent_home_early}</li>
                    </ul>
                    ${data.explanation ? `<p style="margin-top:10px;"><strong>Explanation:</strong> ${data.explanation}</p>` : ''}
                </div>
            `;

            // Prepare and Populate Edit Form (keeping the review date static)
            document.getElementById('edit_review_date').value = data.review_date;
            document.getElementById('edit_review_date_display').innerText = data.review_date;
            
            // Set radio values
            setRadioValue('edit_meds', data.meds);
            setRadioValue('edit_spare_room', data.spare_room);
            setRadioValue('edit_sick', data.sick);
            setRadioValue('edit_work_on_time', data.work_on_time);
            setRadioValue('edit_sent_home_early', data.sent_home_early);

            // Set explanation field
            const explanationBox = document.getElementById('edit-explanation-box');
            const explanationText = document.getElementById('edit_explanation');
            if (data.sent_home_early === 'Yes') {
                explanationBox.classList.remove('hidden');
                explanationText.value = data.explanation || '';
            } else {
                explanationBox.classList.add('hidden');
                explanationText.value = '';
            }

            editContainer.classList.remove('hidden');
        });
}

// Helper to check the correct radio button value
function setRadioValue(groupName, value) {
    const radios = document.getElementsByName(groupName);
    for (let i = 0; i < radios.length; i++) {
        if (radios[i].value === value) {
            radios[i].checked = true;
            break;
        }
    }
}

// Adjust view criteria parameters depending on selection items
function handleTimeframeChange() {
    const tf = document.getElementById('time_frame').value;
    document.getElementById('year-select-container').classList.toggle('hidden', tf !== 'year');
    document.getElementById('custom-range-container').classList.toggle('hidden', tf !== 'custom');
}

// View Logic B & C - Dynamic Metric Array Parsing Engines (Metrics only)
function fetchMetrics() {
    const tf = document.getElementById('time_frame').value;
    const question = document.getElementById('target_question').value;
    
    let url = `index.php?fetch=stats&action=fetch_data&range_type=${tf}&question=${question}`;

    if (tf === 'year') {
        url += `&year=${document.getElementById('target_year').value}`;
    } else if (tf === 'custom') {
        url += `&start=${document.getElementById('start_date').value}&end=${document.getElementById('end_date').value}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(response => {
            const data = response.stats; // Access the stats object
            const totalDays = response.total_days;
            const tbody = document.getElementById('analytics-tbody');
            const summaryDiv = document.getElementById('metric-summary');
            
            tbody.innerHTML = '';
            
            // Display Days Included outside the table if > 1
            if (totalDays > 1) {
                summaryDiv.innerHTML = `Days Included in Report: ${totalDays}`;
                summaryDiv.classList.remove('hidden');
            } else {
                summaryDiv.innerHTML = '';
                summaryDiv.classList.add('hidden');
            }
            
            const mappingNames = {
                meds: "Did she take meds on this date?",
                spare_room: "Did she sleep in the spare room overnight?",
                sick: "Was she sick?",
                work_on_time: "Did she go to work on time?",
                sent_home_early: "Did she get sent home early from work?"
            };

            const keys = Object.keys(data);
            if(keys.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">No entries matched tracking parameters</td></tr>`;
            } else {
                keys.forEach(key => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><strong>${mappingNames[key] || key}</strong></td>
                        <td>${data[key].yes}</td>
                        <td>${data[key].no}</td>
                        <td>${data[key].weekly_avg}</td>
                        <td>${data[key].yes_percent}%</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
            document.getElementById('analytics-table').classList.remove('hidden');
        })
        .catch(err => {
            console.error("Metric processing error: ", err);
        });
}
