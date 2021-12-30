<!doctype html>
<html lang="sv">
<head>
	<link href="style.css" media="screen" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Julius+Sans+One&display=swap" rel="stylesheet">
	<meta charset="utf-8">
	<title>Lego databas</title>
</head>
<body>
<img alt="" src="images/logo.gif" class="logo">

<div class="all">

	<div class="meny">
		<nav>
			<a href="omlego.php">Om lego</a>
			<a href="index.php" class="active">Hem</a>
			<a href="omoss.php">Kontakt</a>
		</nav>
	</div>
	<div class="container">
	<form action="result.php?offset=0" method="POST" class="search">
		<input type="search" name="search_key" id="search_key" placeholder="Sök" required>
		<button type="submit" class="search-btn"><img alt="search" src="images/search.svg"></button>
		<img alt="search" id="info-img" src="images/question.svg">
		<div id="help"><p>Sök på namnet på en legobit!</p></div>
	</form>
	
	<p class="disclaimer">LEGO® is a trademark of the LEGO Group of companies which does not sponsor,<br> authorize or endorse this program.
LEGO and the LEGO logo are trademarks and/or copyrights of the LEGO Group. <br><br> ©2018 The LEGO Group. All rights reserved</p>
	</div>
	
</div>
</body>

</html>