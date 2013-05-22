<?php const FORM_VALUE_COUNT = 5;?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Http Client</title>
<link href="<?php echo base_url();?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
</style>
<link href="<?php echo base_url();?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<script type="text/javascript"><!--
function clearFormAll() {
	for (var i=0; i<document.forms.length; ++i) {
		clearForm(document.forms[i]);
	}
}
function clearForm(form) {
	for(var i=0; i<form.elements.length; ++i) {
		clearElement(form.elements[i]);
	}
}
function clearElement(element) {
	switch(element.type) {
		case "hidden":
			case "submit":
			case "reset":
			case "button":
			case "image":
			return;
		case "file":
			return;
		case "text":
			case "password":
			case "textarea":
			element.value = "";
		return;
		case "checkbox":
			case "radio":
			element.checked = false;
		return;
		case "select-one":
			case "select-multiple":
			element.selectedIndex = 0;
		return;
		default:
	}
}
// --></script>
</head>
<body>
<div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
                <div class="container">
                        <a class="brand" href="<?php echo base_url();?>">HttpClient</a>
                </div>
        </div>
</div>
<div class="container">

<?php if(isset($error) && $error === true){?>
<div class="alert alert-error">
<button type="button" class="close" data-dismiss="alert">×</button>
<strong>Error!</strong> The Url you requested seems to be wrong or somehow not working :(
</div>
<?php }?>

<div class="row">
<div class="span12">
<center>
<form action="<?php echo base_url();?>" method="post" id="form" accept-charset="utf-8">
<div class="input-prepend input-append">
<span class="add-on">url</span>
<input class="span6" type="text" id="requestUrl" name="requestUrl" value="<?php if(!empty($post['requestUrl'])) echo $post['requestUrl'];?>" placeholder="http://">
<button class="btn btn-primary" type="submit">Request</button>
<button class="btn" onClick="clearFormAll()">Clear Fields</button>
</div>
</center>
</div>
</div>
<div class="row">
<div class="span2">
<h4>Method</h4>
<select name="method" class="span1">
<option value="POST"<?php if (!empty($post['method'])){ if ($post['method'] === 'POST') echo ' selected';}?>>POST</option>
<option value="GET"<?php if (!empty($post['method'])){ if ($post['method'] === 'GET') echo ' selected';}?>>GET</option>
</select>
<h4>Basic Auth</h4>
<input type="text" class="input-small" name="authUser" value="<?php if(!empty($post['authUser'])) echo $post['authUser'];?>" placeholder="User">
<input type="password" class="input-small" name="authPass" value="<?php if(!empty($post['authPass'])) echo $post['authPass'];?>" placeholder="Password">
</div>

<div class="span5">
<h4>Post Request Values</h4>
<?php 
for($i=0; $i<FORM_VALUE_COUNT; $i++){
    echo (!empty($post['requestKey'.$i]))? '<input type="text" id="requestKey'.$i.'" name="requestKey'.$i.'" placeholder="key" value="'.$post['requestKey'.$i].'">' : '<input type="text" id="requestKey'.$i.'" name="requestKey'.$i.'" placeholder="key">';
    echo (!empty($post['requestVal'.$i]))? '<input type="text" id="requestVal'.$i.'" name="requestVal'.$i.'" placeholder="value" value="'.$post['requestVal'.$i].'">' : '<input type="text" id="requestVal'.$i.'" name="requestVal'.$i.'" placeholder="value">';
}
?>
</div>

<div class="span5">
<h4>Custom Headers</h4>
<?php
for($i=0; $i<FORM_VALUE_COUNT; $i++){
    echo (!empty($post['headerKey'.$i]))? '<input type="text" id="headerKey'.$i.'" name="headerKey'.$i.'" placeholder="key" value="'.$post['headerKey'.$i].'">' : '<input type="text" id="headerKey'.$i.'" name="headerKey'.$i.'" placeholder="key">';
    echo (!empty($post['headerVal'.$i]))? '<input type="text" id="headerVal'.$i.'" name="headerVal'.$i.'" placeholder="value" value="'.$post['headerVal'.$i].'">' : '<input type="text" id="headerVal'.$i.'" name="headerVal'.$i.'" placeholder="value">';
}
?>
</div>
</div>
</form>
<hr/>

<?php if(!empty($response['responseHeader'])) { ?>
<div class="row">
<div class="span6">
<h4>Request Header</h4>
<?php echo implode("<br/>",$response['requestHeader']);?>
<hr/>
<?php if(!empty($response['symfonyProfiler'])) {?>
<a href="<?php echo $response['symfonyProfiler'];?>">Symfony Profiler</a>
<?php }?>
</div>

<div class="span6">
<h4>Response Header</h4>
<?php echo implode("</br>",$response['responseHeader']);?>
</div>
</div>

<div class="row">
<div class="span12">
<h4>Content</h4>
<?php echo $response['contents'];?>
</div>
</div>
<?php }?>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
