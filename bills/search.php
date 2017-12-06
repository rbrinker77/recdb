<?php
//***************************
//***Coded by Ryan Brinker***
//***************************
?>
<?php
	require_once('./calendar/tc_calendar.php');
?>
<head>
	<link rel="stylesheet" href="../CSS/bills.css" type="text/css">
	<link href="./calendar/calendar.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="./calendar/calendar.js"></script>
	<script type="text/javascript" src="../JS/Event.js"></script>
	<script type="text/javascript" src="../JS/SortedTable.js"></script>
	<script type="text/javascript">onload = function() {var myTable = new SortedTable();}</script>
</head>

<?php
//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

$mobile = $_GET["mobile"];
$brink = @$_GET["brink"];
$selectPayee = "";
$currDate = date("Y-m-d");
$pastDate = strtotime ( "-7 day" , strtotime ( $currDate ) ) ;
$pastDate = date ( "Y-m-d" , $pastDate );
$dueDateStart_default = $pastDate;
$dueDateEnd_default = $currDate;

if (isset($_POST['searchBills']))
{
	$selectPayee = $_POST['payeeSearch'];
	$dueDateStart = $_POST['dueDateStart'];
	$dueDateEnd = $_POST['dueDateEnd'];
}
else
{
	$dueDateStart = $dueDateStart_default;
	$dueDateEnd = $dueDateEnd_default;
}

function createPayeeList ($selectPayee,$firstList)
{
	include("../DB/dbconnect.php");

	$payeesQuery = "SELECT * \n"
		."FROM theboxli_Payees \n"
		."ORDER BY payeeName";

	foreach($dbConnection->query($payeesQuery) as $dropdown)
	{
		$payeeNum = $dropdown['payeeNum'];
		$payeeName = $dropdown['payeeName'];

		if ($payeeNum == $selectPayee)
		{
			$selected = "selected";
		}
		else
		{
			$selected = "";
		}

		if ($firstList == "yes")
		{
			echo "<option value=\"\" >All</option>";
			$firstList = "no";
		}

		echo "<option value=\"".$payeeNum."\" ".$selected." >".$payeeName."</option>";
	}

	$dbConnection = null;
}

echo "<html><body>";


echo "<table align=\"center\" border=\"2px\" >
	<tr>
		<td class=\"label\">Payee Name</td>
		<td class=\"label\">Date Due</td>
	</tr>
	<form name=\"searchBills\" action=\"./search.php?mobile=".$mobile."&brink=".$brink."\" method=\"post\">
		<tr>";

echo "		<td>
				<select name=\"payeeSearch\" id=\"payeeSearch\" >";
					createPayeeList($selectPayee,"yes");
echo "			</select>
			</td>";

echo "		<td>";
   				$dateDueCalendar = new tc_calendar("dueDateStart", true, false);
   				$dateDueCalendar->setIcon("./calendar/images/iconCalendar.gif");
   				$dateDueCalendar->setDate(date('d', strtotime($dueDateStart))
					, date('m', strtotime($dueDateStart))
   					, date('Y', strtotime($dueDateStart)));
   				$dateDueCalendar->setPath("./calendar/");
   				$dateDueCalendar->setYearInterval(2012, 2050);
   				$dateDueCalendar->setAlignment('left', 'bottom');
   				$dateDueCalendar->setDatePair('dueDateStart', 'dueDateEnd', $dueDateEnd);
   				$dateDueCalendar->writeScript();

   				$dateDueCalendar = new tc_calendar("dueDateEnd", true, false);
   				$dateDueCalendar->setIcon("./calendar/images/iconCalendar.gif");
   				$dateDueCalendar->setDate(date('d', strtotime($dueDateEnd))
   					, date('m', strtotime($dueDateEnd))
   					, date('Y', strtotime($dueDateEnd)));
   				$dateDueCalendar->setPath("./calendar/");
   				$dateDueCalendar->setYearInterval(2012, 2050);
   				$dateDueCalendar->setAlignment('left', 'bottom');
   				$dateDueCalendar->setDatePair('dueDateStart', 'dueDateEnd', $dueDateStart);
   				$dateDueCalendar->writeScript();
echo "
			</td>";

echo"		<td><input name=\"searchBills\" type=\"submit\" class=\"searchButton\" value=\"Search\" ><input type=\"button\" class=\"backButton\" value=\"Reset\" onclick=\"window.location.href='./search.php?mobile=".$mobile."&brink=".$brink."'\" ><input name=\"back\" type=\"button\" class=\"homeButton\" value=\"Back\"  onclick=\"window.location.href='./billsPage.php?mobile=".$mobile."&brink=".$brink."'\"></td>
	</tr>
	</form>
</table>";

if (isset($_POST['searchBills']))
{
	include("../DB/dbconnect.php");

	$searchQuery = "SELECT * \n"
		."FROM theboxli_Bills, theboxli_Payees \n"
		."WHERE theboxli_Bills.payeeNum = theboxli_Payees.payeeNum ";

	if ($selectPayee <> "")
	{
		$searchQuery .= "AND theboxli_Bills.payeeNum = ".$selectPayee." ";
	}

	if ($dueDateEnd <> $dueDateEnd_default)
	{
		$searchQuery .= "AND (theboxli_Bills.dateDue >= '".$dueDateStart."' AND theboxli_Bills.dateDue <= '".$dueDateEnd."') ";
	}

	$searchQuery .= "ORDER BY theboxli_Payees.payeeName, theboxli_Bills.amount";

	$i = 0;

	foreach($dbConnection->query($searchQuery) as $row)
	{
		if ($i == 0)
		{
			echo "<table class=\"sorted\" align=\"center\" >
			<thead>
				<tr>
					<th id=\"payee\" width=\"15%\">Payee</th>
					<th id=\"amount\" width=\"15%\">Amount</th>
					<th id=\"desc\" width=\"25%\">Description</th>
					<th id=\"adddate\" width=\"15%\">Date Added</th>
					<th id=\"duedate\" width=\"15%\">Date Due</th>
				</tr>
			</thead>
			<tbody>";

			$i = 2;
		}

		if ($row['amount'] > 0)
		{
			$amountClass = "negAmount";
		}
		else
		{
			$amountClass = "posAmount";
		}
		echo "<tr class=\"field\">
			<td headers=\"payee\" axis=\"sstring\">".$row['payeeName']."</td>
			<td headers=\"amount\" class=\"".$amountClass."\" axis=\"number\" >$".number_format(abs($row['amount']),2)."</td>
			<td headers=\"desc\" axis=\"sstring\">".$row['description']."</td>
			<td headers=\"adddate\" axis=\"date\">".$row['dateAdded']."</td>
			<td headers=\"duedate\" axis=\"date\">".$row['dateDue']."</td>
		</tr>";
	}

	$dbConnection = null;

	echo "</tbody>
		</table>";
}

echo "</body>
</html>";

?>
