<?php
// Include this in pages after the main content
// The inserted text will begin at the closing TD tag of a 2-column spanning cell, 
// 		where the main page content should be placed

echo <<<FOOTER
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="docbodyxsmall" colspan="2" align="center" style="height: 12px">
					Copyright 2011-2012 - Response &amp; Relief Network - All rights reserved.
				</td>
			</tr>
		</table>
    </form>
</body>
</html>
FOOTER;
?>