<?php

namespace Nilgems\PhpTextract\Extractor;

use Nilgems\PhpTextract\Concerns\AbstractExtractor;

class TxtExtractor extends AbstractExtractor
{
    /**
     * @var bool|resource $file_handler
     */
    private $file_handler;

    protected string $extractor_name = 'The txt extractor';

    protected array $extractor_supported_extension = ['txt', 'html'];

    protected array $mime_accepts = [
        'text/plain',
        'text/html'
    ];

    protected string $error_message = "The file content is not supported to read. Please check the file content and try again.";

    /**
     * @return bool
     */
    protected function checkHaveProviderPackage(): bool
    {
        if ($file_reader = fopen($this->file_path, 'rb')) {
            $this->file_handler = $file_reader;
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getTextFromFile(): string
    {
        $file_size = filesize($this->file_path);
        $read_data = fread($this->file_handler, $file_size);
        $output = $this->getFilteredText($read_data);
        fclose($this->file_handler);

        return $output;
    }

    /**
     * @param string|bool $read_data
     * @return string
     */
    private function getFilteredText($read_data): string
    {
        if ($read_data) {
            switch ($this->current_mime_type) {
                case "text/html":
                    return strip_tags($read_data);
                default:
                    return (string) $read_data;
            }
        }
        return "";
    }
}
