<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesModule\Disk\Contract\DiskRepositoryInterface;
use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\PublicController;
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
     * @param Request                   $request
     * @param DiskRepositoryInterface   $disks
     * @param FolderRepositoryInterface $folders
     * @param MountManager              $manager
     */
    public function handle(
        Request $request,
        DiskRepositoryInterface $disks,
        FolderRepositoryInterface $folders,
        MountManager $manager
    ) {
        $file   = $request->file('upload');
        $disk   = $disks->findBySlug($request->get('disk'));
        $folder = $folders->findByPath($request->get('path'), $disk);

        if ($folder) {
            $path = $folder->path($file->getClientOriginalName());
        } else {
            $path = $file->getClientOriginalName();
        }

        $stream = fopen($file->getRealPath(), 'r+');

        $manager->putStream($disk->getSlug() . '://' . $path, $stream);

        fclose($stream);
    }
}
