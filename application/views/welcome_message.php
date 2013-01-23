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

	div.col1,
	div.col2{
		float: left;
	}

	div.col1 {
		width: 600px
	}

	#results {
		width: 300px;
	}
	</style>
	<link rel="stylesheet" href="<?php echo base_url('application/a/css/style.css');?>" type="text/css" />

	<?php
	if ( isset( $json ) ) {
	?>
		<script type="text/javascript">
			var results = <?php echo $json ?>;
			var results = results.results.
			<?php
				switch ( $requested ) {

					case 'course':
						echo 'books';
						break;

					case 'detail':
						echo 'book';
						break;

					case 'suggestions':
						echo 'books';
						break;
					
				}	?>
			; 
		</script>
	<?php
	}

	?>
<!--	<script type="text/javascript" src="<?php //echo base_url('js/scripts.js');?>"></script>-->
	
</head>
<body>

<div id="container">

	<div class="col1">
		<div>
			<h2>Get Books By Course ID</h2>
			<?php 
				$this->load->helper('form');
				echo form_open("books/course", array('method' => 'get'));
			?>
				<label for="course_id">Course ID:</label>
				<input type="text" name="course_id" id="course_id" value="CC100"/>
				<label for="format">Format: <select name="format" id="format">
					<option>XML</option>
					<option>JSON</option>
				</select></label>
				<input type="submit" name="submit" id="submit"/>
			<?php
				echo form_close();
			?>
			
		</div>
		<div>
			<h2>Get Book Details</h2>
			<?php
				$this->load->helper('form');
				echo form_open('books/detail', array('method' => 'get'));
			?>
				<label for="book_id">Book ID:</label>
				<input type="text" name="book_id" id="book_id" value="483"/>
				<label for="format">Format: <select name="format" id="format">
					<option>XML</option>
					<option>JSON</option>
				</select></label>
				<input type="submit" name="submit" id="submit"/>
			<?php
			echo form_close();
			?>
		</div>
		<div>
			<h2>Update Borrowing Data</h2>
			<?php 
				$this->load->helper('form');
				echo form_open('books/borrow');
			?>
				<label for="book_id">Book ID:</label>
				<input type="text" name="book_id" id="book_id" value="51390"/>
				<input type="hidden" name="course_id" id="course_id" value="CC100"/>
				<label for="format">Format: <select name="format" id="format">
					<option>XML</option>
				</select></label>
				<input type="submit" name="submit" id="submit"/>
			<?php
				echo form_close();
			?>
		</div>
		<div>
			<h2>Book Suggestions</h2>
			<?php
				$this->load->helper('form');
				echo form_open('books/suggestions', array('method' => 'get'));
			?>
				<label for="book_id">Book ID:</label>
				<input type="text" name="suggestion_id" id="suggestion_id" value="51390"/>
				<input type="hidden" name="course_id" id="course_id" value="CC100"/>
				<label for="format">Format: <select name="format" id="format">
					<option>XML</option>
					<option>JSON</option>
				</select></label>
				<input type="submit" name="submit" id="submit"/>
			<?php
				echo form_close();
			?>
		</div>

		<div>
			<h2>Browse Courses</h2>
			<?php
				$this->load->helper('form');
				echo form_open("books/course", array('method' => 'get'));
			?>

				<input type="hidden" name="format" value="XML" />

				<label for="book_id">Courses:</label>
				<select name="course_id" id="format">
					<?php  foreach( $courses as $course ) { ?>
					<option value="<?php echo $course['id'] ?>"><?php echo $course['name'] ?></option>
					<?php }?>
				</select>
				
				<input type="submit" name="submit" id="submit"/>
			<?php
				echo form_close();
			?>
		</div>
	</div>
	<div class="col2">
		<div id="results">
			<?php
			extract( $_POST );
			extract( $_GET );
			if ( isset( $submit ) && isset( $xml ) ) {
				print( $xml );
			}
			?>
		</div>
		<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>

	</div>

</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<?php



if ( isset( $json ) && $json ) {
	?>
		<script type="text/javascript">

			jQuery(document).ready(function($){

				$("#results").append("<ul></ul>");
				
				//suggestions output
				if ( results.suggestions ) {

					//that can be looped over
					if ( results.suggestions.length > 1 ) {
						$.each(results.suggestions, function(){

							var isbn = "<h4> ISBN: " + this.isbn + "</h4>";
							$("#results ul").append("<li>" + isbn + "</li>");

						});
					}
					//that can't be looped over
					else { 
						var isbn = "<h4> Isbn: " + this.isbn + "</h4>";
						$("#results ul").append("<li>" + isbn + "</li>");

					}

				}

				//for all other retrieval of information, that can be looped over
				else if ( results.length > 1 ) {
					$.each(results, function(){

						var title          = "<h3>" + this.title + "</h3>";
						var isbn           = "<h4> ISBN: " + this.isbn + "</h4>";
						var borrowed_count = "<h4> Borrowed count: " + this.borrowedcount + "</h4>";

						$("#results ul").append("<li><hgroup>" + title + "\n" + isbn + "\n" + borrowed_count + "</hgroup></li>");

					});
				}

				//can't be looped over
				else {
					
					var title          = "<h3>" + results.title + "</h3>";
					var isbn           = "<h4> ISBN: " + results.isbn + "</h4>";
					var borrowed_count = "<h4> Borrowed count: " + results.borrowedcount + "</h4>";

					$("#results ul").append("<li><hgroup>" + title + "\n" + isbn + "\n" + borrowed_count + "</hgroup></li>");
				
				}


			});
			
		</script>
	<?php
}
?>
</body>
</html>