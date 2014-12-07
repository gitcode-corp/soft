<?php

namespace Soft\Db\Query;

class SQLParserUtils
{
    /**
     * @var string 
     */
    private $lastClause;
    
    /**
     * @var string
     */
    private $queryType;
    
    /**
     * @var string
     */
    private $sql = "";
    
    
    public function parse(array $sqlParts)
    {
        foreach ($sqlParts as $type => $data) {    
            if (!$data) {
                continue;
            }

            switch($type) {
                case QueryBuilder::SQL_CLAUSE_SELECT :
                    $this->addSelectClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_FROM :
                    $this->addFromClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_WHERE :
                    $this->addWhereClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_GROUP_BY :
                    $this->addGroupByClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_HAVING :
                    $this->addHavingClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_JOIN:
                    $this->addJoinClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_INSERT :
                    $this->addInsertClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_UPDATE :
                    $this->addUpdateClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_DELETE :
                    $this->addDeleteClause($data);
                    break;
                case QueryBuilder::SQL_CLAUSE_SET :
                    $this->addValuesClause($data);
                    break;
            }
            
            $this->lastClause = $type;
        }
        
        $offset = null;
        if (isset($sqlParts[QueryBuilder::SQL_CLAUSE_OFFSET])) {
            $offset = array_shift($sqlParts[QueryBuilder::SQL_CLAUSE_OFFSET])['condition'];
        }
        
        $limit = null;
        if (isset($sqlParts[QueryBuilder::SQL_CLAUSE_LIMIT])) {
            $limit = array_shift($sqlParts[QueryBuilder::SQL_CLAUSE_LIMIT])['condition'];
        }
        
        if ($offset || $limit) {
            $this->addLimitClause($offset, $limit);
        }
        
            
        $this->glueSql(";");
        
        return $this->sql;
    }
    
    private function addSelectClause(array $parts)
    {
        $this->queryType = QueryBuilder::SQL_CLAUSE_SELECT;
        
        $this->glueSql(QueryBuilder::SQL_CLAUSE_SELECT);

        $selectClause = [];
        foreach ($parts as $data) {
            if ($data['columns']) {
                $selectClause[] = implode(", ", $data['columns']);
            } else {
                $selectClause[] = "*";
            }
        }

        $this->glueSql(implode(", ", $selectClause));
    }
    
    private function addFromClause(array $parts)
    {
        $this->glueSql(QueryBuilder::SQL_CLAUSE_FROM);
        
        $fromClause = [];
        foreach ($parts as $data) {
            $alias = " ";
            if ($data['alias']) {
                $alias = " AS " . $data['alias'];
            }
            
            $fromClause[] = $data['table'] . $alias;
        }
        
        $this->glueSql(implode(", ", $fromClause));
    }
    
    private function addWhereClause(array $parts)
    {
        $this->glueSql(QueryBuilder::SQL_CLAUSE_WHERE);
        
        $whereClause = [];
        foreach ($parts as $data) {
            $whereClause[] = $data['condition'];
        }
        
        $this->glueSql(implode(" AND ", $whereClause));
    }
    
    private function addGroupByClause(array $parts)
    {
        $this->glueSql(QueryBuilder::SQL_CLAUSE_GROUP_BY);
        
        $groupByClause = [];
        foreach ($parts as $data) {
            $groupByClause[] = $data['condition'];
        }
        
        $this->glueSql(implode(", ", $groupByClause));
    }
    
    private function addHavingClause(array $parts)
    {
        $this->glueSql(QueryBuilder::SQL_CLAUSE_HAVING);
        
        $havingClause = [];
        foreach ($parts as $data) {
            $havingClause[] = $data['condition'];
        }
        
        $this->glueSql(implode(", ", $havingClause));
    }
    
    private function addJoinClause(array $parts)
    {
        foreach ($parts as $data) {
            $this
                ->glueSql($data['type'])
                ->glueSql($data['table'])
                ->glueSql('AS')
                ->glueSql($data['alias'])
            ;
            
            if ($data['condition']) {
                $this
                    ->glueSql('ON')
                    ->glueSql($data['condition']);
            }
        }
    }
    
    private function addInsertClause(array $parts)
    {
        $this->queryType = QueryBuilder::SQL_CLAUSE_INSERT;
        
        $data = array_shift($parts);
        $this
            ->glueSql(QueryBuilder::SQL_CLAUSE_INSERT)
            ->glueSql("INTO")
            ->glueSql($data['table'])
            ;
    }
    
    private function addUpdateClause(array $parts)
    {
        $this->queryType = QueryBuilder::SQL_CLAUSE_UPDATE;
        
        $data = array_shift($parts);
        $this
            ->glueSql(QueryBuilder::SQL_CLAUSE_UPDATE)
            ->glueSql($data['table'])
            ;
    }
    
    private function addDeleteClause(array $parts)
    {
        $this->queryType = QueryBuilder::SQL_CLAUSE_DELETE;
        
        $data = array_shift($parts);
        
        $this
            ->glueSql(QueryBuilder::SQL_CLAUSE_DELETE)
            ->glueSql(QueryBuilder::SQL_CLAUSE_FROM)
            ->glueSql($data['table'])
            ;
    }
    
    private function addValuesClause(array $data)
    {
        if ($this->queryType === QueryBuilder::SQL_CLAUSE_INSERT) {
            $this->addInsertValues($data);   
        } elseif ($this->queryType === QueryBuilder::SQL_CLAUSE_UPDATE) {
            $this->addUpdateValues($data);
        }
    }
    
    private function addInsertValues(array $parts)
    {
        $values = [];
        $columns = [];
        foreach ($parts as $data) {
            $columns[] = $data['column'];
            $values[] = "'" . $data['value'] . "'";
        }
        
        $this->glueSql("(");
        $this->glueSql(implode(", ",$columns));
        $this->glueSql(")");
        $this->glueSql(QueryBuilder::SQL_CLAUSE_VALUES);
        $this->glueSql("(");
        $this->glueSql(implode(", ", $values));
        $this->glueSql(")");
    }
    
    private function addUpdateValues(array $parts)
    {
        $this->glueSql("SET");
        foreach ($parts as $data) {
            $values[] = $data['column'] . "=" . "'" . $data['value'] . "'";  
        }
        
        $this->glueSql(implode(", ", $values));
    }
    
    private function addLimitClause($offset, $limit)
    {
        $this->glueSql(QueryBuilder::SQL_CLAUSE_LIMIT);
        
        if ($offset !== null) {
            $this->glueSql($offset . ", " . $limit);
        } else {
            $this->glueSql($limit);
        }
    }
    
    /**
     * @param string $part
     * @return QueryBuilder
     */
    private function glueSql($part)
    {
        $this->sql .= " " . $part . " ";
        
        return $this;
    }
}