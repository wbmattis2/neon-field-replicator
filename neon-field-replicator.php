<?php
/*
The Neon Field Replicator, a tool for copying and multiplying custom fields in Neon CRM.
https://github.com/wbmattis2/neon-field-replicator/

MIT License

Copyright (c) 2024 Benny Mattis

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
$org_id = "sample_org_id";
$api_key = "sample_api_key";
$field_marker = "sample_field_marker";
$target_number = sample_target_number;
//Get custom fields
$get_opts = array(
    'http'=>array(
        'method'=>"GET",
        'header'=>"Authorization: Basic " . base64_encode($org_id . ":" . $api_key) . "\r\n" . 
            "NEON-API-VERSION: 2.7"
    )
);
$get_context = stream_context_create($get_opts);
$base_url = "https://api.neoncrm.com/v2";
$custom_fields = file_get_contents(
    $base_url . '/customFields' . '?&category=Account', 
    false, 
    $get_context
);
$custom_fields_assoc = json_decode($custom_fields, true);
$greatest_id = 0; //use to avoid conflicts with existing custom field ids
$fields_to_multiply = [];
//locate relevant custom fields
foreach ($custom_fields_assoc as $field) {
    $current_id = intval($field['id']);
    if ($current_id > $greatest_id) {
        $greatest_id = $current_id;
    }
    if ( strpos($field['name'], $field_marker) === 0 ) {
        if (strlen($field['name']) == strlen($field_marker)) {
            $current_field_number = 0;
            $field_detail = 'NO_DETAIL';
        }
        else {
            $field_name_following_marker = substr($field['name'], strlen($field_marker) + 1);
            $position_of_space_after_number = strpos($field_name_following_marker, ' ');
            if (!$position_of_space_after_number) {
                $current_field_number = intval($field_name_following_marker);
                $field_detail = 'NO_DETAIL';
            }
            else {
                $current_field_number = intval(substr($field_name_following_marker, 0, $position_of_space_after_number));
                $field_detail = trim(substr($field_name_following_marker, $position_of_space_after_number));
            }
        }
        if (!isset($fields_to_multiply[$field_detail]['base_number']) || ($fields_to_multiply[$field_detail]['base_number'] < $current_field_number)) {
            $fields_to_multiply[$field_detail] = [
                'base_number' => $current_field_number,
                'base_field' => $field
            ];
        }
    }
}
//post new fields to reach target number
$array_to_post = [];
$displayed_response = '';
foreach($fields_to_multiply as $detail => $base_field_obj) {
    if ($detail == 'NO_DETAIL') {
        $detail = '';
    }
    else {
        $detail = ' ' . $detail;
    }
    $current_number = $base_field_obj['base_number'];
    while ($current_number < $target_number) {
        $field_to_post = $base_field_obj['base_field'];
        $field_to_post['id'] = ++$greatest_id;
        $field_to_post['name'] = $field_marker . ' ' . strval(++$current_number) . $detail;
        $json_to_post = json_encode($field_to_post);
        $post_opts = array(
            'http'=>array(
                'method'=>"POST",
                'header'=>"Authorization: Basic " . base64_encode($org_id . ":" . $api_key) . "\r\n" . 
                    "NEON-API-VERSION: 2.7" . "\r\n" .
                    "Content-type: Application/JSON",
                'content' => $json_to_post,
                'ignore_errors' => true
            )
        );
        $post_context = stream_context_create($post_opts);
        $post_response = file_get_contents(
            $base_url . '/customFields', 
            false, 
            $post_context
        );
        $displayed_response .= "\n\n".$field_to_post['name']." (".$field_to_post['id'].") : " .$post_response;
    }
}
echo '<h1>Response from Neon API:</h1><br/><pre>' . $displayed_response . '</pre>';
?>
