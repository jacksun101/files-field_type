<?php namespace Anomaly\FilesFieldType\Table;

use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

/**
 * Class FileTableBuilder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType\Table
 */
class FileTableBuilder extends TableBuilder
{

    /**
     * The ajax flag.
     *
     * @var bool
     */
    protected $ajax = true;

    /**
     * The table model.
     *
     * @var string
     */
    protected $model = FileModel::class;

    /**
     * The table filters.
     *
     * @var array
     */
    protected $filters = [
        'folder',
        'filename'
    ];

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [
        'entry.preview' => [
            'heading' => 'anomaly.module.files::field.preview.name'
        ],
        'filename'      => [
            'sort_column'   => 'filename',
            'data-file'     => 'entry.id',
            'data-filename' => 'entry.filename',
            'wrapper'       => '<h4 style="margin: 0 0 3px;">{value.filename}<br><small>{value.keywords}</small></h4>',
            'value'         => [
                'filename' => 'entry.filename',
                'keywords' => 'entry.keywords.labels'
            ]
        ],
    ];

    protected $actions = [
        'select'
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'title' => 'anomaly.field_type.files::message.choose_file'
    ];

    /**
     * The table assets.
     *
     * @var array
     */
    protected $assets = [
        'styles.css' => [
            'anomaly.field_type.files::less/table.less'
        ],
        'scripts.js' => [
            'anomaly.field_type.files::js/table.js'
        ]
    ];

}
