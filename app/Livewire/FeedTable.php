<?php

namespace App\Livewire;

use App\Models\Feed;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class FeedTable extends PowerGridComponent
{
    public string $tableName = 'feed-table-xckbto-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    protected $listeners = [
        '$refresh'
    ];

    public function datasource(): Builder
    {
        return Feed::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('feed_name')
            ->add('feed_type')
            ->add('quantity')
            ->add('unit_price')
            ->add('purchase_date_formatted', fn(Feed $model) => Carbon::parse($model->purchase_date)->format('d/m/Y'))
            ->add('expiry_date_formatted', fn(Feed $model) => Carbon::parse($model->expiry_date)->format('d/m/Y'))
            ->add('supplier')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Feed name', 'feed_name')
                ->sortable()
                ->searchable(),

            Column::make('Feed type', 'feed_type')
                ->sortable()
                ->searchable(),

            Column::make('Quantity', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Unit price', 'unit_price')
                ->sortable()
                ->searchable(),

            Column::make('Purchase date', 'purchase_date_formatted', 'purchase_date')
                ->sortable(),

            Column::make('Expiry date', 'expiry_date_formatted', 'expiry_date')
                ->sortable(),

            Column::make('Supplier', 'supplier')
                ->sortable()
                ->searchable(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Created at', 'created_at')
            //     ->sortable()
            //     ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('purchase_date'),
            Filter::datepicker('expiry_date'),
        ];
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId): void
    // {
    //     $this->js('alert(' . $rowId . ')');
    // }

    public function actions(Feed $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['id' => $row->id]),

            Button::add('add_usage')
                ->slot('Add Usage: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('recordUsage', ['id' => $row->id])

        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
