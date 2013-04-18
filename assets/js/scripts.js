/**
* The Coursesmodel holds all queries that occur on the courses.xml file
*
*
* @author_name  Andrew Nevins
* @author_no    09019549 
* @link         http://isa.cems.uwe.ac.uk/assets/js/scripts.js
*/

/**
 * Used by the Books default controller for accessing all (local) APIs 
 *
 * @return	HTML
 */
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

/**
 * Get all the courses from course.xml
 *
 * @return	HTML
 */
function courses() {
    
    $("#main").append("<ul></ul>");
   		
    if ( results ) {
       
	   //check if localstorage boolean variable is set to true
	   if ( window.localstorageCheck ) {
	   
		   //saving courses to localStorage
		   localStorage['courses'] = JSON.stringify(results);
		   results = JSON.parse(localStorage['courses']);
		   
	   }
       
	   $.each(results, function(){
		  
            var title =  this.id + ": " + this.name;
            $("#main ul").append("<li class=" + this.id + "> <a href='#' class='getBooks'> "+ title + "</a></li>");
            
        });
        
    }
   
}

/**
 * Get the books for a course, by the course ID
 *
 * @return	HTML
 */
function booksForCourse() {

    $("#main .getBooks").click(function(e){
        	
        var $this=$(this); 
        var msg = $this.parent().attr('class');
		var data;
		
		if ( $this.children('.books').length == 0 ) {
			   
			//use the function 'course' API call
			$.get('books/course?course_id=' + msg + '&format=JSON', {name: msg}, function(data) {
			   
			   //if the course has books, ifJsonString will return true 
				if ( isJsonString(data) ) {
					data = jQuery.parseJSON(data);
					
					var books = data.results.books;
					var current_directory = location.href;
					
					$this.append('<ul></ul>');
					
					$.each(books, function(k,v){
				  
						var title =  v.title;
						$this.children('ul').addClass('books').append("<li class=" + this.id + "> <a href='" + current_directory + "/../book/detail/" + this.id + "' class='getBook'> "+ title + "</a></li>");
						
					});	
					
				}
				//otherwise the course has no books and output a suitable message
				else {
					
					$this.append('<ul></ul>');					
					$this.children('ul').addClass('books').append("<li class='error'>No books found</li>");
					
					
				}
				
			});
			
			//remove trailing slash
			return false;
		}
	});	
    
}

/**
 * Get all detail from books.xml and use external APIs to return a book's detail
 *
 * @return	HTML
 */
function getBookDetail() {

	var $this=$(this); 
	
	//grab the book id from the body attribute
	var msg = $('body').attr('id');
	var data;
	var bookDetails;
	
	//check if localstorage bookDetails item exists 	   
	if ( localStorage.getItem("bookDetails") !== null ){ 
	
		var book = jQuery.parseJSON(localStorage['bookDetails']);
		
		//check if a new book is requested
		if ( book.results.book.id !== msg ) {

			//use the function 'detail' API call
			$.get('/books/detail?book_id=' + msg + '&format=JSON', {name: msg}, function(data) {

			   data = jQuery.parseJSON(data);
			   
			   //saving book's details to localStorage
			   if (window.localstorageCheck) {
				
				  //saving course books to localStorage
				  localStorage['bookDetails'] = JSON.stringify(data);
				  data = JSON.parse(localStorage['bookDetails']);
				   
			   }		
			   
			   formatDetails(data);
						 
			   //redeclaration of book_isbn variable, because is needed outside of formatDetails function
			   var book_isbn = data.results.book.isbn;
			   
				//attempting to use WorldCat's API to get more of the book's details via ISBN
				//need to call through <script> tag, otherwise can't talk to their server because of the "Same-origin policy"
				//https://developer.mozilla.org/en-US/docs/JavaScript/Same_origin_policy_for_JavaScript?redirectlocale=en-US&redirectslug=Same_origin_policy_for_JavaScript
				//
				
				//Attempting to use jsonp to parse the json from the script tag into a callback method
				//	var script = document.createElement( 'script' );
				//	script.type = 'text/javascript';
				//	script.src = ' http://xisbn.worldcat.org/webservices/xid/isbn/' + book_isbn + '?method=getMetadata&format=json&fl=*&jsonp=processWorldCatAPI';			
				//	$("body").append( script );
			
				//Found it could be done a lot easier using the getJSON method, then just specifying a question mark on the callback parameter
				//Which tells jQuery to return the callback immediately
				//http://stackoverflow.com/questions/5943630/basic-example-of-using-ajax-with-jsonp
				
				getAdditionalBookInfo(book_isbn);
				
			});	
			
		}
		
		//if new book is not requested, use localstorage		
		else { 
		
			data = JSON.parse(localStorage['bookDetails']); 
			formatDetails(data);
			
			var book_isbn = data.results.book.isbn;
			getAdditionalBookInfo(book_isbn);
		}
	}	   
	
	//there is no localstorage available. 
	else { 
	
		//use the function 'detail' API call
		$.get('/books/detail?book_id=' + msg + '&format=JSON', {name: msg}, function(data) {
			 data = jQuery.parseJSON(data);
			 formatDetails(data);
			 
			 var book_isbn = data.results.book.isbn;
			 getAdditionalBookInfo(book_isbn);
		});

	}
	
	function formatDetails(data) {
		  
		   var book = data.results.book;
		   var book_id = book.id;
		   var book_title = book.title;
		   var book_isbn = book.isbn;
		   var book_borrowedcount = book.borrowedcount; 
		   
		   //get the book cover in a medium size using the OpenLibrary API 
		   //http://openlibrary.org/dev/docs/api/covers
		   //note: some covers are not found
		   var book_image = 'http://covers.openlibrary.org/b/isbn/' + book_isbn + '-M.jpg';
		   //I would check if the image exists by binding an error function with the image element in a selector,
		   //But book_image will always hold an image because the API returns a 1px by 1px image if no image is recognised.
		   //This means I can't differentiate an image that isn't found.
		
		   $('#detail').append('<div></div>');
		   $('#detail div').append('<h2> Title: ' + book_title + '</h2>')
					 .append('<h3>ID: ' + book_id + '</h3>')
					 .append('<h3>ISBN: ' + book_isbn + '</h3>')
					 .append('<h3>Borrowed count: ' + book_borrowedcount + '</h3>')
					 .append('<img src="' + book_image + '" alt="' + book_title + '" />');
	}
	
	function getAdditionalBookInfo(book_isbn) {
	
		$.getJSON("http://xisbn.worldcat.org/webservices/xid/isbn/" + book_isbn + "?method=getMetadata&format=json&fl=*&callback=?", function(result){
			//response data are now in the result variable
			$('#detail div').append('<div class="additional"></div>');
			$('#detail div.additional').append('<h2>Additional Details</h2>').append('<h3>Using WorldCat API</h3>');
			$('#detail .additional').append('<ul></ul>');
			
			//check if there is information from WorldCat on the book
			if ( result.stat !== 'invalidId' ) {
			 for (var i=0, len=result.list.length; i < len; i++) {
				
				//Adding some additional information. I would normally loop over all of them automatically,
				//but there were too many fields I didn't want.				
				var detail = result.list[i];
				
				//check whether the edition exists and if it doesn't, use '1st ed.'
				if ( !detail.ed ) {
					detail.ed = '1st ed.';
				}
				
				$('#detail .additional ul')
					.append('<li>URL: <a href="' + detail.url + '" title="URL">' + detail.url + '</li>')
					.append('<li>Publisher: ' + detail.publisher + '</li>')							
					.append('<li>City published: ' + detail.city + '</li>')
					.append('<li>Language: ' + detail.lang + '</li>')
					.append('<li>Author: ' + detail.author + '</li>')
					.append('<li>Edition: ' + detail.ed + '</li>');
					
			 }
			}
			//Log the error & display an appropriate message to the client
			else {
				console.log(result);
				$('#detail .additional ul')
					.append('<li>No additional details found</li>');
			}
		});	
	
	}

}

/**
 * Get the suggestions for a singular book
 *
 * @return	HTML
 */
function getBookSuggestions() {

	var $this=$(this); 
	var msg = $('body').attr('id');
	
	$('#suggestions').append('<h2>Suggestions</h2>').append('<ul></ul>');
	
	//use the function 'suggestions' API call
	$.get('/books/suggestions?book_id=' + msg + '&format=JSON', {name: msg}, function(data) {
		
		if (isJsonString(data)) {
			var data = jQuery.parseJSON(data);
			var suggestions = data.results.books.suggestions;
			
			$.each(suggestions, function(k,v){
			
				$.get('/books/detail?book_id=' + v.item + '&format=JSON', {name: msg}, function(data) {
					
					//check if each item can be found in books.xml
					if (isJsonString(data)) {
					
						var data = jQuery.parseJSON(data);
						var data = data.results.book;
						var title = data.title;
						var id = data.id;
						$('#suggestions').children('ul').append("<li class='" + id + "'> <a href='../detail/" + id + "?format=JSON'>" + title + "</a></li>");
					
					}
					else {
						//for debugging - usually when a suggestion's ID cannot be matched in books.xml
						console.log(data);
					}
					
				});
					
			});
		
		}
		else {
			$('#suggestions').children('ul').append("<li>No suggestions found</li>");
		}
		
	});

}

/**
 * Update the book's borrowed count by one
 *
 * @return	HTML
 */
function borrowBook() {

	var $this=$(this); 
	var msg = $('body').attr('id');	
	
	//check if localstorage bookDetails item exists 	   
	if ( localStorage.getItem("bookDetails") !== null ){ 
	
		var bookDetailsLocal = jQuery.parseJSON(localStorage['bookDetails']);
		
		$('#borrow a').click(function() { 
		
			//use the function 'books' API call
			$.post('/books/borrow/', {book_id: msg}, function(xml) {	   
			   
			   //update the DOM (to see new borrowed count)
			   $('#detail div').remove();
			   				
			   //need to provide an updated localstorage item with the updated borrowedcount
			   var jsonData = $.xml2json(xml); 
			   var borrowedcount = jsonData.book.borrowedcount;
			   
			   bookDetailsLocal.results.book.borrowedcount = borrowedcount;
			   
			   // Rewrite the bookDetails localstorage item with the updated borrowedcount
			   localStorage.setItem('bookDetails', JSON.stringify(bookDetailsLocal));

			   //get the book details again
			   getBookDetail();
			   
			});
		
		});
		
	}
	
	//if localstorage does not exist, just run the normal API with AJAX
	else {
	
		$('#borrow a').click(function() {
		
			//use the function 'books' API call
			$.post('/books/borrow/', {book_id: msg}, function(xml) {	   
			   
			   //update the DOM (to see new borrowed count)
			   $('#detail div').remove();
			   
			   //get the book details again
			   getBookDetail();
			   
			});
		
		});
		
	}
}

/**
 * Provide feedback through AJAX states
 *
 * @return	HTML
 */
function ajaxLoader() {

	//ajax image generated by http://www.ajaxload.info/
	
	$('#loading')
		.hide()
		.ajaxStart(function(){
			$(this).show();
			$('#main').animate( { "opacity" : .3 }, 250 );
		})
		.ajaxStop(function() {
			$(this).hide();
			$('#main').animate( { "opacity" : 1 }, 250 );
			
			//if there are no suggestions at all
			$suggestions_html = $('#suggestions ul li');
			if ($suggestions_html.length < 1) {
				$('#suggestions').append('<p>No suggestions found</p>');
			}
		})
	;
	
}

/**
 * Check if string is json format
 *
 * @param   STRING
 * @return	BOOLEAN
 */
//http://stackoverflow.com/questions/3710204/how-to-check-if-a-string-is-a-valid-json-string-in-javascript-without-using-try
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

//convert XML string to JSON 
//http://jquery-xml2json-plugin.googlecode.com/svn/trunk/jquery.xml2json.js
/*
 ### jQuery XML to JSON Plugin v1.2 - 2013-02-18 ###
 * http://www.fyneworks.com/ - diego@fyneworks.com
	* Licensed under http://en.wikipedia.org/wiki/MIT_License
 ###
 Website: http://www.fyneworks.com/jquery/xml-to-json/
*//*
 # INSPIRED BY: http://www.terracoder.com/
           AND: http://www.thomasfrank.se/xml_to_json.html
											AND: http://www.kawa.net/works/js/xml/objtree-e.html
*//*
 This simple script converts XML (document of code) into a JSON object. It is the combination of 2
 'xml to json' great parsers (see below) which allows for both 'simple' and 'extended' parsing modes.
*/
// Avoid collisions
;if(window.jQuery) (function($){
 
 // Add function to jQuery namespace
 $.extend({
  
  // converts xml documents and xml text to json object
  xml2json: function(xml, extended) {
   if(!xml) return {}; // quick fail
   
   //### PARSER LIBRARY
   // Core function
   function parseXML(node, simple){
    if(!node) return null;
    var txt = '', obj = null, att = null;
    var nt = node.nodeType, nn = jsVar(node.localName || node.nodeName);
    var nv = node.text || node.nodeValue || '';
    /*DBG*/ //if(window.console) console.log(['x2j',nn,nt,nv.length+' bytes']);
    if(node.childNodes){
     if(node.childNodes.length>0){
      /*DBG*/ //if(window.console) console.log(['x2j',nn,'CHILDREN',node.childNodes]);
      $.each(node.childNodes, function(n,cn){
       var cnt = cn.nodeType, cnn = jsVar(cn.localName || cn.nodeName);
       var cnv = cn.text || cn.nodeValue || '';
       /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>a',cnn,cnt,cnv]);
       if(cnt == 8){
        /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>b',cnn,'COMMENT (ignore)']);
        return; // ignore comment node
       }
       else if(cnt == 3 || cnt == 4 || !cnn){
        // ignore white-space in between tags
        if(cnv.match(/^\s+$/)){
         /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>c',cnn,'WHITE-SPACE (ignore)']);
         return;
        };
        /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>d',cnn,'TEXT']);
        txt += cnv.replace(/^\s+/,'').replace(/\s+$/,'');
								// make sure we ditch trailing spaces from markup
       }
       else{
        /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>e',cnn,'OBJECT']);
        obj = obj || {};
        if(obj[cnn]){
         /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>f',cnn,'ARRAY']);
         
									// http://forum.jquery.com/topic/jquery-jquery-xml2json-problems-when-siblings-of-the-same-tagname-only-have-a-textnode-as-a-child
									if(!obj[cnn].length) obj[cnn] = myArr(obj[cnn]);
									obj[cnn] = myArr(obj[cnn]);
         
									obj[cnn][ obj[cnn].length ] = parseXML(cn, true/* simple */);
         obj[cnn].length = obj[cnn].length;
        }
        else{
         /*DBG*/ //if(window.console) console.log(['x2j',nn,'node>g',cnn,'dig deeper...']);
         obj[cnn] = parseXML(cn);
        };
       };
      });
     };//node.childNodes.length>0
    };//node.childNodes
    if(node.attributes){
     if(node.attributes.length>0){
      /*DBG*/ //if(window.console) console.log(['x2j',nn,'ATTRIBUTES',node.attributes])
      att = {}; obj = obj || {};
      $.each(node.attributes, function(a,at){
       var atn = jsVar(at.name), atv = at.value;
       att[atn] = atv;
       if(obj[atn]){
        /*DBG*/ //if(window.console) console.log(['x2j',nn,'attr>',atn,'ARRAY']);
        
								// http://forum.jquery.com/topic/jquery-jquery-xml2json-problems-when-siblings-of-the-same-tagname-only-have-a-textnode-as-a-child
								//if(!obj[atn].length) obj[atn] = myArr(obj[atn]);//[ obj[ atn ] ];
        obj[cnn] = myArr(obj[cnn]);
								
								obj[atn][ obj[atn].length ] = atv;
        obj[atn].length = obj[atn].length;
       }
       else{
        /*DBG*/ //if(window.console) console.log(['x2j',nn,'attr>',atn,'TEXT']);
        obj[atn] = atv;
       };
      });
      //obj['attributes'] = att;
     };//node.attributes.length>0
    };//node.attributes
    if(obj){
     obj = $.extend( (txt!='' ? new String(txt) : {}),/* {text:txt},*/ obj || {}/*, att || {}*/);
     txt = (obj.text) ? (typeof(obj.text)=='object' ? obj.text : [obj.text || '']).concat([txt]) : txt;
     if(txt) obj.text = txt;
     txt = '';
    };
    var out = obj || txt;
    //console.log([extended, simple, out]);
    if(extended){
     if(txt) out = {};//new String(out);
     txt = out.text || txt || '';
     if(txt) out.text = txt;
     if(!simple) out = myArr(out);
    };
    return out;
   };// parseXML
   // Core Function End
   // Utility functions
   var jsVar = function(s){ return String(s || '').replace(/-/g,"_"); };
   
			// NEW isNum function: 01/09/2010
			// Thanks to Emile Grau, GigaTecnologies S.L., www.gigatransfer.com, www.mygigamail.com
			function isNum(s){
				// based on utility function isNum from xml2json plugin (http://www.fyneworks.com/ - diego@fyneworks.com)
				// few bugs corrected from original function :
				// - syntax error : regexp.test(string) instead of string.test(reg)
				// - regexp modified to accept  comma as decimal mark (latin syntax : 25,24 )
				// - regexp modified to reject if no number before decimal mark  : ".7" is not accepted
				// - string is "trimmed", allowing to accept space at the beginning and end of string
				var regexp=/^((-)?([0-9]+)(([\.\,]{0,1})([0-9]+))?$)/
				return (typeof s == "number") || regexp.test(String((s && typeof s == "string") ? jQuery.trim(s) : ''));
			};
			// OLD isNum function: (for reference only)
			//var isNum = function(s){ return (typeof s == "number") || String((s && typeof s == "string") ? s : '').test(/^((-)?([0-9]*)((\.{0,1})([0-9]+))?$)/); };
																
   var myArr = function(o){
    
				// http://forum.jquery.com/topic/jquery-jquery-xml2json-problems-when-siblings-of-the-same-tagname-only-have-a-textnode-as-a-child
				//if(!o.length) o = [ o ]; o.length=o.length;
    if(!$.isArray(o)) o = [ o ]; o.length=o.length;
				
				// here is where you can attach additional functionality, such as searching and sorting...
    return o;
   };
   // Utility functions End
   //### PARSER LIBRARY END
   
   // Convert plain text to xml
   if(typeof xml=='string') xml = $.text2xml(xml);
   
   // Quick fail if not xml (or if this is a node)
   if(!xml.nodeType) return;
   if(xml.nodeType == 3 || xml.nodeType == 4) return xml.nodeValue;
   
   // Find xml root node
   var root = (xml.nodeType == 9) ? xml.documentElement : xml;
   
   // Convert xml to json
   var out = parseXML(root, true /* simple */);
   
   // Clean-up memory
   xml = null; root = null;
   
   // Send output
   return out;
  },
  
  // Convert text to XML DOM
  text2xml: function(str) {
   // NOTE: I'd like to use jQuery for this, but jQuery makes all tags uppercase
   //return $(xml)[0];
   var out;
   try{
    var xml = ((!$.support.opacity && !$.support.style))?new ActiveXObject("Microsoft.XMLDOM"):new DOMParser();
    xml.async = false;
   }catch(e){ throw new Error("XML Parser could not be instantiated") };
   try{
    if((!$.support.opacity && !$.support.style)) out = (xml.loadXML(str))?xml:false;
    else out = xml.parseFromString(str, "text/xml");
   }catch(e){ throw new Error("Error parsing XML string") };
   return out;
  }
		
 }); // extend $

})(jQuery);

/* End of file script.js */
/* Location: ./application/js/script.js */