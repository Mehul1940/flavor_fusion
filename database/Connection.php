<?php

class Connection
{
    private $server_name;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $pdo;

    public function __construct($server_name, $port, $db_name, $username, $password)
    {
        $this->server_name = $server_name;
        $this->port = $port;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;

        try {
            $this->pdo = new PDO("mysql:host={$server_name};port={$port};dbname={$db_name}", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function save($table_name, $object)
    {
        $object_keys = array_keys($object);

        $object_keys2 = array_map(function ($key) {
            return ':' . $key;
        }, $object_keys);

        $column_names = implode(",", $object_keys);
        $column_names2 = implode(",", $object_keys2);


        $statement = $this->pdo->prepare("INSERT INTO {$table_name}({$column_names}) VALUES ({$column_names2})");

        $statement->execute($object);

        $result = $this->pdo->lastInsertId();

        return $result;
    }

    public function find($table_name, $options)
    {
        $column_names = array_keys($options);

        $condition_array = [];

        for ($i = 0; $i < count($column_names); $i++) {
            array_push($condition_array, "{$column_names[$i]} = :{$column_names[$i]}");
        }

        $condition = implode(" AND ", $condition_array);

        $statement = $this->pdo->prepare("SELECT * FROM {$table_name} WHERE {$condition}");

        $statement->execute($options);

        $result = $statement->fetchAll();

        return $result;
    }

    public function findOne($table_name, $options)
    {
        $column_names = array_keys($options);

        $condition_array = [];

        for ($i = 0; $i < count($column_names); $i++) {
            array_push($condition_array, "{$column_names[$i]} = :{$column_names[$i]}");
        }

        $condition = implode(" AND ", $condition_array);

        $statement = $this->pdo->prepare("SELECT * FROM {$table_name} WHERE {$condition} LIMIT 1");

        $statement->execute($options);

        $result = $statement->fetch();

        return $result;
    }

    public function findById($table_name, $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$table_name} WHERE id = :id");

        $statement->execute(["id" => $id]);

        $result = $statement->fetch();

        return $result;
    }

    public function findAll($table_name)
    {

        $sql = "SELECT * FROM {$table_name}";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }

    public function update($table_name, $id, $object)
    {
        $object_keys = array_keys($object);

        $set_clause = [];

        for ($i = 0; $i < count($object_keys); $i++) {
            array_push($set_clause, "{$object_keys[$i]} = :{$object_keys[$i]}");
        }

        $set_clause = implode(", ", $set_clause);

        $statement = $this->pdo->prepare("UPDATE {$table_name} SET {$set_clause} WHERE id = :id");

        $object['id'] = $id;

        $statement->execute($object);

        return $statement->rowCount();
    }

    public function delete($table_name, $id)
    {
        $statement = $this->pdo->prepare("DELETE FROM {$table_name} WHERE id = :id");

        $statement->execute(["id" => $id]);

        return $statement->rowCount();
    }

    public function search($table_name, $search_fields, $search_term, $limit = null, $offset = null)
    {
        $condition_array = [];
        $params = [];

        foreach ($search_fields as $field) {
            $condition_array[] = "{$field} LIKE :search_term";
        }

        $condition = implode(" OR ", $condition_array);
        $params['search_term'] = "%{$search_term}%";

        $sql = "SELECT * FROM {$table_name} WHERE {$condition}";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params['limit'] = (int)$limit;

            if ($offset !== null) {
                $sql .= " OFFSET :offset";
                $params['offset'] = (int)$offset;
            }
        }

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key == 'limit' || $key == 'offset') {
                $statement->bindValue(":{$key}", $value, PDO::PARAM_INT);
            } else {
                $statement->bindValue(":{$key}", $value, PDO::PARAM_STR);
            }
        }

        $statement->execute();

        return $statement->fetchAll();
    }

    public function paginate($table_name, $limit, $offset = 0, $order_by = 'id', $order_direction = 'ASC')
    {
        $order_direction = strtoupper($order_direction) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM {$table_name} ORDER BY {$order_by} {$order_direction} LIMIT :limit OFFSET :offset";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function count($table_name, $where = [])
    {
        $sql = "SELECT COUNT(*) as total FROM {$table_name}";

        if (!empty($where)) {
            $column_names = array_keys($where);
            $condition_array = [];

            for ($i = 0; $i < count($column_names); $i++) {
                array_push($condition_array, "{$column_names[$i]} = :{$column_names[$i]}");
            }

            $condition = implode(" AND ", $condition_array);
            $sql .= " WHERE {$condition}";
        }

        $statement = $this->pdo->prepare($sql);

        if (!empty($where)) {
            $statement->execute($where);
        } else {
            $statement->execute();
        }

        $result = $statement->fetch();

        return (int)$result['total'];
    }
    public function inner_join($table1, $table2, $join_condition, $columns = '*', $where = [])
    {
        $sql = "SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$join_condition}";

        if (!empty($where)) {
            $column_names = array_keys($where);
            $condition_array = [];

            foreach ($column_names as $column) {
                $condition_array[] = "{$column} = :{$column}";
            }

            $condition = implode(" AND ", $condition_array);
            $sql .= " WHERE {$condition}";
        }

        $statement = $this->pdo->prepare($sql);

        if (!empty($where)) {
            $statement->execute($where);
        } else {
            $statement->execute();
        }

        return $statement->fetchAll();
    }
}
