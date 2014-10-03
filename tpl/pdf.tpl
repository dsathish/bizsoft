<html>
<head>
	<title>Pdf Report</title>
</head>
<body>

<form name="pdf_report" id="pdf_report" method="post" action="pdf.php?report_id={$report_id}&customization_id={$customization_id}">
<table width="100%" cellpadding="5" cellspacing="0" align="center">
	<tr>
		<td>Orientation</td>
		<td>
			{html_options name="orientation" options=$orientation_array selected=$orientation_default}
		</td>
	</tr>
	<tr>
		<td>Paper Size</td>
		<td>
			{html_options name="paper_size" options=$paper_size_array selected=$paper_default}
		</td>
	</tr>
	<tr>
		<td>Font</td>
		<td>
			{html_options name="font_name" options=$font_array selected=$font_name_default}
		</td>
	</tr>
	<tr>
		<td>Title Font Size</td>
		<td>
			{html_options name="title_font_size" options=$font_size_array selected=$title_font_size_default}
		</td>
	</tr>
	<tr>
		<td>Font Size</td>
		<td>
			{html_options name="font_size" options=$font_size_array selected=$font_size_default}
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" name="show" value="Show" />
		</td>
	</tr>
</table>
</form>
</body>
</html>