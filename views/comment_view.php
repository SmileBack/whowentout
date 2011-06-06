<html>
<head>
	<title><?php print $title;?></title>
</head>

<body>
	<h1><?php print $heading;?></h1>
	
	<?php print_r($query); ?>
	
	<p><?php print "anchor('blog', 'Back to Blog')";?></p>
	
	<?php print form_open('blog/comment_insert');?>
	
	<?php print form_hidden('entry_id', $this->uri->segment(3));?>

	<!-- <p><input type="hidden" name="entry_id" value=<?php print $this->uri->segment(3); ?> /> --> 
	
	<p><textarea name="body" rows="10"></textarea></p>
	<p><input type="text" name="author" /></p>
	<p><input type="submit" value="Submit Comment" /><p/>
			
</body>

</html>