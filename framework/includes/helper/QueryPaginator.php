<?php

Class QueryPaginator {

    public $start;
    public $pageLimit;
    public $pageNow;
    public $totalData;
    public $currentPageTotalData;
    public $totalPage;
    public $bottomPageData;
    public $topPageData;
    public $listData;
    public $searchKey;
    public $db;
    public $fetchType;

    public function __construct()
    {
        $this->db = new QueryBuilder();
        $this->pageLimit = 10;
        $this->fetchType = '';
    }

    public function setNum($pageNumber)
    {
        if (!isset($pageNumber) || is_null($pageNumber))
        {
            $this->start = 0;
            $this->pageNow = 1;
        }
        else
        {
            if ($pageNumber < 1) 
            {
                $this->start = 0;
                $this->pageNow = 1;
            }
            else 
            {
                $this->pageNow = (int) $pageNumber;
                $this->start = ($this->pageNow - 1) * $this->pageLimit;
            }
        }

        return $this;
    }

    public function getData($sql)
    {
        $this->db->sth = $this->db->con->prepare($sql);
        $this->db->execBuilder();

        if ($this->fetchType == 'ASSOC')
            $this->listData = $this->db->sth->fetchAll(PDO::FETCH_ASSOC);
        else
            $this->listData = $this->db->sth->fetchAll(PDO::FETCH_OBJ);

        return $this;
    }

    public function getDataCount($sql)
    {
        // query should have COUNT(fieldname) AS total

        $this->db->sth = $this->db->con->prepare($sql);
        $this->db->execBuilder(); // what the difference between using $this->db->execute();
        $totalData = $this->db->sth->fetch(PDO::FETCH_OBJ);
        
        $this->totalData = $totalData->total;

        return $this;
    }

    public function setSearchKey($searchKey)
    {
        $this->searchKey = $searchKey;
        return $this;
    }

    public function setup()
    {
        // set current page total data
        $this->currentPageTotalData = count($this->listData);
        
        // set total page
        $this->totalPage = (int) ceil($this->totalData / $this->pageLimit);
        
        // set bottomPageData
        $this->bottomPageData = (($this->pageNow - 1) * $this->pageLimit) + 1;
        
        // set topPageData
        $this->topPageData = (($this->pageNow - 1) * $this->pageLimit) + $this->currentPageTotalData;

        $this->db = null;
    }
}

$appPaginator = new QueryPaginator();

return $appPaginator;