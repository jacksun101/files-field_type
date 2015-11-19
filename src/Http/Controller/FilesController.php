<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesFieldType\Table\FileTableBuilder;
use Anomaly\FilesModule\Entry\Form\EntryFormBuilder;
use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilesController
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType\Http\Controller
 */
class FilesController extends PublicController
{

    public function index(FileTableBuilder $table, $id)
    {
        return $table->setOption('attributes.id', $id)->on('querying', function(Builder $query) {
            $query->whereNotIn('id', explode(',', $this->request->get('selected', '')));
        })->render();
    }

    public function choose(FolderRepositoryInterface $folders)
    {
        return view(
            'anomaly.field_type.files::choose',
            [
                'folders' => $folders->all()
            ]
        );
    }

    public function upload(FolderRepositoryInterface $folders)
    {
        return view('anomaly.field_type.files::upload', ['folder' => $folders->find($this->request->get('folder'))]);
    }

    public function test(FileTableBuilder $table)
    {
        return $table->setOption('attributes.id', 'test_field')->on(
            'querying',
            function (Builder $query) {
                $query->whereIn('id', explode(',', $this->request->get('uploaded')));
            }
        )->render();
    }
}
