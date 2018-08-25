<html>
	<body style="margin: 20px;">
		<table>
			<tr>
				<td>Serie ID: {{ $seriesmedia->seriesmediaid }}
					<br>Serie title: {{ $seriesmedia->title }}
					<br>Serie url: {{ $seriesmedia->filepath }}</td>
			</tr>
			<tr>
				<td>User who report this content: 
					<br> ID: {{ $user->userid }}
					<br> Email: {{ $user->email }}</td>
			</tr>
			<tr>
				<td>Reason: {{ $reason }}</td>
			</tr>
		</table>
	</body>
</html>
