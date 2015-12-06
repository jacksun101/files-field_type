<?php namespace Anomaly\FilesFieldType;

use Anomaly\FilesFieldType\Table\ValueTableBuilder;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Support\Collection;
use Anomaly\Streams\Platform\Support\Query;
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
     * The field type config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = parent::getRules();

        if ($min = array_get($this->getConfig(), 'min')) {
            $rules[] = 'min:' . $min;
        }

        if ($max = array_get($this->getConfig(), 'max')) {
            $rules[] = 'max:' . $max;
        }

        return $rules;
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
        )->orderBy($this->getPivotTableName() . '.sort_order', 'ASC');
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

    /**
     * Get the post value.
     *
     * @param null $default
     * @return array
     */
    public function getPostValue($default = null)
    {
        return explode(',', parent::getPostValue($default));
    }

    /**
     * Get the index path.
     *
     * @return string
     */
    public function getIndexPath()
    {
        $field     = $this->getField();
        $stream    = $this->entry->getStreamSlug();
        $namespace = $this->entry->getStreamNamespace();

        return "streams/files-field_type/index";
    }

    /**
     * Get the upload path.
     *
     * @return string
     */
    public function getUploadPath()
    {
        $field     = $this->getField();
        $stream    = $this->entry->getStreamSlug();
        $namespace = $this->entry->getStreamNamespace();

        return "streams/files-field_type/choose";
    }

    /**
     * Value table.
     *
     * @return string
     */
    public function valueTable()
    {
        $table = app(ValueTableBuilder::class);

        $files = $this->getValue();

        return $table->setUploaded($files ? [] : $files->lists('id')->all())->make()->getTableContent();
    }
}
