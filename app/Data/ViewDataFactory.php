<?php

namespace App\Data;

use App\Models\ViewCard;

class ViewDataFactory extends aViewData
{
    public function __construct(public readonly string $__method)
    {
        parent::__construct($this->__method);

        $this->init();
    }

    private function init(): void
    {
        $this->dto['pageName'] = 'pages.'.$this->viewPage->page_name.'Page';
        $this->dto['header'] = $this->viewPage->header;

        foreach ($this->getComponents() as $component) {

            $method = 'get'.ucfirst($component);

            $this->dto[$component] = $this->$method();
        }
    }

    private function getComponents(): array
    {
        $components = [
            'dashboard' => ['cards'],
        ];

        return $components[$this->viewPage->page_name];
    }

    public function dto(): array
    {
        return $this->dto;
    }

    private function getCards(): array
    {
        return ViewCard::query()
            ->where('header', $this->dto['header'])
            ->orderBy('order_by')
            ->get()
            ->toArray();
    }

}
