<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<?php $this->load->helper('assets_helper'); ?>
	<link rel="stylesheet" href="<?php echo css_url('style.css');?>" type="text/css" />

	<?php
	extract( $_POST );
	extract( $_GET );
	
	if ( $format == 'JSON' ) { 
	?>
		<script type="text/javascript">
			var results = <?php echo $client ?>;
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
				echo form_open('books/suggestions', array('method' => 'get'));
			?>
				<label for="book_id">Book ID:</label>
				<input type="text" name="suggestion_id" id="suggestion_id" value="51390"/>
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
				echo form_open("books/course", array('method' => 'get'));
			?>
				<input type="hidden" name="format" value="XML" />

				<label for="book_id">Courses:</label>
				<select name="course_id" id="format">
					<?php foreach( $courses as $course ) { ?>
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
			<form>
				<h2>Service</h2>
				<textarea readonly="readonly">
				<?php
					print $service;
				?>
				</textarea>
				<h2>Client</h2>
				<?php 
					if ( $format != 'JSON' )	print $client['client'];
				?>
			</form>
		</div>
		<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>

	</div>

</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo js_url('scripts.js') ?>"></script>
<?php

if ( $format == 'JSON' ) { 
	?>
		<script type="text/javascript">

			jQuery(document).ready(function($){
				printJSON();
			});
			
		</script>
	<?php
}
?>
</body>
</html>