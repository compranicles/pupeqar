<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td>{{ ($value == null) ? '' : date('m/d/Y', strtotime($value)) }}</td>
</tr>