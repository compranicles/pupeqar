<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td>{{ ($value == null) ? 'n/a' : date('m/d/Y', strtotime($value)) }}</td>
</tr>