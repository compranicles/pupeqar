<tr>
    <th>{{ $fieldInfo->label }}</th>
    <?php 
        if ($value == "present")
            $date = $value;
        else {
            $date = strtotime( $value );
            $date = date( 'M d, Y', $date );
        }
        ?>  
    <td>{{ ($value == null) ? 'N/A' : $date }}</td>
</tr>