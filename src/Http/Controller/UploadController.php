<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesModule\Disk\Contract\DiskRepositoryInterface;
use Anomaly\FilesModule\File\Contract\FileInterface;
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
     * @param DiskRepositoryInterface $disks
     * @param ResponseFactory         $response
     * @param MountManager            $manager
     * @param Request                 $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(
        DiskRepositoryInterface $disks,
        ResponseFactory $response,
        MountManager $manager,
        Request $request
    ) {

        $path = trim($request->get('path'), '.');

        $file = $request->file('upload');
        $disk = $request->get('disk');

        if (is_numeric($disk)) {
            $disk = $disks->find($disk);
        } elseif (is_string($disk)) {
            $disk = $disks->findBySlug($disk);
        }

        if (!$disk) {
            return $response->json(
                'The configured upload disk [' . $request->get('disk') . '] does not exist!',
                500
            );
        }

        $file = $manager->putStream(
            $disk->path(ltrim(trim($path, '/') . '/' . $file->getClientOriginalName(), '/')),
            fopen($file->getRealPath(), 'r+')
        );

        /* @var FileInterface $file */

        return $response->json($file->getAttributes());
    }
}
