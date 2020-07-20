<?php

class QueryBuilder {

    public $con; // pdo connection
    public $sql; // sql for query builder
    public $sth; // pdo statement
    public $whereParam; // pdo sql parameter
    public $updateParam; // pdo sql parameter
    public $result; // data result from query
    public $singleRow; // data result from query, single row
    public $counter; // variable counter
    public $queryAction; // select, update, insert, delete
    public $queryCounter;

    public function __construct()
    {
        $this->con = new PDO('mysql:dbname='.DBNAME.';host='.DBHOST.';port='.DBPORT, DBUSER, DBPASS);        
        // $this->con = new PDO('mysql:dbname='.DBNAME.';host='.DBHOST.';port='.DBPORT, DBUSER, DBPASS); //koneksi ke server
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$this->con->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);

        $this->reset();
    }

    public function reset()
    {
        $this->counter = 1;
        $this->whereParam = null;
        $this->updateParam = null;
        $this->queryCouter = 0;
        $this->err = 0;
        $this->tbl = '';
        // look like $this->sql not need to be reseted it will reseted automatically
        // $this->sql = '';
        $this->result = null;
        $this->singleRow = null;

        return $this;
    }

    public function beginTransaction()
    {
        $this->con->beginTransaction();
    }

    public function commit()
    {
        $this->con->commit();
    }

    public function rollBack()
    {
        $this->con->rollBack();
    }

    public function execBuilder()
    {
        try
        {
            $this->sth->execute();
            $this->queryCounter++;
        }
        catch (PDOException $e)
        {
            $this->err = 1;
            
            echo $this->sql.'<br/>';
            echo 'Bad Query (System Halt), Query Counter['.$this->queryCounter.']: ' . $e->getMessage();
            
            
            if ($this->con->inTransaction())
                $this->con->rollBack();
                
            app()->stop();
            exit;
        }
    }

    public function execute($dataType = 'class')
    {
        try
        {
            $this->sth = $this->con->prepare($this->sql);

            $count = 1;
            if ($this->queryAction == 'update')
            {
                if ($this->updateParam != null)
                {
                    foreach ($this->updateParam as $key => $field)
                    {
                        $this->sth->bindValue(':var'.$count, $field, PDO::PARAM_STR);
                        $count++;
                    }
                }
            }
            
            if ($this->whereParam != null)
                foreach ($this->whereParam as $key => $field)
                    $this->sth->bindValue(':'.$key, $field, PDO::PARAM_STR);

            $this->execBuilder();

            if ($this->queryAction == 'select')
            {
                if ($dataType == 'class')
                    $this->result = $this->sth->fetchAll(PDO::FETCH_CLASS);
                else
                    $this->result = $this->sth->fetchAll(PDO::FETCH_ASSOC);

                if ($this->sth->rowCount() > 0)
                    $this->singleRow = $this->result[0];
                else
                    $this->result = null;
            }

            $this->sql = '';
            $this->queryAction = '';
            $this->queryCounter++;
            $this->counter = 1;
        }
        catch (PDOException $e)
        {
            $this->err = 1;
            echo $e->getMessage();
            if ($this->con->inTransaction())
                $this->con->rollBack();

            app()->stop();
        }

        return $this;
    }

    public function insert($table, $data)
    {
        $this->tbl = $table;

        $sql = "INSERT INTO ".$table;
        $fields = "";
        $values = "";
        $count = 0;
        foreach ($data as $key => $field)
        {
            if ($count == 0)
            {
                $fields .= $key;
                $values .= ":" . $key;
            }
            else
            {
                $fields .= "," . $key;
                $values .= ", :" . $key;
            }

            $count++;
        }
        
        $sql .= "(" . $fields . ") VALUES (" . $values . ")";
       
        $this->sth = $this->con->prepare($sql);
        foreach ($data as $key => $field)
        {
            $this->sth->bindValue(':'.$key, $field, PDO::PARAM_STR);
        }
        
        $this->execBuilder();
    }

    public function select($field = '*')
    {
        $this->reset(); // select perlu di reset, soalnya mengeluarkan hasil
        $this->sql .= "SELECT ".$field;
        $this->queryAction = 'select';
        return $this;
    }

    public function from($table = '')
    {
        $this->sql .= " FROM ".$table;
        return $this;
    }

    public function update($table = '', $data)
    {
        $this->sql .= "UPDATE ".$table." SET ";
        $count = 0;
        foreach ($data as $key => $field)
        {
            if ($count == 0)
                $this->sql .= $key." = :var".$this->counter;
            else
                $this->sql .= ", ".$key." = :var".$this->counter;

            $count++;
            $this->counter++;
        }

        $this->queryAction = 'update';
        $this->updateParam = $data;

        return $this;
    }

    public function delete($table = '')
    {
        $this->sql .= "DELETE FROM ".$table;
        $this->queryAction = 'delete';
        return $this;
    }

    public function join($table = '', $cond = '', $type = '')
    {
        $this->sql .= $type." JOIN ".$table." ON ".$cond;
        return $this;
    }

    public function where($cond = '', $param = null)
    {
        $this->sql .= " WHERE ".$cond;
        $this->whereParam = $param;
        return $this;
    }

    public function orderBy($orderBy = '')
    {
        if ($orderBy != '')
            $this->sql .= " ORDER BY ".$orderBy;

        return $this;
    }

    public function limit($start = 0, $limit = 0)
    {
        $this->sql .= " LIMIT ".$start;
        if ($limit > 0)
            $this->sql .= ", ".$limit;

        return $this;
    }

    public function query($sql)
    {
        $this->queryAction = 'select';
        $this->sql = $sql;
        return $this;
    }

    public function queryUpdate($sql)
    {
        $this->queryAction = 'update';
        $this->sql = $sql;
        return $this;
    }

    public function getWhere($tbl = '', $cond, $param = null, $reset = true)
    {
        if ($reset)
            $this->reset();
            
        if ($this->sql == '')
            $this->select('*')->from($tbl)->where($cond, $param)->execute();
        else
            $this->from($tbl)->where($cond, $param)->execute();

        return $this;
    }

    public function getAll($tbl = '', $cond = null, $param = null) : array
    {
        // ini harus diperbaiki
        $this->reset();

        if ($cond == null)
        {
            if ($this->sql == '')
                $this->select('*')->from($tbl)->execute();
            else
                $this->from($tbl)->execute();
        }
        else
        {
            if ($this->sql == '')
                $this->select('*')->from($tbl)->where($cond, $param)->execute();
            else
                $this->from($tbl)->where($cond, $param)->execute();
        }

        return ($this->getResult() == null) ? [] : $this->getResult();
    }

    public function colIncrement($tbl = '', $fieldName = '', $inc = 1)
    {
        $this->sql = "UPDATE ".$tbl." SET ".$fieldName." = ".$fieldName." + ".$inc;
        return $this;
    }

    public function colDecrement($tbl = '', $fieldName = '', $inc = 1)
    {
        $this->sql = "UPDATE ".$tbl." SET ".$fieldName." = ".$fieldName." - ".$inc;
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getRow()
    {
        return $this->singleRow;
    }
}

$appQueryBuilder = new QueryBuilder();

return $appQueryBuilder;