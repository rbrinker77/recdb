// Initialize default properties on window frame generation
document.addEventListener("DOMContentLoaded", () => {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('review_date').value = today;
    document.getElementById('lookup_date').value = today;
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

// View Logic A - Process Singular date pull requests
function fetchSingleDate() {
    const dateVal = document.getElementById('lookup_date').value;
    if (!dateVal) return;

    fetch(`index.php?fetch=single_date&date=${dateVal}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('single-date-result');
            if (data.empty) {
                container.innerHTML = `<p style="color: var(--error-color)">No logs parsed on database for ${dateVal}</p>`;
                return;
            }
            
            container.innerHTML = `
                <div style="background:#121212; padding: 15px; border-radius:4px; border: 1px dashed var(--border-color)">
                    <p><strong>Logged Timestamp:</strong> ${data.entry_timestamp} (Time: ${data.entry_time})</p>
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
        });
}

// Adjust view criteria parameters depending on selection items
function handleTimeframeChange() {
    const tf = document.getElementById('time_frame').value;
    document.getElementById('year-select-container').classList.toggle('hidden', tf !== 'year');
    document.getElementById('custom-range-container').classList.toggle('hidden', tf !== 'custom');
}

// View Logic B & C - Dynamic Metric Array Parsing Engines
function fetchMetrics() {
    const tf = document.getElementById('time_frame').value;
    const question = document.getElementById('target_question').value;
    let url = `index.php?fetch=stats&range_type=${tf}&question=${question}`;

    if (tf === 'year') {
        url += `&year=${document.getElementById('target_year').value}`;
    } else if (tf === 'custom') {
        url += `&start=${document.getElementById('start_date').value}&end=${document.getElementById('end_date').value}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('analytics-tbody');
            tbody.innerHTML = '';
            
            const mappingNames = {
                meds: "Did she take meds on this date?",
                spare_room: "Did she sleep in the spare room overnight?",
                sick: "Was she sick?",
                work_on_time: "Did she go to work on time?",
                sent_home_early: "Did she get sent home early from work?"
            };

            const keys = Object.keys(data);
            if(keys.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;">No entries matched tracking parameters</td></tr>`;
            } else {
                keys.forEach(key => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><strong>${mappingNames[key] || key}</strong></td>
                        <td>${data[key].yes}</td>
                        <td>${data[key].no}</td>
                        <td>${data[key].weekly_avg}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
            document.getElementById('analytics-table').classList.remove('hidden');
        });
}
