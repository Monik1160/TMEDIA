<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UniqueMultipleColumns implements Rule
{
    protected $extrafiels;
    protected $tableName;
    protected $firstElement;
    protected $secondElement;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($param, $table, $column1, $column2)
    {
        $this->extrafiels = $param;
        $this->tableName = $table;
        $this->firstElement = $column1;
        $this->secondElement = $column2;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bus = DB::table($this->tableName)->where($this->firstElement, $value)->where($this->secondElement, $this->extrafiels)->count();

        if ($bus == 0) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya la placa existe. Cambiar tipo o numero.';
    }
}
