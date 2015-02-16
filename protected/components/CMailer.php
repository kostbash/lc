<?php

class CMailer
{
    private static $open_SSL_pub = "";
    private static $open_SSL_priv = "";
    // DKIM Configuration
    // Domain of the signing entity (i.e. the email domain)
    // This field is mandatory
    private static $DKIM_d = '';
    // Default identity 
    // Optional (can be left commented out), defaults to no user @$DKIM_d
    private static $DKIM_i = '';
    // Selector, defines where the public key is stored in the DNS
    //    $DKIM_s._domainkey.$DKIM_d
    // Mandatory
    private static $DKIM_s = '';

    public static function send($to, $from, $subject, $body, $file_name = array(), $path = array())
    {
	if (is_array($body))
	{
	    if (isset($body['template']))
	    {
		$template = MailTemplates::model()->findByAttributes(array('template_name' => $body['template']));
		if ($template)
		{
		    $subject = $template->template_subject;
		    $temp_body = $template->template_body;
		}
		else
		{
		    return;
		}
	    }
	    else
	    {
		$temp_body = $body['text'];
	    }
	    if (isset($body['vars']))
	    {
		foreach ($body['vars'] as $var => $val)
		{
		    $temp_body = str_replace('{' . $var . '}', $val, $temp_body);
		}
		$body = $temp_body;
	    }
	}

	if (empty($path) && empty($file_name))
	{
	    $from['name'] = self::mime_header_encode($from['name'], 'utf-8', 'utf-8');
	    if (!$from['reply'])
		$from['reply'] = $from['email'];
	    $subject = self::mime_header_encode($subject, 'utf-8', 'utf-8');
	    $headers = "Content-Type: text/html; charset=\"UTF-8\"\r\n";
	    $headers .= "To: " . $to['email'] . "\r\n";
	    $headers .= "From: {$from['name']} <{$from['email']}>\r\nReply-To: {$from['reply']}\r\nSender: " . Yii::app()->params['adminEmail'];
	}
	else
	{
	    $from['name'] = self::mime_header_encode($from['name'], 'utf-8', 'utf-8');
	    if (!$from['reply'])
		$from['reply'] = $from['email'];
	    $subject = self::mime_header_encode($subject, 'utf-8', 'utf-8');
	    //$body = self::mime_header_encode($body, 'utf-8', 'utf-8');
	    $body_tmp = $body;


	    $EOL = "\r\n";
	    $boundary = "--" . md5(uniqid(time()));

	    $headers = "MIME-Version: 1.0;$EOL";
	    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
	    $headers .= "To: " . $to['email'] . "\r\n";
	    $headers .= "From: " . $from['name'] . " <" . $from['email'] . ">\r\nReply-To: {$from['reply']}\r\nSender: " . Yii::app()->params['adminEmail'];

	    $body = "--$boundary$EOL";
	    $body .= "Content-Type: text/html; charset=UTF-8$EOL";
	    $body .= "Content-Transfer-Encoding: base64$EOL";
	    $body .= $EOL;
	    $body .= chunk_split(base64_encode($body_tmp));

	    $p = count($path);
	    $f = count($file_name);
	    $max = ($p > $f) ? $p : $f;

	    for ($i = 0; $i < $max; $i++)
	    {
		$fp[$i] = fopen($path[$i], "rb");
		$file[$i] = fread($fp[$i], filesize($path[$i]));
		fclose($fp[$i]);
		$body .= "$EOL--$boundary$EOL";
		$body .= "Content-Type: application/pdf; name=\"$file_name[$i]\"$EOL"; // octet-stream
		$body .= "Content-Transfer-Encoding: base64$EOL";
		$body .= "Content-Disposition: attachment; filename=\"$file_name[$i]\"$EOL";
		$body .= $EOL; // ������ ����� ����������� � ����� �������������� ����� 
		$body .= chunk_split(base64_encode($file[$i]));
	    }

	    $body .= "$EOL--$boundary--$EOL";
	}

	//self::BuildDNSTXTRR() ;
	//$headers .= "Return-Receipt-To: " . $from['email'] . "\r\n";
	//$headers .= "X-Confirm-Reading-To: " . $from['email'] . "\r\n";
	//$headers .= "Disposition-Notification-To: " . $from['email'] . "\r\n";
	//$headers = self::AddDKIM($headers_to.$headers,$subject,$body) . $headers;
	$headers = str_replace("\r\n", "\n", $headers);
	$sender = $from['email'];

	return mail($to['email'], $subject, $body, $headers);
    }

    public static function mime_header_encode($str, $data_charset, $send_charset)
    {
	if ($data_charset != $send_charset)
	{
	    $str = iconv($data_charset, $send_charset, $str);
	}
	return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
    }

    public static function sendAdmin($body)
    {
	$to['name'] = '';
	$to['email'] = '';
	$from['name'] = '';
	$from['email'] = Yii::app()->params['adminEmail'];
	$subject = 'Уведомление';
	CMailer::send($to, $from, $subject, $body);
    }

    static function BuildDNSTXTRR()
    {

	$pub_lines = explode("\n", self::$open_SSL_pub);
	$txt_record = self::$DKIM_s . "._domainkey\tIN\tTXT\t\"v=DKIM1\\; k=rsa\\; g=*\\; s=email\; h=sha1\\; t=s\\; p=";
	foreach ($pub_lines as $pub_line)
	    if (strpos($pub_line, '-----') !== 0)
		$txt_record.=$pub_line;
	$txt_record.="\;\"";
	/* print("Excellent, you have DKIM keys
	  You should add the following DNS RR:
	  $txt_record

	  ") ;// */
    }

    static function DKIMQuotedPrintable($txt)
    {
	$tmp = "";
	$line = "";
	for ($i = 0; $i < strlen($txt); $i++)
	{
	    $ord = ord($txt[$i]);
	    if (((0x21 <= $ord) && ($ord <= 0x3A)) || $ord == 0x3C || ((0x3E <= $ord) && ($ord <= 0x7E)))
		$line.=$txt[$i];
	    else
		$line.="=" . sprintf("%02X", $ord);
	}
	return $line;
    }

    static function DKIMBlackMagic($s)
    {
	$key = openssl_get_privatekey(self::$open_SSL_priv);
	if ($key == FALSE)
	{
	    echo '<br/>PRIVATE KEY IS FALSE<br/>' . openssl_error_string();
	}
	if (openssl_sign($s, $signature, $key))
	    return base64_encode($signature);
	else
	    die("Cannot sign: " . openssl_error_string());
    }

    static function NiceDump($what, $body)
    {
	print("After canonicalization ($what):\n");
	for ($i = 0; $i < strlen($body); $i++)
	    if ($body[$i] == "\r")
		print("'OD'");
	    elseif ($body[$i] == "\n")
		print("'OA'\n");
	    elseif ($body[$i] == "\t")
		print("'09'");
	    elseif ($body[$i] == " ")
		print("'20'");
	    else
		print($body[$i]);
	print("\n------\n");
    }

    static function SimpleHeaderCanonicalization($s)
    {
	return $s;
    }

    static function RelaxedHeaderCanonicalization($s)
    {
	// First unfold lines
	$s = preg_replace("/\r\n\s+/", " ", $s);
	// Explode headers & lowercase the heading
	$lines = explode("\r\n", $s);
	foreach ($lines as $key => $line)
	{
	    list($heading, $value) = explode(":", $line, 2);
	    $heading = strtolower($heading);
	    $value = preg_replace("/\s+/", " ", $value); // Compress useless spaces
	    $lines[$key] = $heading . ":" . trim($value); // Don't forget to remove WSP around the value
	}
	// Implode it again
	$s = implode("\r\n", $lines);
	// Done :-)
	return $s;
    }

    static function SimpleBodyCanonicalization($body)
    {
	if ($body == '')
	    return "\r\n";

	// Just in case the body comes from Windows, replace all \r\n by the Unix \n
	$body = str_replace("\r\n", "\n", $body);
	// Replace all \n by \r\n
	$body = str_replace("\n", "\r\n", $body);
	// Should remove trailing empty lines... I.e. even a trailing \r\n\r\n
	// TODO
	while (substr($body, strlen($body) - 4, 4) == "\r\n\r\n")
	    $body = substr($body, 0, strlen($body) - 2);
//	NiceDump('SimpleBody',$body) ;
	return $body;
    }

    static function AddDKIM($headers_line, $subject, $body)
    {

//??? a tester	$body=str_replace("\n","\r\n",$body) ;
	$DKIM_a = 'rsa-sha1'; // Signature & hash algorithms
	$DKIM_c = 'relaxed/simple'; // Canonicalization of header/body
	$DKIM_q = 'dns/txt'; // Query method
	$DKIM_t = time(); // Signature Timestamp = number of seconds since 00:00:00 on January 1, 1970 in the UTC time zone
	$subject_header = "Subject: $subject";
	$headers = explode("\r\n", $headers_line);
	foreach ($headers as $header)
	{
	    if (strpos($header, 'From:') === 0)
		$from_header = $header;
	    elseif (strpos($header, 'To:') === 0)
		$to_header = $header;
	}
	$from = str_replace('|', '=7C', self::DKIMQuotedPrintable($from_header));
	$to = str_replace('|', '=7C', self::DKIMQuotedPrintable($to_header));
	$subject = str_replace('|', '=7C', self::DKIMQuotedPrintable($subject_header)); // Copied header fields (dkim-quoted-printable
	$body = self::SimpleBodyCanonicalization($body);
	$DKIM_l = strlen($body); // Length of body (in case MTA adds something afterwards)
	$DKIM_bh = base64_encode(pack("H*", sha1($body))); // Base64 of packed binary SHA-1 hash of body
	$i_part = (self::$DKIM_i == '') ? '' : " i=" . self::$DKIM_i . ";";
	$b = ''; // Base64 encoded signature
	$dkim = "DKIM-Signature: v=1; a=$DKIM_a; q=$DKIM_q; l=$DKIM_l; s=" . self::$DKIM_s . ";\r\n" .
		"\tt=$DKIM_t; c=$DKIM_c;\r\n" .
		"\th=From:To:Subject;\r\n" .
		"\td=" . self::$DKIM_d . ";$i_part\r\n" .
		"\tz=$from\r\n" .
		"\t|$to\r\n" .
		"\t|$subject;\r\n" .
		"\tbh=$DKIM_bh;\r\n" .
		"\tb=";
	$to_be_signed = self::RelaxedHeaderCanonicalization("$from_header\r\n$to_header\r\n$subject_header\r\n$dkim");
	$b = self::DKIMBlackMagic($to_be_signed);
	return "X-DKIM: php-dkim.sourceforge.net\r\n" . $dkim . $b . "\r\n";
    }

}

?>