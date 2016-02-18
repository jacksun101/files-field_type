<?php namespace Anomaly\FilesFieldType\Table;

use Anomaly\FileFieldType\FileFieldType;
use Anomaly\FilesFieldType\FilesFieldType;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ValueTableBuilder
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\FilesFieldType\Table
 */
class ValueTableBuilder extends TableBuilder
{

    /**
     * The uploaded IDs.
     *
     * @var array
     */
    protected $uploaded = [];

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
        $uploaded = $this->getUploaded();

        if ($fieldType = $this->getFieldType()) {

            /**
             * If we have the entry available then
             * we can determine saved sort order.
             */
            $entry = $fieldType->getEntry();
            $table = $fieldType->getPivotTableName();

            $query->join($table, $table . '.file_id', '=', 'files_files.id');
            $query->where($table . '.entry_id', $entry->getId());
            $query->orderBy($table . '.sort_order', 'ASC');
        } else {

            /**
             * If all we have is ID then just use that.
             * The JS / UI will be handling the sort
             * order at this time.
             */
            $query->whereIn('id', $uploaded ?: [0]);
        }
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

    /**
     * Get the uploaded.
     *
     * @return array
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set the uploaded.
     *
     * @param $uploaded
     * @return $this
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Set the table entries.
     *
     * @param \Illuminate\Support\Collection $entries
     * @return $this
     */
    public function setTableEntries(\Illuminate\Support\Collection $entries)
    {
        if (!$this->getFieldType()) {
            $entries = $entries->sort(
                function ($a, $b) {
                    return array_search($a->id, $this->getUploaded()) - array_search($b->id, $this->getUploaded());
                }
            );
        }

        return parent::setTableEntries($entries);
    }
}
