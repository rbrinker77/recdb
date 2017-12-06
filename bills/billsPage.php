<?php
//***************************
//***Coded by Ryan Brinker***
//***************************
?>

<head>
	<link rel="stylesheet" href="../CSS/bills.css" type="text/css">
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

if (isset($_POST['addRec']))
{
	addRec();
}

if (isset($_POST['isUpdate']))
{
	$billNum = $_GET["billNum"];
	updateRec($billNum);
}

if (isset($_POST['isDelete']))
{
	$billNum = $_GET["billNum"];
	deleteRec($billNum);
}

if (isset($_POST['isPaid']))
{
	$billNum = $_GET["billNum"];
	paidRec($billNum);
}

function createPayeeList ($payee)
{
	include("../DB/dbconnect.php");

	$payeesQuery = "SELECT * \n"
		."FROM theboxli_Payees \n"
		."ORDER BY payeeName";

	foreach($dbConnection->query($payeesQuery) as $dropdown)
	{
		$payeeNum = $dropdown['payeeNum'];
		$payeeName = $dropdown['payeeName'];

		if ($payeeName == $payee)
		{
			$selected = "selected";
		}
		else
		{
			$selected = "";
		}
		$dbConnection = null;

		echo "<option value=\"".$payeeNum."\" ".$selected." >".$payeeName."</option>";
	}

}

function updateRec($billNum)
{
	include("../DB/dbconnect.php");

	$chars = array("-", " ", "/");
	$dateDue = str_replace($chars, "", $_POST["dateDue".$billNum]);
	$formedDate = substr($dateDue,4,4).substr($dateDue,0,4);
	$payeeNumU = $_POST["payee".$billNum];
	$amountU = $_POST["amount".$billNum];
	$descU = $_POST["description".$billNum];
	$dateDueU = date('Ymd', strtotime($formedDate));
	$updateQuery="UPDATE theboxli_Bills SET payeeNum = ".$payeeNumU.", amount = ".$amountU.", description = '".$descU."', dateDue = ".$dateDueU." WHERE billNum = ".$billNum;

	$updateBill = $dbConnection->prepare($updateQuery);
	$updateBill->execute();

	$dbConnection = null;
}

function paidRec($billNum)
{
	include("../DB/dbconnect.php");

	$paidQuery="UPDATE theboxli_Bills SET isPaid = 1, datePaid = CURDATE() WHERE billNum = ".$billNum;

	$setPaid = $dbConnection->prepare($paidQuery);
	$setPaid->execute();

	$dbConnection = null;
}

function addRec()
{
	include("../DB/dbconnect.php");

	$chars = array("-", " ", "/");
	$dateDue = str_replace($chars, "", $_POST["dateDue"]);
	$formedDate = substr($dateDue,4,4).substr($dateDue,0,4);
	$payeeNumA = $_POST["payee"];
	$amountA = $_POST["amount"];
	$descA = $_POST["description"];
	$dateDueA = date('Ymd', strtotime($formedDate));
	$dateAdded = date("Ymd");
	$addQuery="INSERT INTO theboxli_Bills \n"
		."(payeeNum, amount, description, dateDue, dateAdded) \n"
		."VALUES (".$payeeNumA.", ".$amountA.", '".$descA."', '".$dateDueA."', '".$dateAdded."')";

	$addBill = $dbConnection->prepare($addQuery);
	$addBill->execute();

	$dbConnection = null;
}

function deleteRec($billNum)
{
	include("../DB/dbconnect.php");

	$deleteQuery="DELETE FROM theboxli_Bills WHERE billNum = ".$billNum;

	$deleteBill = $dbConnection->prepare($deleteQuery);
	$deleteBill->execute();

	$dbConnection = null;
}

echo "<html>
<body>";

include("../DB/dbconnect.php");

$owedQuery = "SELECT SUM(amount) FROM theboxli_Bills WHERE amount > 0";

foreach($dbConnection->query($owedQuery) as $row)
{
	$owed = $row[0];
}

$creditQuery = "SELECT SUM(amount) FROM theboxli_Bills WHERE amount < 0";

foreach($dbConnection->query($creditQuery) as $row)
{
	$credit = $row[0];
}

$dbConnection = null;

$total = $owed + $credit;

echo "<table align=\"center\">";

if ($total > 0)
{
	echo "
	<tr align='center' class=\"negAmount\">
		<td colspan=\"5\" >Total Owed: $".number_format(abs($total),2)."</td>";
}
else
{
	echo "<tr align='center' class=\"posAmount\">
		<td colspan=\"5\" >Total Credit: $".number_format(abs($total),2)."</td>";
}

echo "<tr>
		<td colspan=\"5\" align=\"right\" ><br />
			<input type=\"button\" class=\"searchButton\" value=\"Search\" onclick=\"window.location.href='./search.php?mobile=".$mobile."&brink=".$brink."'\">
		</td>
	</tr>
</table>";

echo "<table align=\"center\">
		<tr>
			<td colspan='5' class=\"label\" align='center'>Last 30 Days</td>
		</tr>
		<tr><td> </td></tr>
		<tr>
			<td colspan='5' class=\"label\" >Debts</td>
		</tr>
	</table>
	<table class=\"sorted\">
		<thead>
			<tr>
				<td width=\"10%\"> </td>
				<th id=\"payee\" width=\"15%\" >Payee</th>
				<th id=\"amount\" width=\"15%\" >Amount</th>
				<th id=\"desc\" width=\"45%\" >Description</th>
				<th id=\"duedate\" width=\"15%\" >Date Due</th>
			</tr>
		</thead>
		<tbody>";

include("../DB/dbconnect.php");

$billsQuery = "SELECT * \n"
	."FROM theboxli_Bills, theboxli_Payees \n"
	."WHERE theboxli_Bills.amount > 0 \n"
	."AND theboxli_Bills.payeeNum = theboxli_Payees.payeeNum \n"
	."AND theboxli_Bills.dateDue BETWEEN DATE_SUB(CURDATE(),INTERVAL 31 DAY) and DATE_ADD(CURDATE(),INTERVAL 2 YEAR) \n"
	."ORDER BY theboxli_Bills.dateDue";

foreach($dbConnection->query($billsQuery) as $row)
{
	$billNum = $row['billNum'];
	$payee = $row['payeeName'];
	$amount = $row['amount'];
	$description = $row['description'];
	$dateDue = date('m-d-Y',strtotime($row['dateDue']));

	echo "<tr>";
	echo "<form name=\"update".$billNum."\" action=\"./billsPage.php?mobile=".$mobile."&brink=".$brink."&billNum=".$billNum."\" method=\"post\">";
		if ($brink == "brink")
		{
			echo "<td><input name=\"isUpdate\" id=\"isUpdate\" type=\"submit\" class=\"updateButton\" value=\"Update\" ><input name=\"isDelete\" id=\"isDelete\" type=\"submit\" class=\"deleteButton\" value=\"Delete\" ></td>";

			echo "<td headers=\"payee\"><select name=\"payee".$billNum."\" id=\"payee".$billNum."\">";

			createPayeeList($payee);

			echo "</select></td>";
			echo "<td headers=\"amount\" axis=\"number\"><input type=\"number\" step=\"0.5\" value=\"".$amount."\" name=\"amount".$billNum."\" id=\"amount".$billNum."\" /></td>";
			echo "<td headers=\"desc\" axis=\"sstring\"><input type=\"text\" value=\"".$description."\" name=\"description".$billNum."\" id=\"description".$billNum."\" size=\"80\" /></td>";
			echo "<td headers=\"\"duedate ><input value=\"".$dateDue."\" name=\"dateDue".$billNum."\" id=\"dateDue".$billNum."\" size=\"10\" /></td>";
		}
		else
		{
			echo "<td></td>";
			echo "<td headers=\"payee\" class=\"field\" axis=\"sstring\">".$payee."</td>";
			echo "<td headers=\"amount\" class=\"negAmount\" axis=\"number\">".number_format(abs($amount),2)."</td>";
			echo "<td headers=\"desc\" class=\"field\" axis=\"sstring\">".$description."</td>";
			echo "<td headers=\"duedate\" class=\"field\" >".$dateDue."</td>";
		}

	echo "</form>";
	echo "</tr>";
}

$dbConnection = null;

echo "<tr><td> </td></tr>
	</table>
	<table align=\"center\">
		<tr>
			<td colspan='5' class=\"label\" >Credits</td>
		</tr>
	</table>
	<table class=\"sorted\">
		<thead>
			<tr>
				<td width=\"10%\"> </td>
				<th id=\"payee\" width=\"15%\" axis=\"sstring\" >Payee</th>
				<th id=\"amount\" width=\"15%\" axis=\"number\" >Amount</th>
				<th id=\"desc\" width=\"45%\" axis=\"sstring\" >Description</th>
				<th id=\"duedate\" width=\"15%\" >Date Due</th>
			</tr>
		</thead>
		<tbody>";

include("../DB/dbconnect.php");

$creditsQuery = "SELECT * \n"
	."FROM theboxli_Bills, theboxli_Payees \n"
	."WHERE theboxli_Bills.amount < 0 \n"
	."AND theboxli_Bills.payeeNum = theboxli_Payees.payeeNum \n"
	."AND theboxli_Bills.dateDue BETWEEN DATE_SUB(CURDATE(),INTERVAL 31 DAY) and DATE_ADD(CURDATE(),INTERVAL 2 YEAR) \n"
	."ORDER BY theboxli_Bills.dateDue";

foreach($dbConnection->query($creditsQuery) as $row)
{
	$billNum = $row['billNum'];
	$payee = $row['payeeName'];
	$amount = $row['amount'];
	$description = $row['description'];
	$dateDue = date('m-d-Y',strtotime($row['dateDue']));

	echo "<tr>";

	if ($brink == "brink")
	{
		echo "<form name=\"update".$billNum."\" action=\"./billsPage.php?mobile=".$mobile."&brink=".$brink."&billNum=".$billNum."\" method=\"post\">";

		echo "<td><input name=\"isUpdate\" id=\"isUpdate\" type=\"submit\" class=\"updateButton\" value=\"Update\" ><input name=\"isDelete\" id=\"isDelete\" type=\"submit\" class=\"deleteButton\" value=\"Delete\" ></td>";

		echo "<td headers=\"payee\"><select name=\"payee".$billNum."\" id=\"payee".$billNum."\">";

		createPayeeList($payee);

		echo "</select></td>";
		echo "<td headers=\"amount\" axis=\"number\"><input type=\"number\" step=\"0.5\" value=\"".$amount."\" name=\"amount".$billNum."\" id=\"amount".$billNum."\" /></td>";
		echo "<td headers=\"desc\" axis=\"sstring\"><input type=\"text\" value=\"".$description."\" name=\"description".$billNum."\" id=\"description".$billNum."\" size=\"80\" /></td>";
		echo "<td headers=\"duedate\" ><input value=\"".$dateDue."\" name=\"dateDue".$billNum."\" id=\"dateDue".$billNum."\" size=\"10\" /></td>";
		echo "</form>";
	}
	else
	{
		echo "<td></td>";
		echo "<td headers=\"payee\" class=\"field\" axis=\"sstring\">".$payee."</td>";
		echo "<td headers=\"amount\" class=\"posAmount\" axis=\"number\">".number_format(abs($amount),2)."</td>";
		echo "<td headers=\"desc\" class=\"field\" axis=\"sstring\">".$description."</td>";
		echo "<td headers=\"duedate\" class=\"field\" >".$dateDue."</td>";
	}

	echo "</tr>";
}

$dbConnection = null;

echo "</tbody>
	</table>";

echo "<br />";

if ($brink == "brink")
{

	echo "<table align=\"center\">
	<tr>
		<td width=\"10%\"></td>
		<td width=\"15%\"></td>
		<td width=\"15%\"></td>
		<td width=\"45%\"></td>
		<td width=\"15%\"></td>
	</tr>";

	echo "<form name=\"addBill\" action=\"./billsPage.php?mobile=".$mobile."&brink=".$brink."\" method=\"post\">";

	echo "<tr>
		<td><input name=\"addRec\" type=\"submit\" class=\"addButton\" value=\"Add\" ></td>";
	echo "<td><select name=\"payee\" id=\"payee\">";

	createPayeeList("");

	echo "</select></td>";
	echo "<td><input type=\"number\" step=\"0.5\" name=\"amount\" id=\"amount\" /></td>";
	echo "<td><input type=\"text\" name=\"description\" id=\"description\" size=\"80\" /></td>";
	echo "<td><input type=\"number\" name=\"dateDue\" id=\"dateDue\" size=\"10\" /></td>";
	echo "</tr>";

	echo "</form>
		</table>";
}

echo "</body>
</html>";

?>
