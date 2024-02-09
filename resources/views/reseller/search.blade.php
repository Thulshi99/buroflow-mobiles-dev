<form method="POST" action="{{ route('resellers.search_query') }}">
@csrf
	<input type="text" name="query" />
	<input type="submit" value="Search" />
</form>
