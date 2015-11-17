<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use League\Flysystem\MountManager;

/**
 * Class UploadController
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\FilesFieldType\Http\Controller
 */
class UploadController extends PublicController
{

    /**
     * Handle the file upload.
     *
     * @param FolderRepositoryInterface $folders
     * @param MountManager              $manager
     * @return mixed
     */
    public function handle(FolderRepositoryInterface $folders, MountManager $manager)
    {

        $file   = $this->request->file('upload');
        $folder = $this->request->get('folder');

        if (is_numeric($folder)) {
            $folder = $folders->find($folder);
        } elseif (is_string($folder)) {
            $folder = $folders->findBySlug($folder);
        }

        $disk = $folder->getDisk();

        if (!$folder) {
            return $this->response->json(
                'The configured upload disk [' . $this->request->get('disk') . '] does not exist!',
                500
            );
        }

        /* @var FileInterface $file */
        $file = $manager->putStream(
            $disk->getSlug() . '://' . $folder->getSlug() . '/' . $file->getClientOriginalName(),
            fopen($file->getRealPath(), 'r+')
        );

        return $this->response->json($file->getAttributes());
    }
}
