<?php namespace Anomaly\FilesFieldType;

use Anomaly\FilesFieldType\Validation\ValidateDisk;
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
     * The field type rules.
     *
     * @var array
     */
    protected $rules = [
        'valid_disk'
    ];

    /**
     * The field type validators.
     *
     * @var array
     */
    protected $validators = [
        'valid_disk' => [
            'handler' => ValidateDisk::class,
            'message' => 'anomaly.field_type.files::validation.valid_disk'
        ]
    ];

    /**
     * The field type config.
     *
     * @var array
     */
    protected $config = [
        'disk' => 'uploads'
    ];

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

        /**
         * Determine the default max upload size.
         */
        if (!array_get($config, 'max_size')) {

            $post = str_replace('M', '', ini_get('post_max_size'));
            $file = str_replace('M', '', ini_get('upload_max_filesize'));

            array_set($config, 'max_size', $file > $post ? $post : $file);
        }

        /**
         * Determine the max upload size allowed.
         */
        $post = str_replace('M', '', ini_get('post_max_size'));
        $file = str_replace('M', '', ini_get('upload_max_filesize'));

        $server = $file > $post ? $post : $file;

        if (!$max = array_get($config, 'max_size')) {
            $max = $server;
        }

        if ($max > $server) {
            $max = $server;
        }

        array_set($config, 'max_size', $max);

        return $config;
    }

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
}
