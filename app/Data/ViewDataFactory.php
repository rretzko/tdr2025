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
        if ($this->viewPage->id) {
            $this->dto['pageName'] = 'pages.'.$this->viewPage->page_name.'Page';
            $this->dto['header'] = $this->viewPage->header;

            //retrieve page components ex. cards
            foreach ($this->getComponents() as $component) {

                $method = 'get'.ucfirst($component);

                $this->dto[$component] = $this->$method();
            }
        } else { //user default values

            $this->dto['pageName'] = 'pages.dashboardPage';
            $this->dto['header'] = 'unknown';
            $this->dto['cards'] = [];
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
