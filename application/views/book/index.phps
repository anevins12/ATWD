<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ATWD - Assignment</title>
	<?php $this->load->helper('assets_helper'); ?>
	<link rel="stylesheet" href="<?php echo css_url("style.css")?>" type="text/css" />
	<script>
	
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
<body id="<?php echo $id ?>" class="detail">
    <nav>
        <ul>
            <li>
                <a href="<?php echo site_url("books"); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo site_url("courses"); ?>">Courses</a>
            </li>
        </ul>
    </nav>
    
    <h1>Book Detail - Using HTML5 localStorage</h1>
    
    <div id="main">
		<div id="detail">
		
		</div>

		<div id="suggestions" >
			
		</div>
		
		<div id="borrow">
			<a>Borrow</a>
		</div>
		
		
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
				getBookDetail();
				
				getBookSuggestions();
				borrowBook();
        });

</script>
</html>