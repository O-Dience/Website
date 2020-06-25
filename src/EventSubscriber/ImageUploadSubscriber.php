<?php

namespace App\EventSubscriber;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\SocialNetwork;
use App\Service\ImageUploader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ImageUploadSubscriber implements EventSubscriberInterface
{
    private $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }
    // Add every images/files you want to upload with ImageUploader service instead of default EasyAdmin config
    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_persist' => [
                                            ['postCategoryPicto'],
                                            ['postAnnouncementImage'],
                                            ['postSocialPicto'],
                                        ],
            'easy_admin.pre_update' => [
                                            ['postCategoryPicto'],
                                            ['postAnnouncementImage'],
                                            ['postSocialPicto'],
                                        ],
        ];
    }

    function postCategoryPicto(GenericEvent $event) {

        $entity = $event->getSubject();
        $method = $event->getArgument('request')->getMethod();
        if (! $entity instanceof Category || $method !== Request::METHOD_POST) {
            return;
        }
        if ($entity->getPicto() instanceof UploadedFile) {
            $imageName = $this->imageUploader->moveFile($entity->getPicto(), 'category_picto');
            $entity->setPicto($imageName);
        }
    }

    function postSocialPicto(GenericEvent $event) {

        $entity = $event->getSubject();
        $method = $event->getArgument('request')->getMethod();
        if (! $entity instanceof SocialNetwork || $method !== Request::METHOD_POST) {
            return;
        }
        if ($entity->getPicto() instanceof UploadedFile) {
            $imageName = $this->imageUploader->moveFile($entity->getPicto(), 'social_picto');
            $entity->setPicto($imageName);
        }
    }

    function postAnnouncementImage(GenericEvent $event) {

        $entity = $event->getSubject();
        $method = $event->getArgument('request')->getMethod();
        if (! $entity instanceof Announcement || $method !== Request::METHOD_POST) {
            return;
        }
        if ($entity->getImage() instanceof UploadedFile) {
            $imageName = $this->imageUploader->moveFile($entity->getImage(), 'image_announcement');
            $entity->setImage($imageName);
        }
    }
}