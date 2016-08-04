<?php
class CheerioMapper extends Mapper
{
    public function getCheerios() {
        $sql = "SELECT c.id, c.message
            FROM cheerios c
            ORDER BY id DESC LIMIT 20";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new CheerioEntity($row);
        }
        return $results;
    }

    public function save(CheerioEntity $cheerio) {
        $sql = "INSERT into cheerios
            (message) values
            (:message);"; 
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "message" => $cheerio->getMessage(),
        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }
    }
}
