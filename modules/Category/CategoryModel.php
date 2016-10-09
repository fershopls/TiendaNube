<?php

namespace Modules\Category;

use PDO;

class CategoryModel {

    protected $connection;

    public function solveIds ($row)
    {
        return trim(join('', [$row['id_x'], $row['id_y'], $row['id_z']]));
    }

    public function open ($database)
    {
        $sqlite_connection = new PDO('sqlite:'.$database);
        $sqlite_connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sqlite_connection->exec("CREATE TABLE IF NOT EXISTS magento_macropro (
                            magento_id VARCHAR PRIMARY KEY,
                            route_string VARCHAR);");
        $this->connection = $sqlite_connection;

        return $this;
    }

    public function getSQLite3Connection ()
    {
        return $this->connection;
    }

    public function getSQLite3CategoryIdByRoute ($category_route = "", $fallback = null) {
        $sqlite_result = $this->getSQLite3Connection()->query('SELECT * FROM magento_macropro WHERE `route_string` = "'.$category_route.'"');
        $sqlite_result = $sqlite_result->fetch();
        return $sqlite_result?$sqlite_result['magento_id']:$fallback;
    }
    public function setSQLite3Category ($category_id, $category_route) {
        if ($this->getSQLite3CategoryIdByRoute($category_route))
            return $this->getSQLite3CategoryIdByRoute($category_route);
        # Prepare data and query
        $sqlite_data = array('magento_id' => $category_id, 'route_string' => $category_route);
        $sqlite_query = "INSERT INTO magento_macropro (magento_id, route_string) VALUES (:magento_id, :route_string);";
        $sqlite_stmt = $this->getSQLite3Connection()->prepare($sqlite_query);
        # Bind parameters to statement variables
        $sqlite_stmt->bindParam(':magento_id', $sqlite_data['magento_id']);
        $sqlite_stmt->bindParam(':route_string', $sqlite_data['route_string']);
        # Execute prepared insert statement
        $sqlite_stmt->execute();
        return $this->getSQLite3CategoryIdByRoute($category_route);
    }
    public function deleteSQLite3CategoryByRoute ($category_route = "") {
        # Prepare data and query
        $sqlite_query = "DELETE FROM magento_macropro WHERE route_string LIKE '{$category_route}%';);";
        $sqlite_stmt = $this->getSQLite3Connection()->prepare($sqlite_query);
        # Execute prepared insert statement
        $sqlite_stmt->execute();
    }

}