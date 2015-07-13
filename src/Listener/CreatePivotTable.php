<?php namespace Anomaly\FilesFieldType\Listener;

use Anomaly\FilesFieldType\FilesFieldType;
use Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

/**
 * Class CreatePivotTable
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType\Listener
 */
class CreatePivotTable
{

    /**
     * The schema builder.
     *
     * @var Builder
     */
    protected $schema;

    /**
     * Create a new StreamSchema instance.
     */
    public function __construct()
    {
        $this->schema = app('db')->connection()->getSchemaBuilder();
    }

    /**
     * Handle the event.
     *
     * @param AssignmentWasCreated $event
     */
    public function handle(AssignmentWasCreated $event)
    {
        $assignment = $event->getAssignment();

        $fieldType = $assignment->getFieldType();

        if (!$fieldType instanceof FilesFieldType) {
            return;
        }

        $table = $assignment->getStreamPrefix() . $assignment->getStreamSlug() . '_' . $fieldType->getField();

        $this->schema->dropIfExists($table);

        $this->schema->create(
            $table,
            function (Blueprint $table) {

                $table->integer('entry_id');
                $table->integer('file_id');
                $table->integer('sort_order');

                $table->primary(['entry_id', 'file_id']);
            }
        );
    }
}
