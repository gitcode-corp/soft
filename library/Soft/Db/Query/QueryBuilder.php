<?php

namespace Soft\Db\Query;

use Soft\Db\Driver\Connection\Connection;

class QueryBuilder
{
    const SQL_CLAUSE_SELECT = "SELECT";
    const SQL_CLAUSE_INSERT = "INSERT";
    const SQL_CLAUSE_UPDATE = "UPDATE";
    const SQL_CLAUSE_DELETE = "DELETE";
    
    const SQL_CLAUSE_JOIN_INNER = "INNER JOIN";
    const SQL_CLAUSE_JOIN_LEFT = "LEFT JOIN";
    const SQL_CLAUSE_JOIN = "JOIN";
    
    const SQL_CLAUSE_FROM = "FROM";
    const SQL_CLAUSE_WHERE = "WHERE";
    const SQL_CLAUSE_GROUP_BY = "GROUP BY";
    const SQL_CLAUSE_HAVING = "HAVING";
    const SQL_CLAUSE_ORDER_BY = "ORDER BY";
    const SQL_CLAUSE_LIMIT = "LIMIT";
    const SQL_CLAUSE_OFFSET = "OFFSET";
    const SQL_CLAUSE_VALUES = "VALUES";
    const SQL_CLAUSE_SET = "SET";

    /**
     *
     * @var Connection
     */
    private $connection;
    
    /**
     *
     * @var string
     */
    private $queryType = null;
    
     /**
     * @var array The array of SQL parts collected.
     */
    private $sqlParts = array(
        self::SQL_CLAUSE_SELECT  => array(),
        self::SQL_CLAUSE_INSERT => array(),
        self::SQL_CLAUSE_UPDATE => array(),
        self::SQL_CLAUSE_DELETE=> array(),
        self::SQL_CLAUSE_FROM    => array(),
        self::SQL_CLAUSE_JOIN    => array(),
        self::SQL_CLAUSE_SET     => array(),
        self::SQL_CLAUSE_WHERE   => array(),
        self::SQL_CLAUSE_GROUP_BY => array(),
        self::SQL_CLAUSE_HAVING  => array(),
        self::SQL_CLAUSE_ORDER_BY => array(),
        self::SQL_CLAUSE_LIMIT => array(),
        self::SQL_CLAUSE_OFFSET => array(),
    );
    
    /**
     * @var string
     */
    private $sql = "";
   
    /**
     * @var array
     */
    private $values = [];
    
    /**
     * @var array
     */
    private $parameters = [];
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * 
     * @param array $columns
     * @return QueryBuilder
     */
    public function select(array $columns = [])
    {
        $this->sqlParts[self::SQL_CLAUSE_SELECT][] = ['columns' => $columns];
        
        return $this;
    }
    
    /**
     * 
     * @param string $table
     * @param string $alias
     * @return QueryBuilder
     */
    public function from($table, $alias = null)
    {
        $this->sqlParts[self::SQL_CLAUSE_FROM][] = 
            [
                'table' => $table, 
                'alias' => $alias
            ];
        
        return $this;
    }
    
    public function where($condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_WHERE][] = ['condition' => $condition];
        
        return $this;
    }
    
    public function groupBy($condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_GROUP_BY][] = ['condition' => $condition];
        
        return $this;
    }
    
    public function having($condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_HAVING] = ['condition' => $condition];

        
        return $this;
    }
    
    /**
     * 
     * @param string $table
     * @param string $alias
     * @param string $condition
     * @return QueryBuilder
     */
    public function innerJoin($table, $alias, $condition = null)
    {
        $this->join(self::SQL_CLAUSE_JOIN_INNER, $table, $alias, $condition);
        
        return $this;
    }
    
    /**
     * 
     * @param string $table
     * @param string $alias
     * @param string $condition
     * @return QueryBuilder
     */
    public function leftJoin($table, $alias, $condition = null)
    {
        $this->join(self::SQL_CLAUSE_JOIN_LEFT, $table, $alias, $condition);
        
        return $this;
    }
    
    /**
     * 
     * @param string $type
     * @param string $table
     * @param string $alias
     * @param string $condition
     * @return \Soft\Db\Query\QueryBuilder
     */
    private function join($type, $table, $alias, $condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_JOIN][] =  
            [
                'type'=>$type,
                'table' => $table, 
                'alias' => $alias, 
                'condition' => $condition
            ];
        
        return $this;
    }
    
    /**
     * 
     * @param string $condition
     * @return QueryBuilder
     */
    public function orderBy($condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_ORDER_BY][] = ['condition' => $condition];
        
        return $this;
    }
    
    /**
     * 
     * @param string $condition
     * @return QueryBuilder
     */
    public function limit($condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_LIMIT][] = ['condition' => $condition];
        
        return $this;
    }
    
    /**
     * 
     * @param string $condition
     * @return QueryBuilder
     */
    public function offset($condition)
    {
        $this->sqlParts[self::SQL_CLAUSE_OFFSET][] = ['condition' => $condition];
        
        return $this;
    }
    
    /**
     * 
     * @param string $table
     * @return QueryBuilder
     */
    public function insert($table)
    {
        $this->sqlParts[self::SQL_CLAUSE_INSERT][] = ['table' => $table];
        
        return $this;
    }
    
    /**
     * 
     * @param string $table
     * @return QueryBuilder
     */
    public function update($table)
    {
        $this->sqlParts[self::SQL_CLAUSE_UPDATE][] = ['table' => $table];
        
        return $this;
    }
    
    /**
     * 
     * @param string $table
     * @return QueryBuilder
     */
    public function delete($table)
    {
        $this->sqlParts[self::SQL_CLAUSE_DELETE][] = ['table' => $table];
        
        return $this;
    }
    
    /**
     * 
     * @param string $column
     * @param string $value
     * @return QueryBuilder
     */
    public function setValue($column, $value)
    {
        if (isset($this->values[$column])) {
            $message = 'Cannot override value "' . $column . '"';
            throw new Exception\ValueException($message);
        }
        
        $this->values[$column] = $value;
        $this->sqlParts[self::SQL_CLAUSE_SET][] = ['column' => $column, 'value' => $value];
        
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @return QueryBuilder
     * @throws Exception\ParameterException
     */
    public function setParameter($name, $value)
    {
        if (isset($this->parameters[$name])) {
            $message = 'Cannot override paremeter "' . $name . '"';
            throw new Exception\ParameterException($message);
        }
        
        $this->parameters[$name] = $value;
        
        return $this;
    }
    
    /**
     * @return \Soft\Db\Driver\Statement
     */
    public function execute()
    {
        $this->sql = (new SQLParserUtils())->parse($this->sqlParts);

        $stm = $this->connection->prepare($this->sql);
        
        foreach ($this->parameters as $name=>$value) {
            $stm->bindValue($name, $value);
        }
        
        $stm->execute();
        $stm->setFetchMode(\PDO::FETCH_ASSOC);
        
        return $stm;
    }
    
    /**
     * 
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }
    
    /**
     * 
     * @param type $type
     * @throws Exception\QueryTypeException
     */
    private function setQueryType($type)
    {
        if ($this->queryType) {
            $message = "Query type is already set to " . $this->queryType;
            $message .= " Type can be set only once.";
            throw new Exception\QueryTypeException($message);
        }
        
        $this->queryType = $type;
    }
}

