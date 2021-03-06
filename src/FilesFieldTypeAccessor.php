<?php namespace Anomaly\FilesFieldType;

use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeAccessor;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FilesFieldTypeAccessor
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\FilesFieldType
 */
class FilesFieldTypeAccessor extends FieldTypeAccessor
{

    /**
     * The field type object.
     * This is for IDE support.
     *
     * @var FilesFieldType
     */
    protected $fieldType;

    /**
     * Set the value.
     *
     * @param $value
     */
    public function set($value)
    {
        if (is_array($value)) {
            $this->fieldType->getRelation()->sync($this->organizeSyncValue($value));
        }

        if ($value instanceof Collection) {
            $this->fieldType->getRelation()->sync($this->organizeSyncValue($value->filter()->all()));
        }

        if ($value instanceof EntryInterface) {
            $this->fieldType->getRelation()->sync($this->organizeSyncValue([$value->getId()]));
        }

        if (!$value) {
            $this->fieldType->getRelation()->detach();
        }
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->fieldType->getRelation();
    }

    /**
     * Organize the value for sync.
     *
     * @param array $value
     * @return array
     */
    protected function organizeSyncValue(array $value)
    {
        $value = array_filter($value);

        return array_combine(
            array_values($value),
            array_map(
                function ($key) {
                    return ['sort_order' => $key];
                },
                array_keys($value)
            )
        );
    }
}
