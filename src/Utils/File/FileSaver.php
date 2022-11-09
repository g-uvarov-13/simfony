<?php

namespace App\Utils\File;

use App\Utils\FileSystem\FileSystemWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var string
     */
    private $uploadTempDir;
    /**
     * @var FileSystemWorker
     */
    private $fileSystemWorker;

    /**
     * FileSaver constructor.
     * @param SluggerInterface $slugger
     * @param FileSystemWorker $fileSystemWorker
     * @param string $uploadTempDir
     */
    public function __construct(SluggerInterface $slugger, FileSystemWorker $fileSystemWorker,  string $uploadTempDir)
    {

        $this->slugger = $slugger;
        $this->uploadTempDir = $uploadTempDir;
        $this->fileSystemWorker = $fileSystemWorker;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string|null
     */
    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile): ?string
    {
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) ;
        $safeFileName = $this->slugger->slug($originalFileName);

        $fileName = sprintf('%s-%s.%s',$safeFileName, uniqid(), $uploadedFile->guessExtension() );

        $this->fileSystemWorker->CreateFolderIfNotExists($this->uploadTempDir);

        try {
            $uploadedFile->move($this->uploadTempDir, $fileName);
        } catch (\Exception $exception) {
            return null;
        }

        return $fileName;
    }


}