<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ATWD - Assignment</title>
	<?php $this->load->helper('assets_helper'); ?>
	<link rel="stylesheet" href="<?php echo css_url('style.css');?>" type="text/css" />

	<?php
	extract( $_POST );
	extract( $_GET );

	if ( isset( $format ) ) {

		if ( $format == 'JSON' ) { 
		?>
			<script type="text/javascript">
				var results = <?php echo $service ?>;
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

	}

	?>
	
</head>
<body class="client">

<div>
	<h1>More complex and thorough example of service calls through client interface</h1>
	<p>You can browse the dataset in a more realistic output. I tried to replicate a bookstore interface.</p>
	<h2>Browse dataset</h2>

	<p>Implemented in AJAX<p>
	<a id="complex" href="<?php echo site_url("courses"); ?>" title="Browse all courses"> Browse all courses </a>
</div>

<h1>Quick example of service calls through client interface</h1>
<p>For the 'Client' section, the returned XML is transformed into HTML using XSLT</p>
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
				<label for="course_format">Format:</label>
				<select name="format" id="course_format">
					<option>XML</option>
					<option>JSON</option>
				</select>
				<input type="submit" name="submit" id="course_submit"/>
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
				<label for="book_format">Format:</label> 
				<select name="format" id="book_format">
					<option>XML</option>
					<option>JSON</option>
				</select>
				<input type="submit" name="submit" id="detail_submit"/>
			<?php
			echo form_close();
			?>
		</div>
		<div>
			<h2>Update Borrowing Data</h2>
			<?php 
				echo form_open('books/borrow');
			?>
				<label for="book_borrowing_id">Book ID:</label>
				<input type="text" name="book_id" id="book_borrowing_id" value="51390" />
				
				<input type="hidden" name="course_id" id="book_detail_course_id" value="CC100"/>		
				
				<!-- Sorry, I can't use a label for the Format: XML string because in HTML5, you cannot assign labels to hidden input types.
					 Well... You can but your HTML5 will be invalid to W3C standards -->
				Format: XML
				<input id="asshole" type="hidden" name="borrow_format" value="XML"  />
				
				<input type="submit" name="submit" id="borrow_submit" />
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
				<input type="text" name="book_id" id="suggestion_id" value="51390"/>
				<label for="suggestions_format">Format: 
					<select name="format" id="suggestions_format">
						<option>XML</option>
						<option>JSON</option>
					</select>
				</label>
				<input type="submit" name="submit" id="suggestions_submit"/>
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
					if ( $format != 'JSON' )    print $client['client'];                                        
				?>
			</form>
		</div>
	</div>
	
</div>

		<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>

		
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo js_url('scripts.js') ?>"></script>
<?php

if ( isset( $format ) ) {

	if ( $format == 'JSON' ) {
		?>
			<script type="text/javascript">

				jQuery(document).ready(function($){
					printJSON();
				});

			</script>
		<?php
	}

}
?>
</body>
</html>