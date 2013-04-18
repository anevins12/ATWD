<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ATWD - Assignment</title>
	<?php $this->load->helper('assets_helper'); ?>
	<link rel="stylesheet" href="<?php echo css_url("style.css")?>" type="text/css" />
	<script type="text/javascript">
		var results = <?php echo $courses?>;
		
		var localstorageCheck;
		
		//Check if localstorage (or session storage) is available
		// http://www.w3schools.com/html/html5_webstorage.asp
		if(typeof(Storage)!=="undefined") {
			window.localstorageCheck = true;
		}
		else {
			window.localstorageCheck = false;
		}
		
	</script>
</head>
<body class="courses">
    <nav>
        <ul>
            <li>
                <a href="<?php echo site_url("books"); ?>">Home</a>
            </li>
        </ul>
    </nav>
    
    <h1>All Courses - Using HTML5 localStorage</h1>
    <h2>Link shows books per course</h2>
	<p>Note: Please click on 1 course item. A bug is caused by a hash tag when you click on multiple courses, which prevents you from clicking on any books. <br />
	If this bug occurs, please refersh the page or remove all characters starting from the trailing hashtag</p>
    
    <div id="main">
     
    </div>
    
	<div id="loading">
		<img src="<?php echo img_url("ajax-loader.gif"); ?>" alt="Loading" />
	</div>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo js_url('scripts.js') ?>"></script>

</body>
	<script type="text/javascript">
        jQuery(document).ready(function($){
				ajaxLoader();
                courses();
                booksForCourse();
        });

</script>
</html>