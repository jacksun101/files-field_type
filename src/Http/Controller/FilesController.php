<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Illuminate\Routing\Redirector;

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

    /**
     * Redirect to a file's public URL.s
     *
     * @param FileRepositoryInterface $files
     * @param Redirector              $redirector
     * @param                         $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function view(FileRepositoryInterface $files, Redirector $redirector, $id)
    {
        $file = $files->find($id);

        return $redirector->to($file->publicPath());
    }
}
