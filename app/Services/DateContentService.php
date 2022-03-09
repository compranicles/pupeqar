<?php

namespace App\Services;

use Illuminate\Http\Request;

class DateContentService {
    public function checkDateContent(Request $request, $field_name) {
        if (empty($request->input($field_name))) {
            $field_name = null;
        }
        else {
            $field_name = date("Y-m-d", strtotime($request->input($field_name)));
        }

        return $field_name;
    }
}