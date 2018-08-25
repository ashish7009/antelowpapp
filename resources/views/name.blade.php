<!DOCTYPE html>
<html>
<head>
	<title>Demo Page</title>
</head>
<body>
	<form action="{{ url('/influencer/name') }}" method="post" accept-charset="utf-8">
		<input type="name" name="name" id="name">
		 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<input type="submit" name="submit" value="Submit">
	</form>
</body>
</html>