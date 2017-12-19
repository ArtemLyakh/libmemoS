<?php if (!defined("INITIALIZED")) die();

class File
{
    private $id;
    public function getId() { return $this->id; }
    protected function setId (int $id) { $this->id = $id; }

    private $path;
    public function getPath() { return $this->path; }
    protected function setPath(string $path) { $this->path = $path; }

    public function __construct(int $id, string $path)
    {
        $this->setId($id);
        $this->setPath($path);
    }

}