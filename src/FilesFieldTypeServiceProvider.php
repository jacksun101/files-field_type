<?php namespace Anomaly\FilesFieldType;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class FilesFieldTypeServiceProvider
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\FilesFieldType
 */
class FilesFieldTypeServiceProvider extends AddonServiceProvider
{

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'streams/files-field_type/index/{key}'     => 'Anomaly\FilesFieldType\Http\Controller\FilesController@index',
        'streams/files-field_type/choose/{key}'    => 'Anomaly\FilesFieldType\Http\Controller\FilesController@choose',
        'streams/files-field_type/selected'        => 'Anomaly\FilesFieldType\Http\Controller\FilesController@selected',
        'streams/files-field_type/upload/{folder}' => 'Anomaly\FilesFieldType\Http\Controller\UploadController@index',
        'streams/files-field_type/handle'          => 'Anomaly\FilesFieldType\Http\Controller\UploadController@upload',
        'streams/files-field_type/recent'          => 'Anomaly\FilesFieldType\Http\Controller\UploadController@recent',
    ];

    /**
     * The addon listeners.
     *
     * @var array
     */
    protected $listeners = [
        'Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated' => [
            'Anomaly\FilesFieldType\Listener\CreatePivotTable'
        ],
        'Anomaly\Streams\Platform\Assignment\Event\AssignmentWasDeleted' => [
            'Anomaly\FilesFieldType\Listener\DropPivotTable'
        ]
    ];

}
