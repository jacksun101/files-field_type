<?php

return [
    'disk'     => [
        'label'        => 'Upload Disk',
        'instructions' => 'Choose a disk to upload files to.'
    ],
    'path'     => [
        'label'        => 'Upload Path',
        'instructions' => 'Enter a path to upload files to or leave empty for the disk root.<br>If the path does not exist it will be created upon upload.'
    ],
    'image'    => [
        'label'        => 'Images Only',
        'instructions' => 'Restrict files to images only?'
    ],
    'mimes'    => [
        'label'        => 'Allowed Types',
        'instructions' => 'Specify the allowed file extensions. If no file extensions are specified, any file type may be uploaded.'
    ],
    'max_size' => [
        'label'        => 'Maximum Size',
        'instructions' => 'Specify the maximum file size allowed in <strong>Megabytes</strong>.<br>The default / maximum input allowed is the maximum system upload size.'
    ],
    'min'      => [
        'label'        => 'Minimum Uploads',
        'instructions' => 'Enter the minimum number of allowed uploads.'
    ],
    'max'      => [
        'label'        => 'Maximum Uploads',
        'instructions' => 'Enter the maximum number of allowed uploads.'
    ],
];
