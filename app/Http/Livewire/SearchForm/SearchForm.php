<?php

namespace App\Http\Livewire\SearchForm;

use Livewire\Component;

use Illuminate\Database\Eloquent\Builder;

class SearchForm extends Component
{
    public $searchTerm;
    public $searchParam = null;
    public $model;
    public $results;

    public function mount($model)
    {
        $this->model = $model;
        $this->results = collect();
        $this->searchParam = 'nombre';
    }

    public function getResultsProperty()
    {
        if ($this->searchParam) {
            $this->results = $this->model::query()->when($this->searchTerm, function (Builder $query) {
                return $query->where($this->searchParam, 'like', '%' . $this->searchTerm . '%');
            })->get();
        } else {
            throw new \Exception('Search parameter is not set');
        }
    }

    public function search()
{
    $this->getResultsProperty();
}
    public function render()
    {
        return view('livewire.search-form.search-form', [
            'results' => $this->results,
        ]);
    }
}