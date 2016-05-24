<?php 
header("HTTP/1.1 503 Maintenance Service Temporarily Unavailable");  
header("Status: 503 Maintenance Service Temporarily Unavailable");  
header("Retry-After: 7200");
if ( file_exists('custom.html') )
{
include('custom.html');
} else {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">  
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
<title><?php echo ['HOST']; ?> : Site upgrade in progress</title>  
<meta name="robots" content="none" />  
</head>  
<body>  
<h1>Site upgrade in progress</h1>  
<p>This site is being upgraded, and can't currently be accessed.</p>  
<p>It should be back up and running very soon. Please check back in a bit!</p>  
<hr />  
</body>  
</html> 
<?php } ?> 
