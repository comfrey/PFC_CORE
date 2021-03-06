<?php
/*******************************************************************************

    Copyright 2009 Whole Foods Co-op

    This file is part of Fannie.

    Fannie is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Fannie is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    in the file license.txt along with IT CORE; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*********************************************************************************/

include('../../config.php');
include($FANNIE_ROOT.'auth/login.php');
if (!validateUserQuiet('editcashiers')){
	$url = $FANNIE_URL.'auth/ui/loginform.php';
	$rd = $FANNIE_URL.'admin/Cashiers/index.php';
	header("Location: $url?redirect=$rd");
	return;
}

$page_title = "Fannie : Cashier Management";
$header = "Cashier Management";
include($FANNIE_ROOT.'src/header.html');
?>
<ul>
<li> <a href=add.php>Add a new cashier</a></li>
<li> <a href=view.php>View/edit cashiers</a></li>
<li> <a href="../../reports/cash_report/">Cashier performance report</a></li>
</ul>
<?php
include($FANNIE_ROOT.'src/footer.html');
?>
