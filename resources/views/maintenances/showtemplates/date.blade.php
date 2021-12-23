<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td>{{ ($value == null) ? 'N/A' : date('m/d/Y', strtotime($value)) }}</td>
</tr>