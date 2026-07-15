<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/DB/CONN.php';

$message = '';
$messageClass = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_answers') {
    $review_date = $_POST['review_date'] ?? '';
    $meds = $_POST['meds'] ?? 'No';
    $spare_room = $_POST['spare_room'] ?? 'No';
    $sick = $_POST['sick'] ?? 'No';
    $work_on_time = $_POST['work_on_time'] ?? 'No';
    $sent_home_early = $_POST['sent_home_early'] ?? 'No';
    $explanation = ($sent_home_early === 'Yes') ? ($_POST['explanation'] ?? '') : null;

    // Check for duplicate date
    $check_stmt = $conn->prepare("SELECT id FROM responses WHERE review_date = ?");
    $check_stmt->bind_param("s", $review_date);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $message = "An entry already exists for this calendar date. Please review the date due to duplication.";
        $messageClass = "alert-error";
    } else {
        // Safe insertion with prepared statements
        $stmt = $conn->prepare("INSERT INTO responses (review_date, meds, spare_room, sick, work_on_time, sent_home_early, explanation) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // HTML encoding data handling happens implicitly via strict types, but safely escaped for string usage
        $encoded_explanation = $explanation !== null ? htmlspecialchars($explanation, ENT_QUOTES, 'UTF-8') : null;
        $stmt->bind_param("sssssss", $review_date, $meds, $spare_room, $sick, $work_on_time, $sent_home_early, $encoded_explanation);
        
        if ($stmt->execute()) {
            $message = "Entry recorded successfully!";
            $messageClass = "alert-success";
        } else {
            $message = "Error saving entry.";
            $messageClass = "alert-error";
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// Handle AJAX operations for reviewing data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    $type = $_GET['fetch'];

    if ($type === 'single_date') {
        $date = $_GET['date'] ?? '';
        $stmt = $conn->prepare("SELECT *, TIME(entry_timestamp) as entry_time FROM responses WHERE review_date = ?");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        echo json_encode($result ?: ['empty' => true]);
        exit;
    }

    if ($type === 'stats') {
        $range_type = $_GET['range_type'] ?? 'all';
        $start_date = $_GET['start'] ?? null;
        $end_date = $_GET['end'] ?? null;
        $specific_q = $_GET['question'] ?? 'all';

        $query = "SELECT review_date, meds, spare_room, sick, work_on_time, sent_home_early FROM responses WHERE 1=1";
        
        if ($range_type === 'year') {
            $year = intval($_GET['year'] ?? date('Y'));
            $query .= " AND YEAR(review_date) = $year";
        } elseif ($range_type === 'custom' && $start_date && $end_date) {
            $query .= " AND review_date BETWEEN '" . $conn->real_escape_string($start_date) . "' AND '" . $conn->real_escape_string($end_date) . "'";
        }

        $result = $conn->query($query);
        $rows = [];
        while($row = $result->fetch_assoc()) { $rows[] = $row; }

        $questions = ['meds', 'spare_room', 'sick', 'work_on_time', 'sent_home_early'];
        if ($specific_q !== 'all') {
            $questions = [$specific_q];
        }

        $stats = [];
        // Calculate total weeks elapsed in scope or default to absolute days context / 7
        if (count($rows) > 0) {
            $dates = array_column($rows, 'review_date');
            $min_d = new DateTime(min($dates));
            $max_d = new DateTime(max($dates));
            $days = $max_d->diff($min_d)->days + 1;
            $weeks = $days / 7;
            if($weeks < 1) $weeks = 1; 
        } else {
            $weeks = 1;
        }

        foreach ($questions as $q) {
            $yes_count = 0;
            $no_count = 0;
            foreach ($rows as $r) {
                if ($r[$q] === 'Yes') $yes_count++;
                else $no_count++;
            }
            $stats[$q] = [
                'yes' => $yes_count,
                'no' => $no_count,
                'weekly_avg' => round($yes_count / $weeks, 2)
            ];
        }

        echo json_encode($stats);
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
            
            <div class="question-row">
                <h3>a) Lookup Answers by Specific Date</h3>
                <input type="date" id="lookup_date" onchange="fetchSingleDate()">
                <div id="single-date-result" style="margin-top: 15px;"></div>
            </div>

            <div class="question-row">
                <h3>b & c) Metrics and Aggregations</h3>
                
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
                        <label>Target Target Question</label>
                        <select id="target_question">
                            <option value="all">All Questions Combined (Option C)</option>
                            <option value="meds">Meds Taken</option>
                            <option value="spare_room">Slept in Spare Room</option>
                            <option value="sick">Was Sick</option>
                            <option value="work_on_time">Work on Time</option>
                            <option value="sent_home_early">Sent Home Early</option>
                        </select>
                    </div>
                </div>

                <button type="button" onclick="fetchMetrics()">Generate Report</button>

                <table id="analytics-table" class="hidden">
                    <thead>
                        <tr>
                            <th>Metric Question</th>
                            <th>Yes Count</th>
                            <th>No Count</th>
                            <th>Weekly Average (Yes)</th>
                        </tr>
                    </thead>
                    <tbody id="analytics-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>
