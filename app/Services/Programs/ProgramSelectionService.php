<?php

namespace App\Services\Programs;

class ProgramSelectionService
{
    private string $table = '';
    private $template = null;
    private string $templateName = '';

    public function __construct(private readonly int $programId, private readonly string $programTemplate = 'default')
    {
        $this->factory();
    }

    private function factory(): void
    {
        $this->templateName = 'App\Services\Programs\Templates\\'.ucwords($this->programTemplate).'Template';
        $this->template = new $this->templateName($this->programId);
    }

    public function getTable(): string
    {
        return $this->template->getTable();
    }
}
