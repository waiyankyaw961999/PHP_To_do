<?php

class DB
{
    private static $dbh, $sql, $res;
    public function __construct()
    {
        self::$dbh =  new PDO("mysql:host=localhost;dbname=todo", "root", "" );
        self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query($params=[]) {
        self::$res = self::$dbh->prepare(self::$sql);
        self::$res->execute($params);
    }

    public static function table($table)
    {
        self::$sql = "select * from $table";
        $db = new DB();
        return $db;
    }
    public function get()
    {
        $this->query();
        return self::$res->fetchAll(PDO::FETCH_OBJ);
    }
    public function getOne()
    {
        $this->query();
        return self::$res->fetch(PDO::FETCH_OBJ);
    }

    public function count() {
        $this->query();
       return self::$res-> rowCount();
    }

    public function where($name,$op,$value="")
    {
        if(func_num_args() === 2)
        {
           self::$sql .= " where $name= '$op'";
        }
        else
        {
            self::$sql .=" where $name $op '$value'";
        }
        return $this;
    }
    public function andWhere($name,$op,$value="")
    {
        if(func_num_args() === 2)
        {
            self::$sql .= " and $name='$op'";
        }
        else
        {
            self::$sql .=" and $name $op '$value'";
        }
        return $this;
    }

    public function orWhere($name,$op,$value="")
    {
        if(func_num_args() === 2)
        {
            self::$sql .= " or $name= '$op'";
        }
        else
        {
            self::$sql .=" or $name $op '$value'";
        }
        return $this;
    }
    public function orderBy($name, $order)
    {
        self::$sql .= " ORDER by $name $order";
        return $this;
    }
    public static function create($table,$array)
    {
         $cols =  implode(',',array_keys($array));
         $values = "";
         foreach($array as $value)
         {
             $values .= "?,";
         }
         $temp = substr_replace($values ,"", -1);

         self::$sql = "insert into $table ($cols) values ($temp)";
         $db = new self();
         $db->query(array_values($array));
         $lastInsertId = self::$dbh->lastInsertId();
         return DB::table($table)->where('id',$lastInsertId)->getOne();
    }
    public static function update($table,$data,$id)
    {
        $count = 1;
        $updateString = "";
        foreach($data as $key => $val)
        {
            $updateString .= "$key=?";
            if ($count <count($data))
            {
                $updateString .=",";
            }
            $count++;
        }
      self::$sql= "update $table set $updateString where id='$id'";
        $db = new self();
        $db->query(array_values($data));
        return DB::table($table)->where('id',$id)->getOne();

    }
    public static function delete($table, $id)
    {
        self::$sql = "delete from $table where id='$id'";
        $db = new self();
        $db->query();
        return true;
    }
    public function paginate($numRecords=5)

    {
        $totalRecords = $this->count();
        $totalPages = (int)ceil($totalRecords/$numRecords);
        $totalPages = $totalPages === 0 ? 1 : $totalPages;

        $page_no = isset($_GET['page']) ? $_GET['page'] : 1;
        $page_no = $page_no < 1 ? 1: $page_no;
        $page_no = $page_no > $totalPages ? $totalPages:$page_no;
        $index = ($page_no-1)*$numRecords;

        self::$sql .= " limit $index, $numRecords";
        $data = $this->get();

        $next_no = $page_no == $totalPages ? $page_no:$page_no +1 ;
        $next_page = "?page=$next_no";
        $prev_no = $page_no == 1 ? $page_no : $page_no -1;
        $prev_page = "?page=$prev_no";

        return [
            "data" => $data,
            "current_page_no" => $page_no,
            "total_pages" => $totalPages,
            "totalRecords"=> $totalRecords,
            "prev_page" => $prev_page,
            "next_page" => $next_page
        ];
    }
}


