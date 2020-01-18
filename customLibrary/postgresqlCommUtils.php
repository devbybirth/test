<?php
    //author: pankajgupta@ameyo.com aka baba @ 9560838554
    require_once('config.php');

    Class CommunicationUtils
    {
        private $crmdb_conn_str;
        private $crmdb_conn_resource;
        
        public function __construct($db_host, $db_name)
        {
            $this->crmdb_conn_str = "host=".$db_host." user=".POSTGRES_USER." dbname=".$db_name;
            $this->crmdb_conn_resource = pg_connect($this->crmdb_conn_str) or exit('0x1');
        }

        public function getResult($query)
        {
            $result = pg_query($this->crmdb_conn_resource, $query) ;

            while($row = pg_fetch_row($result))
                $data[] = $row;

            return $data;
        }

        public function insertData($query)
        {
            $result = pg_query($this->crmdb_conn_resource, $query);

            return $result;
        }
    }
?>
