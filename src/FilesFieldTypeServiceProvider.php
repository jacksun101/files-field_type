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
        'streams/files-field_type/upload'    => 'Anomaly\FilesFieldType\Http\Controller\UploadController@handle',
        'streams/files-field_type/view/{id}' => 'Anomaly\FilesFieldType\Http\Controller\FilesController@view',
        'streams/files-field_type/edit/{id}' => 'Anomaly\FilesFieldType\Http\Controller\FilesController@edit'
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
