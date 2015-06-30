<?php namespace Anomaly\FilesFieldType;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class FilesFieldType
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType
 */
class FilesFieldType extends FieldType
{

    /**
     * No database column.
     *
     * @var bool
     */
    protected $columnType = false;

    /**
     * The input view.
     *
     * @var string
     */
    protected $inputView = 'anomaly.field_type.files::input';

    /**
     * Get the relation.
     *
     * @return BelongsToMany
     */
    public function getRelation()
    {
        $entry = $this->getEntry();

        return $entry->belongsToMany(
            array_get($this->config, 'related', 'Anomaly\FilesModule\File\FileModel'),
            $this->getPivotTableName(),
            $this->getForeignKey(),
            $this->getOtherKey()
        );
    }

    /**
     * Get the pivot table.
     *
     * @return string
     */
    public function getPivotTableName()
    {
        $default = $this->entry->getStreamPrefix() . $this->entry->getStreamSlug() . '_' . $this->getField();

        return array_get($this->config, 'pivot_table', $default);
    }

    /**
     * Get the foreign key.
     *
     * @return mixed
     */
    public function getForeignKey()
    {
        return array_get($this->config, 'foreign_key', 'entry_id');
    }

    /**
     * Get the related key.
     *
     * @return mixed
     */
    public function getOtherKey()
    {
        return array_get($this->config, 'related_key', 'file_id');
    }
}
