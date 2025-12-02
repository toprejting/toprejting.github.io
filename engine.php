<p>
ProxyChecker Script Engine <b>ver.2.0</b>
</p>
<form method="post">
<input type="text" name="zpost" value="inside">
<input type="submit" value="Check POST method">
Click here for test engine.php: <small><a href="engine.php?list=check">Check script for problems</a></small><br><br>
</form>
<?php

date_default_timezone_set('Europe/Moscow');//need config
setlocale(LC_ALL,'ru_RU');//need config
global $_SERVER;
if(isset($_SERVER['HTTP_X_REAL_IP'])){$_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];}
elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){$_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_FORWARDED_FOR'];};

$get=array();
$post=array();

if('GET'===$_SERVER['REQUEST_METHOD'])
	{
	$k='ip';
	if(isset($HTTP_GET_VARS[$k])){$get[$k]=$HTTP_GET_VARS[$k];};
	if(isset($_GET[$k])){$get[$k]=$_GET[$k];};
	
	$k='list';
	if(isset($HTTP_GET_VARS[$k])){$get[$k]=$HTTP_GET_VARS[$k];};
	if(isset($_GET[$k])){$get[$k]=$_GET[$k];};
	};

if('POST'===$_SERVER['REQUEST_METHOD'])
	{
	$k='xrumer';
	if(isset($HTTP_POST_VARS[$k])){$post[$k]=$HTTP_POST_VARS[$k];};
	if(isset($_POST[$k])){$post[$k]=$_POST[$k];};
	
	$k='zpost';
	if(isset($HTTP_POST_VARS[$k])){$post[$k]=$HTTP_POST_VARS[$k];};
	if(isset($_POST[$k])){$post[$k]=$_POST[$k];};
	};


if((isset($get['ip'])) && ('get'===$get['ip']))
{
  printf('|%s|',$_SERVER['REMOTE_ADDR']);
}

if((isset($get['list'])) && ('check'===$get['list']))
{
  echo '<br><u>Dianostics result:</u><br>';
  echo '<font color=green><b>Your IP: '.$_SERVER['REMOTE_ADDR'].'</font></b><br>';
  if (file_exists('list.txt'))
  {
     echo '<font color=green><b>list.txt exist: OK</font></b><br>';
     $linkslist = file('list.txt');
     if (sizeof($linkslist)==0)
     {
        echo '<font color=red><b>list.txt size = 0!</font></b><br>';
     }
     else
     {
        echo '<font color=green><b>list.txt lines size = '.sizeof($linkslist).': OK</font></b><br>';
     }
  }
  else
  {
     echo '<font color=red><b>list.txt NOT exist in current folder, you should upload list.txt</font></b><br>';
  }
  echo '<br><br>';

}


if((isset($get['list'])) && ('get'===$get['list']))
{
	$linkslist = file('list.txt');
	print('|');
	for ($current_number = 0; $current_number < sizeof($linkslist); $current_number++) 
	{
		$url = trim($linkslist[$current_number]);
		// Version 2.0 additional function:
		if (preg_match('|<date[^>]*?>(.*?)</date>|', $url, $date_arr)) 
		{
			$date_macros = date($date_arr[1]);
			$url = preg_replace("|<date[^>]*?>(.*?)</date>|",$date_macros,$url);
		}
		
		$url = str_replace('<year>',date("Y"),$url);
		$url = str_replace('<month>',date("m"),$url);
		$url = str_replace('<day>',date("d"),$url); 
		
		if ($url<>'') print($url.'|');
	}
}
else
{
	echo "<small>Header values list:</small>";
	echo "<hr><i><small>";
	foreach($_SERVER as $key => $value)
	{
		if(preg_match("/^HTTP_|^REMOTE_/i",$key))
		printf("%s: %s\n",$key,$value);
	}
	
	if(isset($HTTP_POST_VARS))
	{
		foreach($HTTP_POST_VARS as $key => $value)
		{
			printf("$%s=%s\n",$key,$value);
		}
	}
	elseif(isset($_POST))
	{
		foreach($_POST as $key => $value)
		{
			printf("$%s=%s\n",$key,$value);
		}
	};
	echo "FLASHWM.NET\n";
	echo "</small></i><hr>";
}
?>