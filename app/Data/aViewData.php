<?php

namespace App\Data;

use App\Models\UserConfig;
use App\Models\ViewPage;

abstract class aViewData
{
    protected array $dto = [];
    protected ViewPage $viewPage;
    private string $controller = '';
    private string $method = '';
    protected int $versionId = 0;

    public function __construct(private readonly string $__method)
    {
        //strip $__method to constituent parts: controller and method
        $this->buildMethod($this->__method);

        //use controller and method to identify view target
        $this->viewPage = $this->getViewPage();

        //make commonly used variable quickly available
        $this->versionId = (int) UserConfig::getValue('versionId') ?? 0;
    }

    private function buildMethod($str): void
    {
        $parts = explode('::', $str);

        $this->method = $parts[1];

        $this->buildController($parts[0]);
    }

    private function buildController(string $str): void
    {
        $parts = explode('\\', $str);

        $this->controller = last($parts);
    }

    private function getViewPage(): ViewPage
    {
        return ViewPage::query()
            ->where('controller', $this->controller)
            ->where('method', $this->method)
            ->first();
    }
}
