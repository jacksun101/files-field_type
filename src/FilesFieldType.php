<?php namespace Anomaly\FilesFieldType;

use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class FilesFieldType
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType
 */
class FilesFieldType extends FieldType implements SelfHandling
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
     * The wrapper view.
     *
     * @var string
     */
    protected $wrapperView = 'anomaly.field_type.files::wrapper';

    /**
     * Get the config.
     *
     * @return array
     */
    public function getConfig()
    {
        $config = parent::getConfig();

        /**
         * If images only manually set
         * the allowed mimes.
         */
        if (array_get($config, 'image')) {
            array_set($config, 'mimes', ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg']);
        }

        /**
         * If restricting mimes then prepend
         * with a period as Dropzone requires.
         */
        if (isset($config['mimes'])) {
            foreach ($config['mimes'] as &$extension) {
                $extension = '.' . $extension;
            }
        }

        return $config;
    }

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

    /**
     * Handle saving the form data ourselves.
     *
     * @param FormBuilder $builder
     */
    public function handle(FormBuilder $builder)
    {
        $entry = $builder->getFormEntry();

        // See the accessor for how IDs are handled.
        $entry->{$this->getField()} = $this->getPostValue();
    }
}
