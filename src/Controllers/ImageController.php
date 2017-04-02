<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 10/01/2017
 * Time: 22:30.
 */

namespace VinylStore\Controllers;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Symfony\Component\Filesystem\Filesystem;
use VinylStore\Forms\ImageUploadType;
use VinylStore\Entity\FileEntity;
use VinylStore\BoolFlag;
use VinylStore\FileUploader;
use Spatie\Image\Image;

class ImageController
{

    /**
     * Upload image form.
     *
     * renders the form as well as handling the upload.
     * route: match: admin/upload-image
     *
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function uploadImageAction(Request $request, Application $app)
    {
        $count = 0;
        $choices = $app['vinyl.repository']->fillChoicesWithReleaseId();

        foreach ($choices as $choice) {
            $id[] = $choice->getId();
            $title[] = $choice->getTitle();

    }
        $file = new FileEntity();
        $form = $app['form.factory']
            ->createBuilder(ImageUploadType::class, $file, array('id' => $id, 'title' => $title))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadedImage = $app['file.uploader']->upload($file);
            if(!$count = $app['image.repository']->save($file)) {
                $app['session']->getFlashBag()->add('failure', $uploadedImage);
            }
            $app['session']->getFlashBag()->add('success', $uploadedImage);

//            $count = $app['image.repository']->save($file);
        }

        $templateName = 'backend/uploadImageForm';
        $args_array = array(
            'user' => $app['session']->get('user'),
            'form' => $form->createView(),
            'count' => $count,
        );

        return $app['twig']->render($templateName.'.html.twig', $args_array);
    }

    public function viewImagesAction(Application $app)
    {
        $allImages = $app['image.repository']->findAll();
        $releases = $app['vinyl.repository']->findAll();
        $template = 'backend/viewImages';

        return $app['twig']->render($template.'.html.twig', array(
            'images' => $allImages,
            'releases' => $releases
        ));
    }

    /**
     * Delete an image from the filesystem and the database.
     *
     * This will be done through an ajax call
     *
     * @param Application $app
     * @param $id . The image id.
     * @return string
     */
    public function deleteImageAction(Application $app, $id)
    {
        $response = '';





//        query the db for a file entity
//        and get the actual image string
//        to pass to the delete filesystem function
//        note: do not delete from db first.
//        correct order: filesystem first, db second.
        $image = $app['image.repository']->getImageNameForDelete($id);
        $path = $image->getImage();
        $imagePath = $image->setImagePath($path);
        $fs = new Filesystem();
        if($fs->exists($imagePath)) {
            try {
                $fs->remove($imagePath);
                $response .= 'Success. deleted from filesystem<br>';
            } catch (IOException $e) {
                return $e->getMessage();
            }
        }
        $count = $app['image.repository']->deleteOneById($id);
        if (!$count === 1) {
            $response .= 'Theres a problem with the response.';
            return $response;
        } else {
            $response .= 'Success! An image was deleted from the database.';
            return $response;
        };
    }

    /**
     * Attach an image to a release.
     *
     * Another ajax call.
     * Gets the imageId and the releaseId from the template and updates the
     * image table with the releaseId.
     *
     * @param Application $app
     * @param $imageId
     * @param $releaseId
     */
    public function attachImageAction(Application $app, $imageId, $releaseId)
    {
        $count = $app['image.repository']->attachImageToRelease($imageId, $releaseId);
        if (!$count === 1) {
            $response = 'Theres a problem with the response';
            return $response;
        } else {
            $response = 'Success! You have attached the image';
            return $response;
        }
    }
}