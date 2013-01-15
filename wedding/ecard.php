<?php

include( "{$_SERVER['DOCUMENT_ROOT']}/htmlMimeMail/htmlMimeMail.php");

$dbh = mysql_connect("localhost", "iamher00_wedd", "tseug") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("iamher00_guestlist", $dbh);

function showList ($connection)
{
    echo "<p><small>The ecard will be sent to the following people:</small></p>";
    $select = "SELECT id, name, email FROM ecard";
    if (!($stuff = mysql_query($select, $connection)))
        die("Query: " . $select . "<br /> MySQL says: " . mysql_error());
    echo "<p>";
    while ($row = mysql_fetch_object($stuff)) {
        echo "Name: ".$row->name." Email: ".$row->email." <a href=\"ecard.php?action=delete&id=".$row->id."\">Delete</a><br />";
    }
    echo "</p>";
}

function sendEcard ($name, $address)
{
    $mail = new htmlMimeMail();
    
    $images[] = $mail->getFile('images/ecard_01.jpg');
    $images[] = $mail->getFile('images/ecard_02.jpg');
    $images[] = $mail->getFile('images/ecard_03.jpg');
    $images[] = $mail->getFile('images/ecard_04.jpg');
    $images[] = $mail->getFile('images/ecard_05.jpg');
    
    $confirm_text = "
Dear $name,
We would like to announce our engagement to be married!
And we'd like for you to visit our new wedding website:
http://www.iamhero.com/wedding/

If you know you'll be attending, please R.S.V.P. on the Contact page at
http://www.iamhero.com/wedding/contact.html

Thank you, and we look forward to seeing you there!

Lance and Megan
";

    $confirm_html = '
<html>
<head>
<title>..::Lance and Megan::..</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<style type="text/css" media="screen">
body {
	margin: 0px;
	background: white;
	font: Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#555555;
}

p {
	padding: 10px;
}
</style>
</head>
<body> 
<p>This e-announcement has been especially prepared for: '.$name.' </p>
<table width="787" border="0" cellpadding="0" cellspacing="0"> 
  <tr> 
    <td colspan="3"><img src="images/ecard_01.jpg" width="787" height="370" alt=""></td> 
  </tr> 
  <tr> 
    <td rowspan="2"><img src="images/ecard_02.jpg" width="51" height="154" alt=""></td> 
    <td><a href="http://www.iamhero.com/wedding/"><img border="0" src="images/ecard_03.jpg" width="506" height="37" alt=""></a></td> 
    <td rowspan="2"><img src="images/ecard_04.jpg" width="230" height="154" alt=""></td> 
  </tr> 
  <tr> 
    <td><img src="images/ecard_05.jpg" width="506" height="117" alt=""></td> 
  </tr> 
</table>
</body>
</html>
    ';
    
    $mail->setHtml($confirm_html, $confirm_text);
    for ($i = 0; $i < count($images); $i++)
          $mail->addHtmlImage($images[$i], 'images/ecard_0'.($i+1).'.jpg', 'image/jpg');
    
    $mail->setFrom('"Lance and Megan" <lanceandmeg@iamhero.com>');
    $mail->setSubject("Please visit our wedding website!");
    $mail->setHeader('X-Mailer', 'HTML Mime mail class (http://www.phpguru.org)');
    
    $result = $mail->send(array($address));

    if (!$result) {
        return false;
    } else {
        return true;
    }
}

switch (strtolower($_REQUEST['action'])) {
    case ("saveandadd"):
        $insert = "INSERT INTO ecard VALUES (NULL, '" . $_REQUEST['name'] . "', '" . $_REQUEST['email'] . "')";
        mysql_query($insert, $dbh);
?>
<html>
<head>
<title>ecard</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link type="text/css" href="wedding.css" rel="stylesheet" />
</head>
<body>
<div class="content">
<table border="0">
<tr>
<td><? showList($dbh); ?></td>
<td>
<form action="ecard.php?action=saveandadd" method="post">
<p>Name: <input type="text" size="30" name="name" /><br />
Email: <input type="text" size="30" name="email" /></p>
<p><input type="submit" name="submit" value="Save and Add New" /></p>
</form>
</td>
</tr>
<tr>
<td>
<form action="ecard.php?action=send" method="post">
<p><input type="submit" name="submit" value="Send Ecards" /></p>
</form>
</td>
<td>&nbsp;</td>
</tr>
</table>
</div>
</body>
</html>
<?
    break;
    case ("delete"):
        $delete = "DELETE FROM ecard WHERE id = " . $_REQUEST['id'];
        mysql_query($delete, $dbh);
?>
<html>
<head>
<title>ecard</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link type="text/css" href="wedding.css" rel="stylesheet" />
</head>
<body>
<div class="content">
<table border="0">
<tr>
<td><? showList($dbh); ?></td>
<td>
<form action="ecard.php?action=saveandadd" method="post">
<p>Name: <input type="text" size="30" name="name" /><br />
Email: <input type="text" size="30" name="email" /></p>
<p><input type="submit" name="submit" value="Save and Add New" /></p>
</form>
</td>
</tr>
<tr>
<td>
<form action="ecard.php?action=send" method="post">
<p><input type="submit" name="submit" value="Send Ecards" /></p>
</form>
</td>
<td>&nbsp;</td>
</tr>
</table>
</div>
</body>
</html>
<?
    break;
    case ("send"):
        $namesandaddresses = "SELECT name, email FROM ecard";
        if (!($result = mysql_query($namesandaddresses, $dbh)))
            die("Query: " . $namesandaddresses . "<br /> MySQL says: " . mysql_error());
        while ($row = mysql_fetch_object($result)) {
            if (sendEcard($row->name, $row->email))
                continue;
            else
                die ("Couldn't send email to ".$row->name." <".$row->email.">");
        }
        if (!headers_sent())
        { 
            header("Location: ecard.php?action=thanks");
            exit;
        }
    break;
    case ("thanks"):
?>
<html>
<head>
<title>ecard</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link type="text/css" href="wedding.css" rel="stylesheet" />
</head>
<body>
<div class="content">
<p>The ecards were successfully sent</p>
</div>
</body>
</html>
<?
    break;
    default:
?>
<html>
<head>
<title>ecard</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link type="text/css" href="wedding.css" rel="stylesheet" />
</head>
<body>
<div class="content">
<table border="0">
<tr>
<td><? showList($dbh); ?></td>
<td>
<form action="ecard.php?action=saveandadd" method="post">
<p>Name: <input type="text" size="30" name="name" /><br />
Email: <input type="text" size="30" name="email" /></p>
<p><input type="submit" name="submit" value="Save and Add New" /></p>
</form>
</td>
</tr>
<tr>
<td>
<form action="ecard.php?action=send" method="post">
<p><input type="submit" name="submit" value="Send Ecards" /></p>
</form>
</td>
<td>&nbsp;</td>
</tr>
</table>
</div>
</body>
</html>
<?
    break;
}

?>