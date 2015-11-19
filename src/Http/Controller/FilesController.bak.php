<?php namespace Anomaly\FilesFieldType\Http\Controller;

use Anomaly\FilesModule\Entry\Form\EntryFormBuilder;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\FilesModule\File\Form\FileEntryFormBuilder;
use Anomaly\FilesModule\File\Form\FileFormBuilder;
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

    /**
     * Return an edit form.
     *
     * @param FileEntryFormBuilder    $form
     * @param FileRepositoryInterface $files
     * @param EntryFormBuilder        $entryForm
     * @param FileFormBuilder         $fileForm
     * @param                         $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        FileEntryFormBuilder $form,
        FileRepositoryInterface $files,
        EntryFormBuilder $entryForm,
        FileFormBuilder $fileForm,
        $id
    ) {
        $file   = $files->find($id);
        $disk   = $file->getDisk();
        $stream = $disk->getEntriesStream();

        $entryForm
            ->setModel($stream->getEntryModel())
            ->setEntry($file->getEntryId());

        $fileForm->setEntry($id);

        $form
            ->addForm('entry', $entryForm)
            ->addForm('file', $fileForm);

        $form
            ->setAjax(true)
            ->setActions(['save'])
            ->setOption('title', $file->getName())
            ->setOption('redirect', false)
            ->setSections(function() {
                return [];
            });

        return $form->render($id);
    }
}
