<?php

namespace  App\Utils\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

class FileSystemWorker
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FileSystemWorker constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(FileSystem $filesystem)
    {

        $this->filesystem = $filesystem;
    }

    /**
     * @param string $folder
     */
    public function CreateFolderIfNotExists(string $folder)
    {

        if(!$this->filesystem->exists($folder)){
            $this->filesystem->mkdir($folder);
        }
    }

    /**
     * @param string $item
     */
    public function remove(string $item)
    {
        if($this->filesystem->exists($item)){
            $this->filesystem->remove($item);
        }
    }
}