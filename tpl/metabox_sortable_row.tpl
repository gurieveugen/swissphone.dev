		<tr class="hv-sortable-row">
			<td>
				<input type="text" 		name="meta_${name}_titles[]" value="${title}" /><br />
				<input type="button" 	class="remove-row-button button" value="delete" />
			</td>
			<td>
				<textarea name="meta_${name}_values[]">${value}</textarea>
			</td>
			<td style="${target_style}">
				<input type="checkbox" 		name="meta_${name}_targets[]" value="_blank" ${target} /> Open in new window<br />
			</td>
			<td>${additional_cell}</td>
		</tr>