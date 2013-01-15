<?php

    include( "{$_SERVER['DOCUMENT_ROOT']}/htmlMimeMail/htmlMimeMail.php");

function sendConfirmation ($name, $address, $number)
{    
    $mail = new htmlMimeMail();
    
    $background = $mail->getFile('images/email_back.jpg');
    
    if ( $number == 0 )
	    $yesorno = "We're sorry to hear you won't be able to make it.";
	else
		$yesorno = "Thank you very much for honoring us with the promise of your presence.";
    
    $confirm_text = "
Thank you, $name!
You have successfully R.S.V.P.'d to Megan Garland and Lance Roggendorff's wedding!
{$yesorno}
---------------------------------
http://www.iamhero.com/wedding/ -- Lance and Megan's Wedding Website
    ";

    $confirm_html = "
<html>
<head><title>..::Lance and Megan::..</title>
<style type=\"text/css\">
body   {
    color: #555555;
    font-size: 10pt;
    font-family: \"Georgia\",\"Times New Roman\";
    background: url('email_back.jpg') #ffffff no-repeat top left;
    margin: 0;
    padding: 0;
}

em {
    font-size: 120%;
}

a:link  { color: #cc0000 }
a:visited { color:#333333; }
a:hover   { color: #dddd00; text-decoration: underline }
a:active   { color: #ffffff }

.content {
    height: 300px;
    line-height: 145%;
    font-size: 11pt;
    margin-left: 36px;
    padding-left: 26px;
    padding-top: 2px;
}
</style>
</head>
<body>
<div class=\"content\">
<p><em>Thank you, $name!</em></p>
<p>You have successfully R.S.V.P.'d to Megan Garland and Lance Roggendorff's wedding!</p>
<p>{$yesorno}</p>
<hr align=\"left\" width=\"575\">
<p><small><a href=\"http://www.iamhero.com/wedding/\">Lance and Megan's Wedding Website</a></small></p>
</div>
</body>
</html>
    ";
    
    $mail->setHtml($confirm_html, $confirm_text);
    $mail->addHtmlImage($background, 'email_back.jpg', 'image/jpg');
    
    $mail->setFrom('"Lance and Megan" <lanceandmeg@iamhero.com>');
    $mail->setSubject("You've Successfully R.S.V.P.'d Lance and Megan's Wedding");
    $mail->setHeader('X-Mailer', 'HTML Mime mail class (http://www.phpguru.org)');
    
    $result = $mail->send(array($address));

    return $result;
}

function sendRSVP ($name, $address, $number)
{
    $mail = new htmlMimeMail();
    
    if ( $number == 0 )
    	$grammar_counts = "They regret that they'll be unable to attend.";
    else if ( $number == 1 )
        $grammar_counts = "They'll just be by their lonesome.";
    else
        $grammar_counts = "They think about $number people will be tagging along.";
        
    $rsvp_text = "
$name just R.S.V.P'd!
{$grammar_counts}
Their email address is $address in case you need to get in touch with them.
---------------------------------
http://www.iamhero.com/wedding/ -- Lance and Megan's Wedding Website
    ";
    
    $rsvp_html = "
<html>
<head><title>..::Lance and Megan::..</title>
<style type=\"text/css\">
body   {
    color: #555555;
    font-size: 10pt;
    font-family: \"Georgia\",\"Times New Roman\";
    background-color: #ffffff;
    margin: 0;
    padding: 0;
}

em {
    font-size: 120%;
}

a:link  { color: #cc0000 }
a:visited { color:#333333; }
a:hover   { color: #dddd00; text-decoration: underline }
a:active   { color: #ffffff }

.content {
    line-height: 145%;
    font-size: 11pt;
    margin-left: 36px;
    padding-left: 26px;
    padding-top: 2px;
}
</style>
</head>
<body>
<div class=\"content\">
<p><em>$name just  R.S.V.P'd!</em></p>
<p>{$grammar_counts}</p>
<p>Their email address is $address in case you need to get in touch with them.</p>
<hr align=\"left\" width=\"575\">
<p><small><a href=\"http://www.iamhero.com/wedding/\">Lance and Megan's Wedding Website</a></small></p>
</div>
</body>
</html>
    ";

    $mail->setHtml($rsvp_html, $rsvp_text);
    //$mail->addHtmlImage($background, 'background.gif', 'image/gif');
    
    $mail->setFrom("$name <$address>");
    $mail->setSubject("New RSVP from $name");
    $mail->setHeader('X-Mailer', 'HTML Mime mail class (http://www.phpguru.org)');
    
    $result = $mail->send(array('lanceandmeg@iamhero.com'));

    return $result;
}

$their_name = $_REQUEST['name'];
$their_address = $_REQUEST['email'];
$how_many = $_REQUEST['number'];

$dbh = mysql_connect("localhost", "iamher00_wedd", "tseug") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("iamher00_guestlist", $dbh);

$query = 'INSERT INTO guests VALUES (NULL, "' . $their_name . '", "' . $their_address . '", "' . $how_many . '")';
if (!mysql_query($query, $dbh) || mysql_affected_rows() != 1)
    echo "Query: " . $query . "<br /> MySQL says: " . mysql_error();

if (sendRSVP($their_name, $their_address, $how_many)) {
    if (sendConfirmation($their_name, $their_address, $how_many)) {
        if (!headers_sent())
        { 
            header("Location: thanks.html");
            exit;
        }
    }
}
?>