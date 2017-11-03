<?php

namespace Site\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Site\FrontBundle\Repository\ImageRepository")
 */
class Image implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    protected $file;
    
    public function getFile(){
        return $this->file;
    }
    
    public function setFile($file){
        $this->file = $file;
    }

    public function upload2($request){
        if(null === $this->file){ return false; };
        $message = "";
        if($this->file['error']==0){
            if($this->file['size']<=4000000){
                if($this->file['type']=="image/jpeg" || $this->file['type']=="image/png"){
                    $tempFile = $this->file['tmp_name'];
                    $this->setName($this->file['name']);
                    $targetFile =  $this->getUploadDir(). $this->getName();
                    move_uploaded_file($tempFile,$targetFile);
                    $message = "Upload success";
                    return [true, $message];
                }else{
                    $message = "<p>Format incorrect<p>
                                <p>uploader une nouvelle image</p>
                                <p>format valide: \"jpeg, png, gif\"</p>";
                    return [false, $message];
                }
            }else{
                $message = "<p>Fichier trop volumeux<p>
                            <p>uploader une nouvelle image</p>
                            <p>taille valide est inférieur à 4Mo</p>";
                return [false, $message];
            }
        }else if($this->file['error']==1){
            $message = "Error de téléchargment";
            return [false, $message];
        }
    }
    
    private function getUploadDir(){
        return '../web/assets/images/';
    }

    public function jsonSerialize() {
        return array(
            "keyrandom" => $this->getKeyrandom(),
            "extension" => $this->getExtension()
        );
    }
}
