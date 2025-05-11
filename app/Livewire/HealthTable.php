<?php

namespace App\Livewire;

use App\Models\Health;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class HealthTable extends PowerGridComponent
{
    public string $tableName = 'health-table-mziwpa-table';

    protected $listeners = [
        '$refresh'
    ];

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

    public function datasource(): Builder
    {
        return Health::query();
    }



    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('flock_id')
            ->add('date_formatted', fn(Health $model) => Carbon::parse($model->date)->format('d/m/Y'))
            ->add('treatment_type')
            ->add('medication')
            ->add('dosage')
            ->add('diagnosis')
            ->add('symptoms')
            ->add('treatment_cost')
            ->add('mortality')
            ->add('notes')
            ->add('next_checkup_date_formatted', fn(Health $model) => Carbon::parse($model->next_checkup_date)->format('d/m/Y'))
            ->add('treated_by')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Flock id', 'flock_id'),
            Column::make('Date', 'date_formatted', 'date')
                ->sortable(),

            Column::make('Treatment type', 'treatment_type')
                ->sortable()
                ->searchable(),

            Column::make('Medication', 'medication')
                ->sortable()
                ->searchable(),

            Column::make('Dosage', 'dosage')
                ->sortable()
                ->searchable(),

            Column::make('Diagnosis', 'diagnosis')
                ->sortable()
                ->searchable(),

            Column::make('Symptoms', 'symptoms')
                ->sortable()
                ->searchable(),

            Column::make('Treatment cost', 'treatment_cost')
                ->sortable()
                ->searchable(),

            Column::make('Mortality', 'mortality')
                ->sortable()
                ->searchable(),

            // Column::make('Notes', 'notes')
            //     ->sortable()
            //     ->searchable(),

            Column::make('Next checkup date', 'next_checkup_date_formatted', 'next_checkup_date')
                ->sortable(),

            Column::make('Treated by', 'treated_by')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date'),
            Filter::datepicker('next_checkup_date'),
        ];
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId): void
    // {
    //     $this->js('alert(' . $rowId . ')');
    // }

    public function actions(Health $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['id' => $row->id]),

            Button::add('view')
                ->slot('View Record: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('view', ['id' => $row->id])
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
