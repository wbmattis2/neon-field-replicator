# Neon Field Replicator
PHP script that copies and multiplies select custom fields in Neon CRM with the Neon CRM API. Written by Benny Mattis.

## Purpose  

Multiplies custom fields in Neon CRM to minimize repetitive data entry. Initially written to allow constituents to share "job listing" data for multiple jobs (Job Listing 1 Title, Job Listing 1 Countries, Job Listing 1 States, Job Listing 2 Title, Job Listing 2 Countries, etc.).

## How to Use 

Some assembly is required. Begin by downloading or copying the script to your own file.

On lines 2 and 3, edit the script to include your Neon CRM [organization id and API key](https://developer.neoncrm.com/authentication/) instead of `sample_org_id` and `sample_api_key` (respectively).

The Neon Field Replicator replicates all the fields in your Neon CRM instance that start with a given "field marker." This marker should be the same string of characters at the beginning of each custom field that you wish to replicate; the field marker should not be found at the beginning of any custom fields that you do not wish to replicate. For example, if the field marker is "Job Listing" then each and every field with a title that begins with "Job Listing" will be replicated when the script is run. On line 4, replace `sample_field_marker` with the marker you used to designate the relevant fields.

Keep in mind that if anything follows the field marker in any of the marked fields, then it should start with a space followed by the number 1 (or whichever number you want to startb from.

Your newly created "copy" fields will be numbered; how high should the numbers go? For example, do you want to create custom fields up to Job Listing 3, or go all the way up to Job Listing 10? On line 5, replace `sample_target_number` with the number you want to reach with your copies.

Keep in mind that if some of your fields are already numbered in this way, then the Neon Field Replicator will start from there and only make incremental copies until the target number is reached; if your field marker is "Job Listing" and one of your existing custom fields is marked "Job Listing 6" then copies of that field will only be made if your target number is greater than 6.

Finally, run the script from your [command line](https://www.php.net/manual/en/features.commandline.php) or access the page in your browser through a [running php server](https://www.wikihow.com/Run-a-PHP-File-in-a-Browser). Upon completing the operation, the script will display the responses received from the Neon CRM API for each of the custom fields that the script attempted to create.

## Features

### Copy a single field

If you are replicating one field, then your field name should just be your field marker (e.g. "Event") followed by the number 1, separated by a space ("Event 1"). The copies created will include include the field marker followed by the incrementing numbers, separated by a space (e.g. "Event 2" "Event 3" etc.)

### Copy groups of fields

This tool can be used to simulate master-detail relationships such as in the "Job Listing" example mentioned above, where the field marker ("Job Listing") and number preface a different detail in each of the prototype custom fields ("Title", "Countries", "Type", etc.). The format to follow when designing these prototypes is "[Field Marker] [Number] [Detail]", with spaces between each component (e.g. "Job Listing 1 Title").  

## License

Copyright 2024 Benny Mattis

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
