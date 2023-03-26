<?php
require __DIR__ . "/includes/header.php";
?>
<div class="formTitle">Tags</div>
<div style="font-size: 14px; font-weight: bold; color: #666666; margin-bottom: 10px;">Lastest Tags //</div>
<?php
$tags_strings = $conn->query("SELECT tags FROM videos WHERE converted = 1 ORDER BY uploaded_at DESC LIMIT 60");
$tag_list = [];
foreach($tags_strings as $result) $tag_list = array_merge($tag_list, explode(" ", $result['tags']));
$tag_list = array_slice(array_count_values($tag_list), 0, 50);
?>

<table width="70%" align="left" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left">
			<div style="font-size: 13px; color: #333333; margin-bottom: 30px;">
				<?php foreach($tag_list as $tag => $frequency	) {
					echo '<a style="font-size: '.(($frequency*2) + 10).'px;" href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> :'."\r\n";
				} ?>
			</div>
		</td>
	</tr>
</table>
