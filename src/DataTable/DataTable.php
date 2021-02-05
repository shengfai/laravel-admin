<?php
namespace Shengfai\LaravelAdmin\DataTable;

use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;
use Shengfai\LaravelAdmin\Fields\Factory as FieldFactory;
use Shengfai\LaravelAdmin\DataTable\Columns\Factory as ColumnFactory;

class DataTable
{

    /**
     * The validator instance.
     *
     * @var \Shengfai\LaravelAdmin\DataTable\Columns\Factory
     */
    protected $columnFactory;

    /**
     * The validator instance.
     *
     * @var \Shengfai\LaravelAdmin\Fields\Factory
     */
    protected $fieldFactory;

    /**
     * Create a new action DataTable instance.
     *
     * @param \Shengfai\LaravelAdmin\DataTable\Columns\Factory $columnFactory
     * @param \Shengfai\LaravelAdmin\Fields\Factory $fieldFactory
     */
    public function __construct(ColumnFactory $columnFactory, FieldFactory $fieldFactory)
    {
        // set the config, and then validate it
        $this->columnFactory = $columnFactory;
        $this->fieldFactory = $fieldFactory;
    }

    /**
     * Builds a results array (with results and pagination info).
     *
     * @param mixed $queryBuilder
     * @param array $queryParameters
     * @param int $rowsPerPage
     * @return array
     */
    public function getRows($queryBuilder, array $queryParameters, int $rowsPerPage)
    {
        $defaultSort = $queryParameters['sorting']['default'] ?? '-id';
        $allowedSorts = $queryParameters['sorting']['allowed'] ?? [];
        $allowedFilters = $this->parseAllowedQueryFilters($queryParameters);
        $allowedIncludes = $queryParameters['including']['allowed'] ?? [];
        $withs = $queryParameters['relationships']['with'] ?? [];
        $counts = $queryParameters['relationships']['count'] ?? [];
        
        $rows = QueryBuilder::for($queryBuilder)->defaultSorts($defaultSort)
            ->allowedSorts($allowedSorts)
            ->allowedFilters($allowedFilters)
            ->allowedIncludes($allowedIncludes)
            ->with($withs)
            ->withCount($counts)
            ->paginate($rowsPerPage);
        
        $parseResults = $this->parseResults($rows->items());
        $rows->setCollection(collect($parseResults));
        
        return $rows;
    }

    /**
     * Parses the results of a getRows query and converts it into a manageable array with the proper rendering.
     *
     * @param Collection $rows
     *
     * @return array
     */
    public function parseResults($rows)
    {
        $results = [];
        
        // convert the resulting set into arrays
        foreach ($rows as $item) {
            
            // iterate over the included and related columns
            $arr = [];
            $this->parseOnTableColumns($item, $arr);
            $results[] = $arr;
        }
        
        return $results;
    }

    /**
     * Goes through all related columns and sets the proper values for this row.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param array $outputRow
     */
    public function parseOnTableColumns($item, array &$outputRow)
    {
        if (method_exists($item, 'presenter')) {
            $item = $item->presenter();
        }
        
        $columns = $this->columnFactory->getColumns();
        $includedColumns = $this->columnFactory->getIncludedColumns($this->fieldFactory->getEditFields());
        
        // loop over both the included columns
        foreach ($includedColumns as $field => $col) {
            
            // $attributeValue = $item->getAttribute($field);
            if (strpos($field, '.') !== false) {
                $attributeValue = [];
                list ($relation, $key) = explode('.', $field);
                Arr::set($attributeValue, $field, $item->$relation->$key);
            } else {
                $attributeValue = $item->$field;
            }
            
            // if this column is in our objects array, render the output with the given value
            if (isset($columns[$field])) {
                $outputRow[$field] = [
                    'raw' => $attributeValue,
                    'rendered' => $columns[$field]->renderOutput($attributeValue, $item)
                ];
            } else {
                $outputRow[$field] = [
                    'raw' => $attributeValue,
                    'rendered' => $attributeValue
                ];
            }
        }
    }
    
    /**
     * 获取查询过滤器
     *
     * @param array $parameters
     * @return array
     */
    protected function parseAllowedQueryFilters(array $parameters)
    {
        if (! isset($parameters['filtering']['allowed'])) {
            return [];
        }
    
        $allowedFilters = $parameters['filtering']['allowed'];
        return ($allowedFilters instanceof \Closure) ? $allowedFilters() : $allowedFilters;
    }
}
