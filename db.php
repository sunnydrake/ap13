<?php
class cDB
{
    public const user = 'root';
    public const pass = 'rootpass';
    public const host = 'localhost';
    public const db = 'AP13_DB';
    public const charset = 'utf8';
    public $link;

    public function __construct()
    {
        $this->link = mysqli_connect(self::host, self::user, self::pass, self::db);
        if (!$this->link) {
            echo "Error MySQLi connect db" . PHP_EOL;
            echo "Error code: " . mysqli_connect_errno() . PHP_EOL;
            echo "Error text: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        mysqli_set_charset ( $this->link ,self::charset);
    }

    public function __destruct()
    {
        if ($this->link)
            mysqli_close($this->link);
    }

    public function getPrices($iditem)
    {

        $result = $this->link->query("select * from price where iditem=" . $iditem);
        $row = NULL;
        if ($result !== FALSE) {
            while ($obj = $result->fetch_object()) {
                $row[] = $obj;
            }
            $result->close();
            if ($row == NULL) return false;
            return $row;
        } else return false;
    }
    public function getItems()
    {
        $result = $this->link->query("select * from items");
        $row = NULL;
        if ($result !== FALSE) {
            while ($obj = $result->fetch_object()) {
                $row[] = $obj;
            }
            $result->close();
            if ($row == NULL) return false;
            return $row;
        } else return false;
    }
    public function SaveItem($id,$type,$value)
    { //  echo "update price set `".$type."` = '" . $value . "' WHERE idprice=".$id;
      //  if ($this->link->query("insert into price (`".type."`) values ('" . $value . "')") === TRUE) {
        if ($this->link->query("update price set `".$type."` = '" . $value . "' WHERE idprice=".$id) === TRUE) {
            return true;
        } else return false;
    }

}