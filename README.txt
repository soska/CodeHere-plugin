CodeHere is a very simple plugin to embed highlighed sample code in your WordPress posts. Very useful if you blog about programming.

******
INSTALLATION:

Come on, if you have a programming blog, you already know how to install plugins.

******

USAGE:

Here's how it works: 

1. First, you need to have each snippet into it's own file.
2. Attach them to your WordPress post via the upload dialog in the WP post editor.
3. Use the [codehere] shortcode specifying the file you want to insert and the programming language. The syntax of the shortcode is like this:

	[codehere thefile.ext lang=language]
	
4. That's it.	

******

CUSTOMIZE:

The highlight part is done by CSS. So, you can pretty much modify every aspect of it just editing the code.css file. I could add 'themes' in a future version, so if you do a cool syntax theme feel free to send it to me.

******

NOTES:

Uploading unsafe files:

By default, WP won't let you upload files  with extensions that are considered 'unsafe' like .rb or .php … etc. That's not a problem, you can upload them with some other extension as long as you use the 'lang' parameter and the highlighting will work ok.

If you want to be able to upload whatever file you like, consider uncommenting the last line in codehere.php. That will hook the codehere_types function, where you can add support for any file type. I don't really know if this is unsafe but so it's up to you.


Highlighting Algorithm: 

The Highlighting was not programmed by me, I've just adapted this awesome Pear package: http://pear.php.net/package/Text_Highlighter to work whitout Pear. 

Have in mind, though, that it may be a bit heavy so I recommend using this plugin only in conjunction wit WpSuperCache or something similar. 

See? I'm washing my hands just in case your server melts.

