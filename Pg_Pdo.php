<?php

class PG_PDO {

    public $connect;
    public $error;

    public function connect($host, $user, $password, $dbName) {
        try {
            $dbSource = 'pgsql:dbname='.$dbName.';host='.$host;
            $this->connect = new PDO($dbSource, $user, $password);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
        #$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql)
    {
        try {
            $result = $this->connect->query($sql);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
        return $result;
    }

    public function getData($sql, $onlyColumn='')
    {
        $result = $this->query($sql);
        if (!$result) {
            return [];
        }
        if ($onlyColumn) {
            return $result->fetchAll(PDO::FETCH_COLUMN, $onlyColumn);
        } else {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function getRoles()
    {
		$sql = 'SELECT rolname AS name FROM pg_catalog.pg_roles';
		$sql .= ' ORDER BY rolname';
        return $this->getData($sql);
    }

    function getDatabases()
    {
		if (!$conf['show_system'])
			$where = ' AND NOT pdb.datistemplate';
		else
			$where = ' AND pdb.datallowconn';

		$sql = "
			SELECT pdb.datname AS name
			FROM pg_catalog.pg_database pdb
				LEFT JOIN pg_catalog.pg_roles pr ON (pdb.datdba = pr.oid)
			WHERE true
				{$where}
			ORDER BY pdb.datname";
        return $this->getData($sql);
    }

    function listTables()
    {
        $sql = 'SELECT * FROM information_schema.tables where table_schema=\'public\' ORDER BY table_name;';
        $a = $this->getData($sql);
        $data = array();
        foreach ($a as $k => $v) {
        	$data []= $v['table_name'];
        }
        return $data;
    }

    function listTablesFull()
    {
        $tableNames = $this->listTables();
        $sql = 'SELECT * FROM pg_class WHERE relname IN (\''.implode('\', \'', $tableNames).'\') ORDER BY relname';
        $data = $this->getData($sql);
        return $data;
    }

    function dropTable($table)
    {
        $sql = 'DROP TABLE '.$table;
        $result = $this->query($sql);
        return $result;
    }

    function truncateTable($table)
    {
        $sql = 'TRUNCATE TABLE '.$table;
        $result = $this->query($sql);
        return $result;
    }

    function copyTable($table, $name)
    {
        $sql = 'CREATE TABLE '.$name.' AS SELECT * FROM '.$table;
        $result = $this->query($sql);
        return $result;
    }

    function dropColumn($table, $column)
    {
        $sql = 'ALTER TABLE '.$table.' DROP COLUMN "'.$column.'"';
        $result = $this->query($sql);
        return $result;
    }

    function deleteRow($table, $where)
    {
        $sql = 'DELETE FROM '.$table.' WHERE '.$where;
        $result = $this->query($sql);
        return $result;
    }


    // Список полей
    function listFields($tbl, $onlyNames=false)
    {
        global $pdo;
        $sql = '
      SELECT
        t.table_name,
        c.column_name,
        c.data_type
      FROM information_schema.TABLES t JOIN information_schema.COLUMNS c ON t.table_name::text = c.table_name::text
      WHERE t.table_schema::text = \'public\'::text AND
            t.table_catalog::name = current_database() AND
            t.table_type::text = \'BASE TABLE\'::text AND
            NOT "substring"(t.table_name::text, 1, 1) = \'_\'::text AND
            t.table_name=\''.$tbl.'\'
      ORDER BY t.table_name, c.ordinal_position;
        ';
        $data = $pdo->getData($sql, $onlyNames ? 1 : false);
        return $data;
    }

    // Ключевые поля
    function primaryKeys($table, $onlyNames=false)
    {
        global $pdo;
        $sql = '
        SELECT
            tc.table_schema,
            tc.constraint_name,
            tc.table_name,
            kcu.column_name,
            ccu.table_schema AS foreign_table_schema,
            ccu.table_name AS foreign_table_name,
            ccu.column_name AS foreign_column_name,
            tc.constraint_type
        FROM
            information_schema.table_constraints AS tc
            JOIN information_schema.key_column_usage AS kcu
              ON tc.constraint_name = kcu.constraint_name
              AND tc.table_schema = kcu.table_schema
            JOIN information_schema.constraint_column_usage AS ccu
              ON ccu.constraint_name = tc.constraint_name
              AND ccu.table_schema = tc.table_schema
        WHERE tc.constraint_type = \'PRIMARY KEY\' AND tc.table_name=\''.$table.'\';
        ';
        $data = $pdo->getData($sql, $onlyNames ? 3 : false);
        return $data;
    }
}
