<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesFieldType\Table\FileTableBuilder;
use Anomaly\FilesFieldType\Table\UploadTableBuilder;
use Anomaly\FilesModule\File\FileUploader;
use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

/**
 * Class UploadController
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType\Http\Controller
 */
class UploadController extends AdminController
{

    /**
     * Return the uploader.
     *
     * @param FolderRepositoryInterface $folders
     * @param                           $folder
     * @return \Illuminate\View\View
     */
    public function index(FolderRepositoryInterface $folders, $folder)
    {
        return $this->view->make('anomaly.field_type.files::upload/index', ['folder' => $folders->find($folder)]);
    }

    /**
     * Upload a file.
     *
     * @param FileUploader              $uploader
     * @param FolderRepositoryInterface $folders
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(FileUploader $uploader, FolderRepositoryInterface $folders)
    {
        if ($file = $uploader->upload($this->request->file('upload'), $folders->find($this->request->get('folder')))) {
            return $this->response->json($file->getAttributes());
        }

        return $this->response->json(['error' => 'There was a problem uploading the file.'], 500);
    }

    /**
     * Return the recently uploaded files.
     *
     * @param FileTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recent(UploadTableBuilder $table)
    {
        return $table->setUploaded(explode(',', $this->request->get('uploaded')))
            ->make()
            ->getTableContent();
    }
}
