<html>
	<body style="margin: 20px;">
		<table>
			<tr>
				<td>Reported User ID: {{ $reported_user->userid }}
					<br>Reported User email: {{ $reported_user->email }}
				</td>
			</tr>
			<tr>
				<td>User who report this: 
					<br> ID: {{ $user->userid }}
					<br> Email: {{ $user->email }}</td>
			</tr>
		</table>
	</body>
</html>
