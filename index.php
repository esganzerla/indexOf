<?php
	// Check the current path based on the query string or the current URL
	$path = (isset($_GET['path'])) ? $_GET['path'] : $_SERVER['REQUEST_URI'];
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Index of <?=$path;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			padding: 3%;
			background-color: #313335;
			color: rgba(255,255,255,0,6);
			text-transform: uppercase;
			word-spacing: .05em;
			letter-spacing: .05em;
			font-size: 140%;
			font-family: 'Muli', sans-serif;
			line-height: 1.9em;
		}
		a {
			color: rgba(255,255,255,.6);
			text-decoration: none;
		}
		a:hover {
			padding-bottom: .1em;
			border-bottom: 1px solid rgba(255,221,17,1);
		}
		h1 {
			overflow: auto;
			margin-bottom: 1em;
			padding-bottom: .5em;
			max-width: 100%;
			border-bottom: 1px solid #515355;
			color: rgba(255,221,17,.5);
		}
		h1 small {
			color: rgba(255,255,255,.4);
			font-weight: normal;
		}
		h1 { font-size: 34px; }
		h1 small { font-size: .4em; }
		ul {
			margin: 0;
			padding: 0 1em;
			list-style: none;
		}
		@media only screen and (max-device-width: 480px) {
			body {
				padding: 5%;
				line-height: 2.5em;
			}
			h1 {
				margin-bottom: .5em;
				line-height: .9em;
			}
			ul { padding: 0 .5em; }
		}
	</style>
</head>

<body>
	<h1><small>Index of </small><?=$path;?></h1>

	<ul>
		<?php
			/**
			* Scan the current directory for its content
			*/
			$files = scandir('.'. $path);

			foreach ($files as $file){
				/**
				* Will not show hidden files (files starting with . - dot)
				* Neighter will show the .. link in the root path
				*/
				if (!preg_match("/^\./", $file) && $file !== 'index.php' ) {
					// Just to avoid double bars on the root page
					$path = ($path !== '/') ? $path : '';

					// File path for the links and for the php functions
					$filePath = $path .'/'. $file;
					$systemFilePath = '.'. $filePath;

					// Check if is a dir or a file
					$isDir = is_dir($systemFilePath);

					// Check if dir has a file called index.php or index.html to redirect without scanning its inside
					if ($isDir && !(is_file($systemFilePath .'/index.php') || is_file($systemFilePath .'/index.html'))) {
						$linkPath = '?path='. $filePath;
					} else {
						$linkPath = $filePath;
					}

					// Set the filename for the current file - will add slash after the name on dirs
					$fileName = $isDir ? $file .'/' : $file; 

					// Print the link
					echo '<li><a href="'. $linkPath. '" title="'. $filePath .'">' . $fileName .'</a></li>';
				}
				/**
				* Will create link only for the returning links, not in the root directory
				*/
				else if ($file === '..' && $path !== '/') {
					// Remove the last part of the path
					$dirTree = explode('/', $path);
					array_pop($dirTree);
					$linkPath = implode('/', $dirTree);

					// Make sure the root directory has a slash to its page
					$linkPath = (!empty($linkPath)) ? $linkPath : '/';

					// Print the link
					echo '<li><a href="?path='. $linkPath .'" title="'. $linkPath .'">..</a></li>';
				}
			}
		?>
	</ul>
</body>
</html>