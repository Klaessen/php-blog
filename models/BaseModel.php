<?php
namespace Models;

use ConfigService;
use DatabaseService;
use PDO;

class BaseModel
{
    protected $pdo;
    protected $table ;
    protected $primaryKey = "id";

    public function __construct()
    {
        $config = new ConfigService();
        $db = new DatabaseService($config);
        $this->pdo = $db->getConnection();
    }

    /**
     * Paginate the query results
     * 
     * @param string $query
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return array
     */
    public function paginate(string $query, int $page = 1,  int $limit = 10, array $params = []): array
    {
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindParam(':' . $key, $value);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Insert a new record
     * 
     * @param array $data
     * @return int
     */
    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    /**
     * Update a record
     * 
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data): void
    {
        $columns = '';
        foreach (array_keys($data) as $key) {
            $columns .= $key . ' = :' . $key . ', ';
        }
        $columns = rtrim($columns, ', ');
        $data['id'] = $id;
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $columns WHERE id = :id");
        $stmt->execute($data);
    }

    /**
     * Delete a record
     * 
     * @param int $id
     
     */
    public function delete(int $id):void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}