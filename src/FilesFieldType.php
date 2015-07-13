<?php namespace Anomaly\FilesFieldType;

use Anomaly\FilesModule\File\FileModel;
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
            'entry_id',
            'file_id'
        );
    }

    /**
     * Get the pivot table.
     *
     * @return string
     */
    public function getPivotTableName()
    {
        return $this->entry->getTableName() . '_' . $this->getField();
    }

    /**
     * Get the related model.
     *
     * @return null|FileModel
     */
    public function getRelatedModel()
    {
        return $this->container->make(array_get($this->getConfig(), 'related'), 'Anomaly\FilesModule\File\FileModel');
    }
}
