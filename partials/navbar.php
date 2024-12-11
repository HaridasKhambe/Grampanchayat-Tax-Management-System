<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script src="https://kit.fontawesome.com/14df1a65dd.js" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

	<script>
		$(document).ready(function(){
			$('#icon').click(function(){
				$('ul').toggleClass('show');
			});
		});
	</script>

	<!-- <script type="text/javascript"> 
		document.addEventListener("contextmenu", function(event)
		{
			event.preventDefault();
			alert("Right Click is Disabled!")
		});

	</script> -->

	<script type="text/javascript">
	
		document.addEventListener('DOMContentLoaded', function() {
    	const activePage = window.location.pathname;
    	console.log(activePage);
    	const navLinks = document.querySelectorAll('nav a').forEach(link =>{
    		if(link.href.includes(`${activePage}`)){
    			link.classList.add('active');
    		}
    	});
    });    		
	
	</script>

<style type="text/css">

:root
{
	/*--bgcolor-1:#34495e ;
	--bgcolor-2:#2f3640;
	--ahover:#3498db;*/

	--bgcolor-1:#2a3d66;
	--bgcolor-2:#4a6e99  ;
	--ahover:#3498db;
}

*{
	padding: 0;
	margin: 0;
	list-style-type: none;

}

body{
	font-family:montserrat;
/*	background: url(images/back1.jpg) no-repeat;*/
	background-size:cover;
	height:calc(100vh - 80px);
	background-color:#ffffff;
	font:#333333;
	
}

nav{
	position:fixed;
	height: 80px;
	width:100%;
	background:var(--bgcolor-1);
	z-index:9999;
	
}

label.logo
{
	font-size:35px;
	font-weight:bold;
	color: #ffffff;
	padding: 0 100px;
	line-height:80px;
	
}

nav ul
{
	float: right;
	margin-right:40px;
	
}

nav li 
{
	display: inline-block;
	margin:0 8px;
	line-height:80px;
	
	

}
nav a{
	color:#fafafa;
	font-size: 18px;
	text-transform: uppercase;
	border: 1px solid transparent;
	padding:7px 10px;
	border-radius:3px;
	text-decoration:none;

}

 a:hover{
	border: 1px solid white;
	background-color:black;
	color:white;
	transition:.5s;
}

li a:is(:link, :active, :visited).active{
	border: 1px solid white;
	background-color:black;
	color:white;
	transition:.5s;
}
nav #icon{
	color: white;
	font-size: 30px;
	line-height:80px;
	float: right;
	margin-right:40px;
	cursor: pointer;
	display: none;
}

@media (max-width: 1048px)
{
	label.logo
	{
		font-size:32px;
		padding-left:60px;
	}

	nav ul{
		margin-right:20px;
		left:-100%;


	}

	nav a 
	{
		font-size:17px;
	}
}

@media (max-width: 909px)
{
	nav #icon{
		display: block;
	}

	nav ul{
		position:fixed;
		width:100%;
		height: 100vh;
		background:var(--bgcolor-2);
		top:80px;
		left:-100%;
		text-align:center;
		transition: all .5s;
	}

	nav li{
		display: block;
		margin:50px 0;
		line-height:30px ;
	}

	nav a{
		font-size:20px;

	}

	a.active, a:hover
	{
		border: none;
		color:var(--ahover);
	}

	nav ul.show{
		left:0;
	}
}


</style>
</head>
<body>

	<nav>
		<label class="logo">GP KARDE</label>

		<ul>
		<li><a href="dashboard.php">Dashboard</a></li>
		<li><a href="manage.php">Manage</a></li>
		<li><a href="track.php">Track</a></li>
		<li><a href="Profile.php">Profile</a></li>
		<li><a href="partials/logout.php">Logout</a></li>
		<!-- <li><a href="ad-notice.php">Notice</a></li> -->

		</ul>
		<label id="icon">
			<i class="fas fa-bars"></i>
		</label>
	</nav>
	
	
</body>
</html>