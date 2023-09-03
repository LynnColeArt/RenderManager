<?php 

class PngReader
{
    private $chunks;
    private $file;

    public function __construct($file) {
        if (!file_exists($file)) {
            throw new Exception('File does not exist');
        }

        $this->chunks = [];
        $this->file = fopen($file, 'rb');

        if (!$this->file) {
            throw new Exception('Unable to open file');
        }

        $header = fread($this->file, 8);

        if ($header !== "\x89PNG\x0d\x0a\x1a\x0a") {
            throw new Exception('Is not a valid PNG image');
        }

        $chunkHeader = fread($this->file, 8);

        while ($chunkHeader) {
            $chunk = unpack('Nsize/a4type', $chunkHeader);

            if (!isset($this->chunks[$chunk['type']])) {
                $this->chunks[$chunk['type']] = [];
            }
            $this->chunks[$chunk['type']][] = [
                'offset' => ftell($this->file),
                'size' => $chunk['size'],
            ];

            fseek($this->file, $chunk['size'] + 4, SEEK_CUR);
            $chunkHeader = fread($this->file, 8);
        }
    }

    public function __destruct() {
        fclose($this->file);
    }

    public function getChunks($type) {
        if (!isset($this->chunks[$type])) {
            return null;
        }

        $chunks = [];

        foreach ($this->chunks[$type] as $chunk) {
            if ($chunk['size'] > 0) {
                fseek($this->file, $chunk['offset'], SEEK_SET);
                $chunks[] = fread($this->file, $chunk['size']);
            } else {
                $chunks[] = '';
            }
        }

        return $chunks;
    }
}