<?php namespace Anomaly\FilesFieldType;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class FilesFieldTypeServiceProvider
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
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
        'streams/file-field_type/files/{id}' => 'Anomaly\FilesFieldType\Http\Controller\FilesController@index',
        'streams/file-field_type/choose'     => 'Anomaly\FilesFieldType\Http\Controller\FilesController@choose',
        'streams/file-field_type/upload'     => 'Anomaly\FilesFieldType\Http\Controller\FilesController@upload',
        'streams/file-field_type/test'       => 'Anomaly\FilesFieldType\Http\Controller\FilesController@test',
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
