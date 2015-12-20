<?php namespace Anomaly\FilesFieldType\Table;

use Anomaly\FileFieldType\FileFieldType;
use Anomaly\FilesFieldType\FilesFieldType;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ValueTableBuilder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType\Table
 */
class ValueTableBuilder extends TableBuilder
{

    /**
     * The field type.
     *
     * @var null|FileFieldType
     */
    protected $fieldType = null;

    /**
     * The table model.
     *
     * @var string
     */
    protected $model = FileModel::class;

    /**
     * The table buttons.
     *
     * @var array
     */
    protected $buttons = [
        'remove' => [
            'data-dismiss' => 'file',
            'data-file'    => 'entry.id'
        ]
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'limit'              => 9999,
        'panel_class'        => '',
        'container_class'    => '',
        'show_headers'       => false,
        'sortable_headers'   => false,
        'table_view'         => 'anomaly.field_type.files::table/table',
        'no_results_message' => 'anomaly.field_type.files::message.no_files_selected'
    ];

    /**
     * The table assets.
     *
     * @var array
     */
    protected $assets = [
        'styles.css' => [
            'anomaly.field_type.files::less/input.less'
        ]
    ];

    /**
     * Fired just before querying
     * for table entries.
     *
     * @param Builder $query
     */
    public function onQuerying(Builder $query)
    {
        $fieldType = $this->getFieldType();
        $entry     = $fieldType->getEntry();
        $table     = $fieldType->getPivotTableName();

        $query->join($table, $table . '.file_id', '=', 'files_files.id');

        $query->where($table . '.entry_id', $entry->getId());

        $query->orderBy($table . '.sort_order', 'ASC');
    }

    /**
     * Get the field type.
     *
     * @return FilesFieldType|null
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set the field type.
     *
     * @param FilesFieldType $fieldType
     * @return $this
     */
    public function setFieldType(FilesFieldType $fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }
}
