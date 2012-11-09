<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">

	<?php
		extract( $_GET );
		extract( $_POST );
		
		if ( isset ( $submit ) ) {
			var_dump( $output );
		}
		
	?>

	<div id="body">
		<p>You'll need to clear the URI to get rid of index.php/etcetera, otherwise you can't switch between form submissions</p>
		<div>
			<h2>Get Books By Course ID</h2>
			<form action="index.php/books/course/" method="GET">
				<label for="course_id">Course ID:</label>
				<input type="text" name="course_id" id="course_id" value="CC100"/>
				<select name="format">
					<option>XML</option>
					<option>JSON</option>
				</select>
				<input type="submit" name="submit" id="submit"/>
			</form>
		</div>
		<div>
			<h2>Get Book Details</h2>
			<form action="index.php/books/detail/" method="GET">
				<label for="book_id">Book ID:</label>
				<input type="text" name="book_id" id="book_id" value="483"/>
				<select name="format">
					<option>XML</option>
					<option>JSON</option>
				</select>
				<input type="submit" name="submit" id="submit"/>
			</form>
		</div>
		<div>
			<h2>Update Borrowing Data</h2>
			<form action="index.php/books/borrow/" method="POST">
				<label for="book_id">Book ID:</label>
				<input type="text" name="book_id" id="book_id" value="51390"/>
				<input type="hidden" name="course_id" id="course_id" value="CC100"/>
				<select name="format">
					<option>XML</option>
					<option>JSON</option>
				</select>
				<input type="submit" name="submit" id="submit"/>
			</form>
		</div>
		<div>
			<h2>Book Suggestions</h2>
			<form action="index.php/books/suggestions/" method="GET">
				<label for="book_id">Book ID:</label>
				<input type="text" name="suggestion_id" id="suggestion_id" value="51390"/>
				<input type="hidden" name="course_id" id="course_id" value="CC100"/>
				<select name="format">
					<option>XML</option>
					<option>JSON</option>
				</select>
				<input type="submit" name="submit" id="submit"/>
			</form>
		</div>

		<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>

	</div>

</div>

</body>
</html>