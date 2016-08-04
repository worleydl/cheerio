<?php
class CheerioEntity
{
    protected $id;
    protected $message;
    
    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->message = $data['message'];
    }

    public function getId() {
        return $this->id;
    }

    public function getMessage() {
      return $this->message;
    }
}
