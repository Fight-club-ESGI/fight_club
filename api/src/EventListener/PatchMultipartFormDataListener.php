<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PatchMultipartFormDataListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // We only care about PATCH requests with the multipart/form-data content type
        if ($request->getMethod() !== Request::METHOD_PATCH
            || !str_starts_with($request->headers->get('Content-Type'), 'multipart/form-data')
        ) {
            return;
        }

        // Get the raw request content
        $content = $request->getContent();

        // Extract the boundary from the Content-Type header
        $boundary = substr($request->headers->get('Content-Type'), 30);
        // Split the content into parts
        $parts = array_slice(explode('--' . $boundary, $content), 1, -1);

        $data = [];
        $files = [];

        // Process each part
        foreach ($parts as $part) {
            // If the part contains a filename header, it's a file
            if (str_contains($part, 'filename=')) {
                // Extract the filename from the header
                preg_match('/filename="([^"]*)"/', $part, $matches);
                preg_match('/name="([^"]*)"/', $part, $fileMatches);
                $fileFieldName = $fileMatches[1];
                $filename = $matches[1];
                // Extract the file content from the body
                list($header, $body) = explode("\r\n\r\n", $part, 2);
                $body = substr($body, 0, -2);

                // Create an UploadedFile object
                $tempPath = tempnam(sys_get_temp_dir(), 'upload');
                file_put_contents($tempPath, $body);
                $file = new UploadedFile(
                    $tempPath,
                    $filename,
                    null,
                    null,
                    true // Mark it as already moved
                );

                $files[$fileFieldName] = $file;
            } else {
                list($header, $body) = explode("\r\n\r\n", $part, 2);
                preg_match('/name="([^"]*)"/', $header, $matches);
                $fieldName = $matches[1];
                $body = substr($body, 0, -2);

                $data[$fieldName] = $body;
            }
        }

        $request->files->add($files);
        $request->request->replace($data);
    }
}