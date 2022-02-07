<?php

require_once 'globals.php';


class Table
{

    public $sql;
    public const RANGE_ERR = 'Le nombre de boutons dans la pagination doit être strictement positif';
    public const PAGE_ERR = 'Le nombre de lignes par page doit être strictement positif';
    public const CURRENT_PAGE_ERR = 'Le numéro de page actuel doit être strictement positif';
    private $query;
    public $offset;

    public function __construct(string $sql)
    {
        strpos(strtolower($sql), "select") === 0 ?
            $this->sql = $sql : throw new Exception('Mauvaise requête SQL');

        $this->query = $_GET;
    }

    public function connection()
    {
        $dbh = new PDO(
            'mysql:host=' . SERVER . ';port=' . PORT . ';dbname=' . DBB . ';charset=utf8',
            USER,
            PASS,
            PDO_OPTIONS
        );

        return $dbh;
    }

    public function get_columns_names()
    {
        $dbh = $this->connection();

        $stmt = $dbh->prepare($this->sql);

        $stmt->execute();

        $donnees = array();

        $columnCount = $stmt->columnCount();

        for ($i = 0; $i < $columnCount; $i++) {
            $columnInfo = $stmt->getColumnMeta($i);
            array_push($donnees, $columnInfo['name']);
        }

        return $donnees;
    }

    public function set_offset(int $currentPage, int $limit = 10){
        ($currentPage > 0 && $limit > 0) ?
        $this->offset = 0 + (($currentPage - 1) * $limit) :
        throw new Exception('Offset refusé car numéro de page incorrect, ou nombre de queries par page invalide');
    }

    public function get_table_infos(string $sortName = null, string $sortBy = null, int $offset = null, int $limit = 10)
    {
        $dbh = $this->connection();
        $request = $this->sql;
        $offset = $this->offset;


        $colNames = $this->get_columns_names();
        $sortCondition = ['asc', 'ASC', 'desc', 'DESC'];

        (is_null($sortName) || empty($sortName) || !in_array(trim($sortName), $colNames)) ?
            $sortName = $colNames[0] :
            $sortName = trim($sortName);

        (is_null($sortBy) || empty($sortBy) || !in_array(trim($sortBy), $sortCondition)) ?
            $sortBy = 'ASC' :
            $sortBy = trim($sortBy);

        $request .= " ORDER BY $sortName $sortBy";

        (!is_null($limit) && !empty($limit) && $limit > 0) ?
            ((isset($offset) && !is_null($offset) && !empty($offset) && $offset > 0) ? $request .= " LIMIT $offset, $limit" : $request .= " LIMIT $limit") :
            '';


        $stmt = $dbh->prepare($request);

        $stmt->execute();

        $donnees = $stmt->fetchAll(PDO::FETCH_NUM);

        return $donnees;
    }

    public function get_last_link(int $perPage): int
    {
        $dbh = $this->connection();
        $stmt = $dbh->prepare($this->sql);
        $stmt->execute();

        return ($perPage > 0) ? ceil($stmt->rowCount() / $perPage) :
            throw new Exception($this::PAGE_ERR);
    }

    private function get_sides_links(int $range = 8): int
    {
        return ($range > 0) ? floor($range / 2) :
            throw new Exception($this::RANGE_ERR);
    }

    private function get_break_link_start(int $range = 8): int
    {
        return ($range > 0) ?
            $this->get_sides_links($range) + 1 :
            throw new Exception($this::RANGE_ERR);
    }

    private function get_links_before_break_start(int $range): int
    {
        return ($range > 0) ? $range + 1 :
            throw new Exception($this::RANGE_ERR);
    }

    private function get_break_link_end(int $perPage, int $range): int
    {
        ($perPage > 0) ? $last = $this->get_last_link($perPage) :
            throw new Exception($this::PAGE_ERR);
        ($range > 0) ? $side = $this->get_sides_links($range) :
            throw new Exception($this::RANGE_ERR);
        return $last - $side;
    }

    private function get_links_before_active(int $currentPage, int $range): int
    {
        ($range > 0) ? $side = $this->get_sides_links($range) :
            throw new Exception($this::RANGE_ERR);
        return ($currentPage > 0) ? $currentPage - $side :
            throw new Exception($this::CURRENT_PAGE_ERR);
    }

    private function get_links_after_active(int $currentPage, int $range): int
    {
        ($range > 0) ? $side = $this->get_sides_links($range) :
            throw new Exception($this::RANGE_ERR);
        return ($currentPage > 0) ? $currentPage + $side :
            throw new Exception($this::CURRENT_PAGE_ERR);
    }

    private function get_links_after_break_end(int $perPage, int $range): int
    {
        ($perPage > 0) ? $last = $this->get_last_link($perPage) :
            throw new Exception($this::PAGE_ERR);
        return ($range > 0) ? $last - $range :
            throw new Exception($this::RANGE_ERR);
    }



    public function get_previous_links(int $currentPage, int $perPage = 10, string $nameGet = 'page')
    {
        $print = '';

        $first = 1;

        if ($currentPage == $first) {
            $toFirstPage = 'disabled';
        } else {
            $query[$nameGet] = '1';
            $query_result = http_build_query($query);
            $toFirstPage = '" href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '"';
        }

        if ($currentPage == $first) {
            $toPreviousPage = 'disabled';
        } else {
            $query[$nameGet] = strval($currentPage - 1);
            $query_result = http_build_query($query);
            $toPreviousPage = '" href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '"';
        }

        $print .= ' <a class="icon item ' . $toFirstPage . '">
                        <i class=" angle double left icon"></i>
                    </a>
                    <a class="icon item ' . $toPreviousPage . '">
                        <i class="angle left icon"></i>
                    </a>';

        return $print;
    }

    public function get_next_links(int $currentPage, int $perPage = 10, string $nameGet = 'page')
    {

        $print  = '';

        $last = $this->get_last_link($perPage);

        if ($currentPage == $last) {
            $toNextPage = 'disabled';
        } else {
            $query[$nameGet] = strval($currentPage + 1);
            $query_result = http_build_query($query);
            $toNextPage = '" href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '"';
        }

        if ($currentPage == $last) {
            $toLastPage = 'disabled';
        } else {
            $query[$nameGet] = strval($last);
            $query_result = http_build_query($query);
            $toLastPage = '" href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '"';
        }

        $print .= '   <a class="icon item ' . $toNextPage . '">
                            <i class="angle right icon"></i>
                        </a>
                        <a class="icon item ' . $toLastPage . '">
                            <i class="angle double right icon"></i>
                        </a>';

        return $print;
    }


    public function print_links_for_current_page(int $currentPage, int $range = 8, int $perPage = 10, string $nameGet = 'page')
    {
        $print = $this->get_previous_links($currentPage, $perPage);

        $first = 1;
        $last = $this->get_last_link($perPage);

        $break_point_a = $this->get_break_link_start($range);
        $break_point_b = $this->get_break_link_end($perPage, $range);

        $before_break_a = $this->get_links_before_break_start($range);
        $after_break_b = $this->get_links_after_break_end($perPage, $range);

        $before_active = $this->get_links_before_active($currentPage, $range);
        $after_active = $this->get_links_after_active($currentPage, $range);

        if ($currentPage <= $break_point_a) {
            for ($i = $first; $i <= $before_break_a; $i++) {
                $this->query[$nameGet] = $i;
                $query_result = http_build_query($this->query);
                $active = ($currentPage == $i) ? ' active' : ' ';
                $print .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '" class="item ' . $active . '">' . $i . '</a>';
            }
        } elseif ($currentPage > $break_point_a && $currentPage <= $break_point_b) {
            for ($i = $before_active; $i <= $after_active; $i++) {
                $this->query[$nameGet] = $i;
                $query_result = http_build_query($this->query);
                $active = ($currentPage == $i) ? ' active' : ' ';
                $print .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '" class="item ' . $active . '">' . $i . '</a>';
            }
        } else {
            for ($i = $after_break_b; $i <= $last; $i++) {
                $this->query[$nameGet] = $i;
                $query_result = http_build_query($this->query);
                $active = ($currentPage == $i) ? ' active' : ' ';
                $print .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . $query_result . '" class="item ' . $active . '">' . $i . '</a>';
            }
        }

        $print .= $this->get_next_links($currentPage, $perPage);

        return $print;
    }
}
