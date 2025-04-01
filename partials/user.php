<?php
require_once("connect.php");

class user extends connect
{
    protected $tablename = "users";

    //function to add user
    public function addUser($data)
    {
        if (!empty($data)) {
            $fields = $placeholder = [];
            foreach ($data as $field => $value) {
                $fields[] = $field;
                $placeholder[] = ":{$field}";
            }
        }

        //$sql = "INSERT INTO $tableName (pname,email,number) VALUES (:pname,:email,:number)";

        $sql = "INSERT INTO {$this->tablename} (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholder) . ") ";

        $stmt = $this->conn->prepare($sql); //in place of mysqli_query we use prepare for SQL injection

        try {
            $this->conn->beginTransaction();
            $stmt->execute($data);
            $lastInsertedId = $this->conn->lastInsertId();
            $this->conn->commit();
            return $lastInsertedId;
        } catch (PDOException $e) {
            echo "error" . $e->getMessage();
            $this->conn->rollBack();
        }
    }

    //function to fetch rows

    public function getRows($start = 0, $limit = 4)
    {
        $sql = "SELECT * FROM {$this->tablename} ORDER BY id DESC LIMIT :start,:limit";
        $stmt = $this->conn->prepare($sql);
        // Bind the start and limit parameters correctly
        $stmt->bindParam(":start", $start, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $results = [];
        }
        return $results;
    }


    //function to get a single row
    public function getRow($field, $value)
    {
        $sql = "SELECT * FROM {$this->tablename} WHERE {$field} = :{$field}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":{$field}" => $value]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC); //fetch the value without indexing
        } else {
            $result = [];
        }
        return $result;
    }


    //function to count rows
    public function rowCount()
    {
        $sql = "SELECT COUNT(*) AS usercount FROM {$this->tablename}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['usercount'];
    }

    //function for upload photos

    public function upload_photo($file)
    {
        if (!empty($file['tmp_name'])) {
            $fileTempPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileType = $file['type'];
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . "." . $fileExtension;
            $allowedExtensions = ["png", "jpg", "jpeg"];

            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadFileDir = getcwd() . '/uploads/';
                $destFileDir = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTempPath, $destFileDir)) {  // ✅ Fixed parameter order
                    return $newFileName;
                }
            }
        }
    }

    //function to update
    public function update($data, $id)
    {
        if (!empty($data)) {
            $fields = "";
            $x = 1;
            $fieldsCount = count($data);
            foreach ($data as $field => $value) {
                $fields .= "{$field}=:{$field}";
                if ($x < $fieldsCount) {
                    $fields .= ",";
                }
                $x++;
            }
        }
        $sql = "UPDATE {$this->tablename} SET {$fields} where id=:id";
        $stmt = $this->conn->prepare($sql);
        try {
            $this->conn->beginTransaction();
            $data['id'] = $id;
            $stmt->execute($data);
            $this->conn->commit();
        } catch (PDOException $e) {
            echo "error" . $e->getMessage();
            $this->conn->rollBack();
        }
    }



    //funtion for delete
    public function deleteRow($id)
    {
        $sql = "DELETE FROM {$this->tablename} WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([':id' => $id]);
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error" . $e->getMessage();
            return false;
        }
    }


    //function for search
    public function searchUser($searchUser, $start = 0, $limit = 4)
    {
        $sql = "SELECT * FROM {$this->tablename} WHERE name LIKE :search ORDER BY id DESC LIMIT :start, :limit";

        $stmt = $this->conn->prepare($sql);

        // Binding parameters correctly
        $stmt->bindValue(':search', "{$searchUser}%", PDO::PARAM_STR);
        $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
