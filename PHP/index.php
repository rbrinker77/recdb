<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/DB/daily_connect.php';

$message = '';
$messageClass = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // ACTION 1: Submit Daily Log
    if ($_POST['action'] === 'submit_answers') {
        $review_date = $_POST['review_date'] ?? '';
        $meds = $_POST['meds'] ?? 'No';
        $spare_room = $_POST['spare_room'] ?? 'No';
        $sick = $_POST['sick'] ?? 'No';
        $work_on_time = $_POST['work_on_time'] ?? 'No';
        $sent_home_early = $_POST['sent_home_early'] ?? 'No';
        $explanation = ($sent_home_early === 'Yes') ? ($_POST['explanation'] ?? '') : null;

        // 1. Check for duplicate date using PDO
        $check_stmt = $conn->prepare("SELECT id FROM responses WHERE review_date = ?");
        $check_stmt->execute([$review_date]);
        $row = $check_stmt->fetch();

        if ($row) {
            $message = "An entry already exists for this calendar date. Please review the date due to duplication.";
            $messageClass = "alert-error";
        } else {
            // 2. Safe insertion using PDO prepared statements
            $stmt = $conn->prepare("INSERT INTO responses (review_date, meds, spare_room, sick, work_on_time, sent_home_early, explanation) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $review_date, 
                $meds, 
                $spare_room, 
                $sick, 
                $work_on_time, 
                $sent_home_early, 
                $explanation
            ]);
            
            $message = "Log entry saved successfully!";
            $messageClass = "alert-success";
        }
    }

    // ACTION 2: Edit Existing Log (Date is kept static, only update values)
    if ($_POST['action'] === 'edit_answers') {
        $review_date = $_POST['edit_review_date'] ?? '';
        $meds = $_POST['edit_meds'] ?? 'No';
        $spare_room = $_POST['edit_spare_room'] ?? 'No';
        $sick = $_POST['edit_sick'] ?? 'No';
        $work_on_time = $_POST['edit_work_on_time'] ?? 'No';
        $sent_home_early = $_POST['edit_sent_home_early'] ?? 'No';
        $explanation = ($sent_home_early === 'Yes') ? ($_POST['edit_explanation'] ?? '') : null;

        if (!empty($review_date)) {
            $stmt = $conn->prepare("UPDATE responses SET meds = ?, spare_room = ?, sick = ?, work_on_time = ?, sent_home_early = ?, explanation = ? WHERE review_date = ?");
            $stmt->execute([
                $meds,
                $spare_room,
                $sick,
                $work_on_time,
                $sent_home_early,
                $explanation,
                $review_date
            ]);
            $message = "Log entry for " . htmlspecialchars($review_date) . " updated successfully!";
            $messageClass = "alert-success";
        } else {
            $message = "Unable to update: Date was missing.";
            $messageClass = "alert-error";
        }
    }
}

// Handle AJAX operations for reviewing data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    $type = $_GET['fetch'];

    if ($type === 'single_date') {
        $date = $_GET['date'] ?? '';
        $stmt = $conn->prepare("SELECT *, DATE_FORMAT(entry_timestamp, '%Y-%m-%d %H:%i:%s') as entry_timestamp_formatted FROM responses WHERE review_date = ?");
        $stmt->execute([$date]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $result['entry_timestamp'] = $result['entry_timestamp_formatted'] ?? $result['review_date'];
            $result['entry_time'] = $result['entry_timestamp_formatted'] ? date('H:i:s', strtotime($result['entry_timestamp_formatted'])) : 'Logged';
            echo json_encode($result);
        } else {
            echo json_encode(['empty' => true]);
        }
        exit;
    }

    if ($type === 'stats') {
        $range_type = $_GET['range_type'] ?? 'all';
        $start_date = $_GET['start'] ?? null;
        $end_date = $_GET['end'] ?? null;
        $specific_q = $_GET['question'] ?? 'all';

        $query = "SELECT review_date, meds, spare_room, sick, work_on_time, sent_home_early FROM responses WHERE 1=1";
        $params = [];
        
        if ($range_type === 'year') {
            $year = intval($_GET['year'] ?? date('Y'));
            $query .= " AND YEAR(review_date) = ?";
            $params[] = $year;
        } elseif ($range_type === 'custom' && $start_date && $end_date) {
            $query .= " AND review_date BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date;
        }

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questions = ['meds', 'spare_room', 'sick', 'work_on_time', 'sent_home_early'];
        if ($specific_q !== 'all') {
            $questions = [$specific_q];
        }

        $stats = [];
        $days = 0;
        if (count($rows) > 0) {
            $dates = array_column($rows, 'review_date');
            $min_d = new DateTime(min($dates));
            $max_d = new DateTime(max($dates));
            $days = $max_d->diff($min_d)->days + 1;
            $weeks = $days / 7;
            if ($weeks < 1) $weeks = 1; 
        } else {
            $weeks = 1;
        }

        foreach ($questions as $q) {
            $yes_count = 0;
            $no_count = 0;
            foreach ($rows as $r) {
                if ($r[$q] === 'Yes') {
                    $yes_count++;
                } else {
                    $no_count++;
                }
            }
            
            // Calculate overall percentage of yes responses, rounded up to the nearest whole number
            $total_responses = $yes_count + $no_count;
            $yes_percent = ($total_responses > 0) ? ceil(($yes_count / $total_responses) * 100) : 0;

            $stats[$q] = [
                'yes' => $yes_count,
                'no' => $no_count,
                'weekly_avg' => round($yes_count / $weeks, 2),
                'yes_percent' => $yes_percent
            ];
        }

        echo json_encode([
            'stats' => $stats,
            'total_days' => $days
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Tracker</title>
    <link rel="stylesheet" href="/CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Daily Question System</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $messageClass; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="nav-buttons">
            <button onclick="switchView('answer')">Answer Questions</button>
            <button onclick="switchView('review')">Review Responses</button>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 25px;">

        <div id="answer-view" class="view-section">
            <h2>Submit Daily Log</h2>
            <form action="" method="POST">
                <input type="hidden" name="action" value="submit_answers">
                
                <div class="form-group">
                    <label for="review_date">Review Date:</label>
                    <input type="date" id="review_date" name="review_date" required>
                </div>

                <div class="question-row">
                    <p>1. Did she take meds on this date?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="meds" value="Yes"> Yes</label>
                        <label><input type="radio" name="meds" value="No" checked> No</label>
                    </div>
                </div>

                <div class="question-row">
                    <p>2. Did she sleep in the spare room overnight?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="spare_room" value="Yes"> Yes</label>
                        <label><input type="radio" name="spare_room" value="No" checked> No</label>
                    </div>
                </div>

                <div class="question-row">
                    <p>3. Was she sick?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="sick" value="Yes"> Yes</label>
                        <label><input type="radio" name="sick" value="No" checked> No</label>
                    </div>
                </div>

                <div class="question-row">
                    <p>4. Did she go to work on time?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="work_on_time" value="Yes"> Yes</label>
                        <label><input type="radio" name="work_on_time" value="No" checked> No</label>
                    </div>
                </div>

                <div class="question-row">
                    <p>5. Did she get sent home early from work?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="sent_home_early" value="Yes" onchange="toggleExplanation(true)"> Yes</label>
                        <label><input type="radio" name="sent_home_early" value="No" checked onchange="toggleExplanation(false)"> No</label>
                    </div>
                    <div id="explanation-box" class="form-group hidden" style="margin-top:15px;">
                        <label for="explanation">Provide Explanation (Max 500 characters):</label>
                        <textarea id="explanation" name="explanation" maxlength="500" rows="3"></textarea>
                    </div>
                </div>

                <button type="submit">Submit Data</button>
            </form>
        </div>

        <div id="review-view" class="view-section hidden">
            <h2>Review Interface</h2>
            
            <div class="sub-nav-buttons" style="margin-bottom: 20px;">
                <button type="button" onclick="switchReviewSubView('lookup')">Date Lookup</button>
                <button type="button" onclick="switchReviewSubView('metrics')">Metrics & Aggregations</button>
            </div>
        
            <div id="sub-view-lookup" class="question-row">
                <h3>Lookup Answers by Date</h3>
                <div class="flex-inputs" style="margin-bottom: 15px;">
                    <div>
                        <label for="lookup_date">Select Date</label>
                        <input type="date" id="lookup_date">
                    </div>
                </div>
                <button type="button" onclick="fetchSingleDate()">Fetch Log</button>
                <div id="single-date-result" style="margin-top: 15px;"></div>

                <!-- EDIT SECTION (Dynamically pre-populated and shown upon lookup) -->
                <div id="edit-entry-container" class="hidden" style="margin-top: 25px; padding-top: 20px; border-top: 1px dashed var(--border-color);">
                    <h3>Edit Log for <span id="edit_review_date_display"></span></h3>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="edit_answers">
                        <input type="hidden" id="edit_review_date" name="edit_review_date">

                        <div class="question-row">
                            <p>1. Did she take meds on this date?</p>
                            <div class="radio-group">
                                <label><input type="radio" id="edit_meds_yes" name="edit_meds" value="Yes"> Yes</label>
                                <label><input type="radio" id="edit_meds_no" name="edit_meds" value="No"> No</label>
                            </div>
                        </div>

                        <div class="question-row">
                            <p>2. Did she sleep in the spare room overnight?</p>
                            <div class="radio-group">
                                <label><input type="radio" id="edit_spare_room_yes" name="edit_spare_room" value="Yes"> Yes</label>
                                <label><input type="radio" id="edit_spare_room_no" name="edit_spare_room" value="No"> No</label>
                            </div>
                        </div>

                        <div class="question-row">
                            <p>3. Was she sick?</p>
                            <div class="radio-group">
                                <label><input type="radio" id="edit_sick_yes" name="edit_sick" value="Yes"> Yes</label>
                                <label><input type="radio" id="edit_sick_no" name="edit_sick" value="No"> No</label>
                            </div>
                        </div>

                        <div class="question-row">
                            <p>4. Did she go to work on time?</p>
                            <div class="radio-group">
                                <label><input type="radio" id="edit_work_on_time_yes" name="edit_work_on_time" value="Yes"> Yes</label>
                                <label><input type="radio" id="edit_work_on_time_no" name="edit_work_on_time" value="No"> No</label>
                            </div>
                        </div>

                        <div class="question-row">
                            <p>5. Did she get sent home early from work?</p>
                            <div class="radio-group">
                                <label><input type="radio" id="edit_sent_home_early_yes" name="edit_sent_home_early" value="Yes" onchange="toggleEditExplanation(true)"> Yes</label>
                                <label><input type="radio" id="edit_sent_home_early_no" name="edit_sent_home_early" value="No" onchange="toggleEditExplanation(false)"> No</label>
                            </div>
                            <div id="edit-explanation-box" class="form-group hidden" style="margin-top:15px;">
                                <label for="edit_explanation">Provide Explanation (Max 500 characters):</label>
                                <textarea id="edit_explanation" name="edit_explanation" maxlength="500" rows="3"></textarea>
                            </div>
                        </div>

                        <button type="submit" style="background-color: #28a745;">Save Edits</button>
                    </form>
                </div>
            </div>
        
            <div id="sub-view-metrics" class="question-row hidden">
                <h3>Metrics and Aggregations</h3>
                
                <div class="flex-inputs" style="margin-bottom: 15px;">
                    <div>
                        <label>Scope / Time Frame</label>
                        <select id="time_frame" onchange="handleTimeframeChange()">
                            <option value="all">All Time</option>
                            <option value="year">Specific Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    
                    <div id="year-select-container" class="hidden">
                        <label>Year</label>
                        <input type="number" id="target_year" value="<?php echo date('Y'); ?>" min="2000" max="2100">
                    </div>
        
                    <div id="custom-range-container" class="hidden flex-inputs" style="padding:0; gap:10px;">
                        <div>
                            <label>Start</label>
                            <input type="date" id="start_date">
                        </div>
                        <div>
                            <label>End</label>
                            <input type="date" id="end_date">
                        </div>
                    </div>
        
                    <div>
                        <label>Target Question</label>
                        <select id="target_question">
                            <option value="all">All Questions</option>
                            <option value="meds">Meds Taken</option>
                            <option value="spare_room">Slept in Spare Room</option>
                            <option value="sick">Was Sick</option>
                            <option value="work_on_time">Work on Time</option>
                            <option value="sent_home_early">Sent Home Early</option>
                        </select>
                    </div>
                </div>
        
                <button type="button" onclick="fetchMetrics()">Generate Report</button>
        
                <!-- Summary Display Area outside the table -->
                <div id="metric-summary" class="hidden" style="margin: 20px 0 15px 0;"></div>
        
                <table id="analytics-table" class="hidden">
                    <thead>
                        <tr>
                            <th>Metric Question</th>
                            <th>Yes Count</th>
                            <th>No Count</th>
                            <th>Weekly Average (Yes)</th>
                            <th>Yes % (Rounded Up)</th>
                        </tr>
                    </thead>
                    <tbody id="analytics-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/JS/app.js"></script>
</body>
</html>
