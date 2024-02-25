<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilterJobs implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query, Model $model, array $filters) 
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = strtolower($search); // Convert search term to lowercase
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(title) like ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(description) like ?', ['%' . $search . '%'])
                    ->orWhereHas('employee', function($query) use($search){
                        $query->whereRaw('LOWER(company_name) like ?', ['%' . $search . '%']);
                    });
            });
        })->when($filters['min_salary'] ?? null, function ($query, $minSalary) {
            $query->where('salary', '>=', $minSalary);
        })->when($filters['max_salary'] ?? null, function ($query, $maxSalary) {
            $query->where('salary', '<=', $maxSalary);
        })->when($filters['experience'] ?? null, function ($query, $experience) {
            $query->where('experience', $experience);
        })->when($filters['category'] ?? null, function ($query, $category) {
            $query->where('category', $category);
        });
    }
}
