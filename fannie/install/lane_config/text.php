<?php
include('../../config.php');
include($FANNIE_ROOT.'src/SQLManager.php');
include($FANNIE_ROOT.'src/mysql_connect.php');

$TRANSLATE = array(
	'receiptHeader'=>'Receipt Header',
	'receiptFooter'=>'Receipt Footer',
	'ckEndorse'=>'Check Endorsement',
	'welcomeMsg'=>'Welcome On-screen Message',
	'farewellMsg'=>'Goodbye On-screen Message',
	'trainingMsg'=>'Training On-screen Message',
	'chargeSlip'=>'Store Charge Slip'
);

if (isset($_REQUEST['new_submit'])){
	$chkQ = "SELECT MAX(seq) FROM customReceipt WHERE type=".$dbc->escape($_REQUEST['new_type']);
	$chkR = $dbc->query($chkQ);
	$seq = 0;
	if ($dbc->num_rows($chkR) > 0){
		$max = array_pop($dbc->fetch_row($chkR));
		if ($max != null) $seq=$max+1;
	}
	if (!empty($_REQUEST['new_content'])){
		$insQ = sprintf("INSERT INTO customReceipt (type,text,seq) VALUES (%s,%s,%d)",
			$dbc->escape($_REQUEST['new_type']),
			$dbc->escape($_REQUEST['new_content']),
			$seq);
		$dbc->query($insQ);
	}
}
else if (isset($_REQUEST['old_submit'])){
	$cont = $_REQUEST['old_content'];
	$type = $_REQUEST['old_type'];
	$seq=0;
	$prev_type='';
	$dbc->query("TRUNCATE TABLE customReceipt");	
	for($i=0;$i<count($cont);$i++){
		if ($prev_type != $type[$i])
			$seq = 0; // new type, reset sequence
		if (empty($cont[$i])) 
			continue; // empty means delete that line

		$q = sprintf("INSERT INTO customReceipt (type,text,seq) VALUES (%s,%s,%d)",
			$dbc->escape($type[$i]),
			$dbc->escape($cont[$i]),
			$seq);
		$dbc->query($q);

		$prev_type=$type[$i];
		$seq++;
	}
}

?>
<a href="index.php">Necessities</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="extra_config.php">Additional Configuration</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="scanning.php">Scanning Options</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="security.php">Security</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Text Strings
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<hr />
<form method="post" action="text.php">
<select name="new_type">
<?php
foreach($TRANSLATE as $short=>$long){
	printf('<option value="%s" %s>%s</option>',
		$short,
		(isset($_REQUEST['new_type'])&&$_REQUEST['new_type']==$short)?'selected':'',
		$long);
}
?>
</select>
<input type="text" name="new_content" maxlength="80" />
<input type="submit" name="new_submit" value="Add" />
</form>
<hr />
<form method="post" action="text.php">
<?php
$q = "SELECT type,text FROM customReceipt ORDER BY type,seq";
$r = $dbc->query($q);
$header="";
$i=1;
while($w = $dbc->fetch_row($r)){
	if ($header != $w['type']){
		echo '<h3>'.$TRANSLATE[$w['type']].'</h3>';
		$header = $w['type'];	
		$i=1;
	}
	printf('<p>%d:<input type="text" maxlength="80" name="old_content[]" value="%s" />
		<input type="hidden" name="old_type[]" value="%s" /></p>',
		$i++,$w['text'],$w['type']);
}
?>
<input type="submit" name="old_submit" value="Save Changes" />
</form>
