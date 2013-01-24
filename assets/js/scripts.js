function printJSON() { 
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

}