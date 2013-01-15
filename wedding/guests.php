<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>..::Lance and Megan : Guest List::..</title>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
<link href="wedding.css" type="text/css" rel="stylesheet" />
<script src="scripts.js" type="javascript"></script>

</head>
<body>
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD ROWSPAN="2">
    <a href="index.html" title="Lance and Megan" onMouseOut="document.text.src='images/text_blank.png';" onMouseOver="document.text.src='images/text_home.png';"><IMG SRC="images/table_back_01.jpg" WIDTH="378" HEIGHT="165" ALT="Lance and Megan's Wedding"></a></TD>
<TD COLSPAN="2" height="117"><div class="menu">
<a href="story.html" title="Our Story" onMouseOut="document.story.src='images/menu_story.png'; document.text.src='images/text_blank.png';" onMouseOver="document.story.src='images/menu_story_hi.png';document.text.src='images/text_story.png';"><img name="story" src="images/menu_story.png" width="100" height="95" /></a>
<a href="registry.html" title="Where to Buy Stuff" onMouseOut="document.registry.src='images/menu_registry.png'; document.text.src='images/text_blank.png'" onMouseOver="document.registry.src='images/menu_registry_hi.png', document.text.src='images/text_registry.png'"><img name="registry" src="images/menu_registry.png" width="100" height="95" /></a>
<a href="directions.html" title="How to Get There" onMouseOut="document.direct.src='images/menu_direct.png';document.text.src='images/text_blank.png'" onMouseOver="document.direct.src='images/menu_direct_hi.png'; document.text.src='images/text_direct.png'"><img name="direct" src="images/menu_direct.png" width="100" height="95" /></a>
<a href="contact.html" title="Contact and RSVP" onMouseOut="document.contact.src='images/menu_contact.png';document.text.src='images/text_blank.png';" onMouseOver="document.contact.src='images/menu_contact_hi.png';document.text.src='images/text_contact.png'"><img name="contact" src="images/menu_contact.png" width="100" height="95" /></a></div></TD>
</TR>
<TR>
<TD><img src="images/text_blank.png" width="108" height="48" name="text"></TD>
<TD><IMG SRC="images/table_back_04.jpg" WIDTH="314" HEIGHT="48" ALT=""></TD>
</TR>
<TR>
<TD height="435" COLSPAN="3" style="background: url('images/table_back_05.jpg') no-repeat;">
<div class="content">
<p><em>Lance and Megan's Guest List</em></p>
<table id="guestlist" width="550" cellspacing="0" cellpadding="0" border="0">
<?php

$dbh = mysql_connect("localhost", "iamher00_wedd", "tseug") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("iamher00_guestlist", $dbh);

$total = array();
$query = 'SELECT * FROM guests ORDER BY gid';
if (!$result = mysql_query($query, $dbh)) { 
    echo "Query: " . $query . "<br /> MySQL says: " . mysql_error(); 
    break;
}
while ( $row = mysql_fetch_object($result) ) {
    echo "<tr><td>{$row->guest_name}</td>"
        ."<td><a href=\"mailto:{$row->email}\">{$row->email}</a></td>"
        ."<td width=\"10%\" align=\"right\">";
    if ($row->number_attending == 0)
        echo "<span style=\"color:#ff0000\">{$row->number_attending}</span>";
    else
        echo $row->number_attending;
    echo "</td></tr>";
    $total[] = $row->number_attending;
}

echo "<tr><td colspan=\"2\" align=\"right\"><b>Total:</b></td><td align=\"right\">" . array_sum($total) . "</td></tr>";
?>
</table>
</div>
</TD>
</TR>
<tr>
<td colspan="3">
<div class="footer">
Copyright &copy; 2005 Lance and Megan
</div></td>
</tr>
</TABLE>
<!-- End ImageReady Slices -->
</BODY>
</HTML>