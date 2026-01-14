<?php
define("WORDING_LOGOUT", "Log out");
define("MESSAGE_LOGGED_OUT", "You have been logged out.");

define("MESSAGE_COOKIE_INVALID", "Invalid cookie");
define("MESSAGE_DATABASE_ERROR", "Database connection problem.");

define("MESSAGE_EXPERIMENT_EMPTY;", "Experiment field was empty");
define("MESSAGE_PASSWORD_EMPTY", "Password field was empty");
define('LANDING_PAGE', '
	<h1 id="openphodasev">OpenPhoda-SeV</h1>

	<p><strong>OpenPhoda-SeV now at <a href="https://openphoda.net">openphoda.net</a></strong>—just a domain change.</p>

	<p><strong>Everything works the same:</strong> tool, data, database all unchanged. artoo.info redirects here automatically. <em>Bookmark the new address!</em></p>	

	<p><a href="https://github.com/artoo-git/OpenPhoda-SeV">Open Source version of the Phoda-SeV</a> scale for Chronic Low Back Pain</p>

	<h2> Why developing an open source Phoda-Sev tool?</h2>
	<p>The University of Leuven releases a proprietary version of the Phoda-SeV in "Windows" format. I thought it may have been useful to create a version of the software which (provided that a browser and internet connection is available) is capable to:</p>

	<ul>
	<li>run on any possible machine at any time </li>

	<li>add data to a single, fast and reliable relational database</li>
	</ul>
	
	<h2> OpenPhoda-SeV is free to use on this website </h2>
	<p>I have installed a fully functioning version of OpenPhoda on this website and it is free to use. I cannot guarantee how long this instance will run but if you are concerned or if you want to know more about OpenPhoda drop me a message <a href="https://www.researchgate.net/profile/diego-vitali"> here </a></p>
	<h2 id="whatisopenphodasev">What is OpenPhoda-SeV?</h2>

	<p><a href="https://github.com/artoo-git/OpenPhoda-SeV">OpenPhoda-SeV</a> is a webapp for delivery of the electronic version of the PHODA (Photograph Series of Daily Activities), a diagnostic tool using which uses photographs to determine the threat value of different physical activities and movements. Exactly like <a href="https://ppw.kuleuven.be/ogp/labfacilities">Phoda-SeV</a> this tool offers a quick and simple way for therapists to create a fear hierarchy for patients that may be influenced by some degree of fear of movement. </p>

	<h2 id="minimumrequirement">Minimum Requirement</h2>

	<p>Any webserver running:</p>

	<ul>
	<li>Apache 2.4.0+ (or NGNIX)</li>

	<li>PHP 7.0.0+</li>

	<li>MySQL 5.7+ or mariaDB 10.3+</li>
	</ul>

	<h2 id="referencephoda">References </h2>
	<h3>PHODA</h3>
	<p>The original PHODA was developed by Maastricht University together with Zuyd University. Reference: </p>

	<blockquote>
	 <p><a href="https://books.google.co.uk/books/about/The_Photograph_series_of_daily_activitie.html?id=EcgsQwAACAAJ&redir_esc=y">Kugler, K., Wijn, J., Geilen, M., de Jong, J., &amp; Vlaeyen, J. W. S. (1999). The Photograph series of Daily Activities (PHODA). CD-rom version 1.0.</a></p>
	</blockquote>

	<h3 id="referencephodasev">Phoda-SeV</h3>

	<p><a href="https://ppw.kuleuven.be/ogp/labfacilities">Phoda-SeV</a> was developed by Maastricht University. If Phoda-SeV is used for research purposes resulting in a publication or presentation, we request you to use the following reference:</p>

	<blockquote>
	 <p><a href="https://www.jpain.org/article/S1526-5900(07)00729-8/abstract">Leeuw, M., Goossens, M. E. J. B., van Breukelen, G. J. P., Boersma, K., &amp; Vlaeyen, J. W. S. (2007). Measuring perceived harmfulness of physical activities in patients with chronic low back pain: the Photograph Series of Daily Activities - Short electronic Version. Journal of Pain, 8, 840-849.</a></p>
	</blockquote>');

?>
