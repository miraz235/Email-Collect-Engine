<?php
/*
 * Created by Ashraful Karim Miraz
 */
 
error_reporting(0);
session_start();
$sid = session_id();
/* Set the current directory name */
$dir = opendir(dirname(__FILE__)) or die("Could not open directory");
chdir(dirname(__FILE__));
/* Delete all searching emails files on 1 day before */
$yesterday = strtotime('yesterday');
while(($dirent = readdir($dir)) != false)
{
    if(is_file($dirent))
    {
		if($dirent!='index.php'){
			$cur = filemtime($dirent);
			if($cur < $yesterday)
				unlink($dirent);
		}
    }
}
closedir($dir);

/* a large array splits with group of index of a new array */
function array_split($array,$grp){
	if(count($array)<=$grp)
		return array($array);
	$a = array_slice($array, 0, $grp);
	$b = array_split(array_slice($array, $grp),$grp);
	return array_merge(array($a),$b);
}

/* Set default or new url variable */
if($_FILES['emailpage']['size']>0)
	$_POST['url'] = 'http://';
else{
	$_POST['url'] = str_replace('https://','http://',$_POST['url']);		  
	if (false === strpos($_POST['url'],'http://')) 
		$_POST['url'] = 'http://'.$_POST['url'];
}

/* Set maximum number of emails per line in downloadable email files */
$_POST['perlineno'] = intval($_POST['perlineno']);

if($_POST['perlineno']<1) 
	$_POST['perlineno'] = 4;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="Free Email Collect Engine by Ashraful Karim Miraz" name="description">
<meta content="email collect, email collection, email search, email engine, search engine, search email, get email, get emails from, email from url, url emails, download emails, email file genarator, get email from file" name="keywords">
<title>.: Collect Emails<?php echo ($_POST['url']!='http://') ? ' from '.$_POST['url'] : '' ?> :.</title>
<style>
body{font-family:Verdana, Geneva, sans-serif; font-size:11px; line-height:150%;}
h2{font-size:17px; margin:3px 0;}
a{color:#360;}
a:hover{color:#06F;}
input,label{ vertical-align:middle; }
label{font-weight:bold;}
input[type="text"]{ border:1px solid #666; background:#EEE;}
input[type="text"]:focus{outline:2px solid #999; background:#FFF;}
#url{width:500px;}
#csvresult{	border:1px solid #333; background:#FFF; padding:5px;}
span.oldcsv{background:#CCC;}
span.newcsv span.oldcsv{color:#063;}
ol{ float:left; margin:2px; list-style:decimal; border:1px solid #333; background:#0CF; padding-left:40px; }
ol li{ padding:1px 2px;}
ol li:hover, ol li.sfhover{ background:#FBB;}
ol li:hover, ol li:hover span, ol li.sfhover span, ol li.sfhover{color:#000 !important;}
big{ font-family:Georgia; font-weight:bold;}
#box{margin:15px; padding:10px;	border:1px solid #666; background-color:#EEE;}
#box div{ padding:2px; }
.row1{ background:#BBB;}
.row2{ background:#CCC;}
.row3{ background:#AAA; border-top:1px solid #999999; border-bottom:1px solid #FFF;}
.sap{margin:0 20px;}
#result{margin:15px; padding:10px; border:1px solid #999; background:#FFC; overflow:hidden; min-height:140px;}
.r0{ background:#EED; border-bottom: 1px solid #EED; border-top: 1px solid #EED;}
.r1{ background:#FFF; border-bottom: 1px solid #FFF; border-top: 1px solid #FFF;}
.old{ background:#999; border-bottom: 1px solid #666; border-top: 1px solid #CCC;}
.old span{color:#FFF;}
.red{ color:#F00;}
.clr{ clear:both; }
#hbox{background:none repeat scroll 0 0 #EDEFF4; border:2px solid #666666; float:right; padding:0 2px;}
#help{position:relative; padding:0; margin:0; list-style:none; cursor:pointer;}
#help li{ text-align:right; padding:0; margin:0; }
#help li ul{ position:absolute; right:-999px; width: 420px; border:5px solid #666666; background-color:#EDEFF4; padding:0; margin:0; list-style:none; cursor:text; }
#help li:hover ul, #help li.sfhover ul{ right:-4px; top:16px; }
#help li ul li{ padding:3px; }
#help li ul ul{ position:inherit; width:auto; border:none; background-color:transparent; }
#help li li{ text-align:left; }
#help li li ul li{ padding: 4px 3px; }
</style>
<!--[if lte IE 6]>
<script type="text/javascript">
sfHover = function()
    {
       var sfEls = document.getElementById("hbox").getElementsByTagName("li");
       for (var i=0; i<sfEls.length; i++)
       {
          sfEls[i].onmouseover=function()
          {
             this.className+=" sfhover";
          }

          sfEls[i].onmouseout=function()
          {
             this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
          }
       }
    }
if (window.attachEvent) window.attachEvent("onload", sfHover);
sfHover = function()
    {
       var sfEls = document.getElementById("emails").getElementsByTagName("li");
       for (var i=0; i<sfEls.length; i++)
       {
          sfEls[i].onmouseover=function()
          {
             this.className+=" sfhover";
          }

          sfEls[i].onmouseout=function()
          {
             this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
          }
       }
    }
if (window.attachEvent) window.attachEvent("onload", sfHover);
</script>
<![endif]-->
</head>
<body>
<div id="box">
<h2>EMAIL COLLECT ENGINE</h2>
<p style="color:#006600; font-weight:bold">Select txt,csv,htm,html,xml type file or enter a valid URL in text field and collect emails.</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>#results" method="post" enctype="multipart/form-data">
	<div class="row1">
        <input type="file" name="emailpage" id="emailpage" /> or
        <input type="text" name="url" id="url" name="urltext" value="<?php echo $_POST['url']; ?>" />
    </div>
    <div class="row2">
        <label>Show Type:</label> 
        <input type="radio" name="stype" id="stype1" value="1" checked="checked" /> 
        <label for="stype1">CSV Format</label>
        <input type="radio" name="stype" id="stype2" value="2" <?php if($_POST['stype']==2) echo 'checked="checked"'; ?> /> 
        <label for="stype2">List Format</label>
        <span class="sap">|</span>
        <label>Order:</label>
    	<input type="radio" name="order" id="order1" value="1" checked="checked" /> 
        <label for="order1">General</label>
        <input type="radio" name="order" id="order2" value="2" <?php if($_POST['order']==2) echo 'checked="checked"'; ?> /> 
        <label for="order2">Asending</label>
        <input type="radio" name="order" id="order3" value="3" <?php if($_POST['order']==3) echo 'checked="checked"'; ?> /> 
        <label for="order3">Desanding</label>
    </div>
    <div class="row1">
    	<label for="perlineno">Number of emails per line for downloadable files:</label> 
        <input type="text" size="1" style="text-align:center" value="<?php echo $_POST['perlineno'] ?>" name="perlineno" id="perlineno" />
        <span class="sap">|</span>
        <input type="checkbox" name="append" id="append" value="1" /> 
        <label for="append">Add new emails with previous emails in files [+]</label>
    </div>
    <div class="row3">
		<input type="submit" value="Collect Emails" name="submit" />
    </div>
</form>
</div>

<?php
/* Get input */
$efile = $_FILES['emailpage'];
$url = trim($_POST['url']);
$str = '';
if($url!='http://' || $efile['size']!=0){
?>
<a name="results"></a>
<div id="result">
	<?php
	/* validation for uploaded file or given url */
	$allowedExtensions = array("txt","csv","htm","html","xml"); 
	if ($efile['tmp_name'] > '') {
		if (!in_array(end(explode(".",
			strtolower($efile['name']))),
			$allowedExtensions)) {
				die('<p class="red">'.$efile['name'].' has not allowed file type!</p>');
		} 
		$str = file_get_contents($efile['tmp_name']);
		unlink($efile['tmp_name']);
	}
	else{
		/* URL pattern expression */
		$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?([\&amp;|\&]?([-+_~.\:\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
		if(preg_match($pattern, $url)){
			if( false == ($str = file_get_contents($url)))
				die('<p class="red">could not read from '.$url.'</p>');
		}
		else
			die('<p class="red">The url is not valid</p>');
	}
	
	/* Email pattern expression */
	$searchEmail = '([\w\.\-]+(\@|\[at\])(?:[a-z0-9(\.|\[dot\])\-]+(\.|\[dot\]))+(?:[a-z0-9\-]{2,4}))';
	
	/* Searching emails from haystack */
	preg_match_all($searchEmail, $str, $matches);
	$mail=array();
	foreach ($matches[0] as $v) {
		/* Convert all hidden emails to correct email structure */
		$v = str_replace(array("[at]","[dot]"), array("@","."), $v);
		/* Collection of all founded mails */
		if(!in_array($v,$mail)){
			$mail[] = $v;
		}
	}
	$olddata = array();
	$newmailno = count($mail);
	$foundmails = $mail;
	$foundmsg = "<p%s>%s new email%s found";
	if($url!='http://')
		$foundmsg .= " in <a href=\"$url\" target=\"_blank\">$url</a>";
	$foundmsg .= "</p>";
	
	/* Add all new mails to old emails in downloadable email files */ 
	if($_POST['append']){
		if (($handle = fopen($sid.".csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				foreach ($data as $v) {
					if(!in_array($v,$mail))
						$mail[] = $v;
					else
						$newmailno--;
				}
				$olddata = array_merge($data,$olddata);
			}
			fclose($handle);
		}
	}

	if(empty($mail)){
		printf($foundmsg," class=\"red\"","no","s");
	}
	else{
		/* Total number of founded emails */
		$n = count($mail);
		
		/* Rearranging all emails to acending order */
		if($_POST[order]==2)
			asort($mail);
		/* Rearranging all emails to decending order */
		else if($_POST[order]==3)
			arsort($mail);
			
		/* Show help or wiki for CSV (comma delimited) style outputs */
		if($_POST['stype'] == 1){
			?>
			<div id="hbox">
				<ul id="help">
					<li>Help
						<ul>
							<li>
								<ul style="float:left">
									<li><span class="newcsv">etc@example.com</span></li>
									<li><span class="newcsv"><span class="oldcsv">etc@example.com</span></span></li>
									<li><span class="oldcsv">etc@example.com</span></li>
								</ul>
								<ul style="float:right">
									<li>&laquo; New email</li>
									<li>&laquo; Old email but found in searching file/URL</li>
									<li>&laquo; Old email but not in searching file/URL</li> 
								</ul>
								<div class="clr"></div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<?php
		}
		/* Show help or wiki for list style outputs */
		else{
			?>
			<div id="hbox">
				<ul id="help">
					<li>Help
						<ul>
							<li>
								<ol style="float:left">
									<li class="r1">etc@example.com</li>
									<li class="r0">etc@example.com</li>
									<li class="old"><span>etc@example.com</span></li>
									<li class="old">etc@example.com</li>
								</ol>
								<ul style="float:right">
									<li>&laquo; New email</li>
									<li>&laquo; New email</li>
									<li>&laquo; Old email but found in searching file/URL</li>
									<li>&laquo; Old email but not in searching file/URL</li>
								</ul>
								<div class="clr"></div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<?php
		}
		
		/* Create email files downloadable links */
		echo '<p>Download: <a href="'.$sid.'.csv" title="Download CSV File" target="_blank">CSV</a>, <a href="'.$sid.'.txt" title="Download TXT File" target="_blank">TXT</a></p>';
		
		/* Show message */
		if(count($olddata)==$n)
			printf($foundmsg," class=\"red\"","no","s");
		else
			printf($foundmsg,"","<big>".$newmailno."</big>",(($newmailno>1)?'s':''));
			
		/* Show CSV style output */
		if($_POST['stype'] == 1){
			$csvmail = $mail;
			foreach($csvmail as $k=>$m){
				if(in_array($m,$olddata))
					$csvmail[$k] = "<span class=\"oldcsv\">".$m."</span>";
				if(in_array($m,$foundmails))
					$csvmail[$k] = "<span class=\"newcsv\">".$csvmail[$k]."</span>";
			}
			$emails = implode(', ',$csvmail);
			echo "<div id=\"csvresult\">".$emails."</div>";
		}
		/* Show list style output */
		else{
			if($n > 0){
				$i = 0;
				$grp = ceil($n/4);
				if($grp < 10) 
					$grp = 10;
				$list = array_split($mail,$grp);
				echo '<div id="emails">';
				foreach ($list as $line){
					$row = 1;
					echo '<ol start="'.($i+1).'">';
					foreach($line as $onemail){
						$old="";
						$new = false;
						if(in_array($onemail,$olddata))
							$old = " old";
						if(in_array($onemail,$foundmails))
							$new = true;
						echo '<li class="r'.$row.$old.'">'.($new?'<span>':'').$onemail.($new?'</span>':'').'</li>';
						$i++;
						$row = 1-$row;
					}
					echo '</ol>';
				}
				echo '</div>';
			}
		}
		
		/* Collect emails for to save in downlodable files */
		$grp = $_POST['perlineno'];
		$list = array_split($mail,$grp);
		$emailcontent='';
		foreach ($list as $line)
			$emailcontent .= implode(',',$line)."\n";
		
		/* Create Downlodable files */
		$file = fopen($sid.".csv","w");
		fwrite($file, $emailcontent);
		fclose($file);
		
		$file = fopen($sid.".txt","w");
		fwrite($file, $emailcontent);
		fclose($file);
		
	}
	?>
    <div class="clr"></div>
</div>
<?php
}
?>
	
</body>
</html>


