<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\DB2QueryBuilder;
use App\Models\Core\DB2Connection;
use App\Models\Core\DB2Grammar;
use App\Models\Core\DB2Processor;

use PDO;

abstract class DB2Model extends Model
{

	public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $table = str_replace('TABLA_TERCEROS',env('TRILLASOFT_TABLA_TERCEROS','FDCFENIX01.XCOMAEGRL'),$this->table);
        $table = str_replace('SCHEMA',config('app.schema'),$table);
        $this->setTable($table);
    }

    /**
     * Ajusta un array y ejecuta fill
     */
    public function fillit(array $attributes)
    {
        $Attrs = array_diff_key($this->attributes, array_flip($this->getGuarded()));
        $Filler = array_intersect_key($attributes, $Attrs);
        return $this->fill($Filler);
    }

    protected function newBaseQueryBuilder()
	{
		$Connection = new DB2Connection(new PDO('sqlite::memory:'));
		$Gramar 	= new DB2Grammar;
		$Processor 	= new DB2Processor;

		return new DB2QueryBuilder($Connection, $Gramar, $Processor, $this);
	}

	public function bladeCompile($value, array $args = array())
    {
        $generated = \Blade::compileString($value);
        ob_start() and extract($args, EXTR_SKIP);
        try
        {
            eval('?>'.$generated);
        }catch (\Exception $e)
        {
            ob_get_clean(); throw $e;
        }

        $content = ob_get_clean();
        return $content;
    }
}